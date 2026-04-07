<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RevenueAnalyticsService
{
    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    public function resolveDateRange(Request $request): array
    {
        $range = $request->query('range', 'month');
        $range = is_string($range) ? $range : 'month';
        $now = Carbon::now();

        if ($range === 'custom') {
            $from = Carbon::parse((string) $request->query('date_from', $now->toDateString()))->startOfDay();
            $to = Carbon::parse((string) $request->query('date_to', $now->toDateString()))->endOfDay();
            if ($from->gt($to)) {
                [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }

            return [$from, $to];
        }

        return match ($range) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }

    /**
     * @return list<string>
     */
    public function resolveStatuses(Request $request): array
    {
        $s = $request->query('payment_status', Payment::STATUS_SUCCESS);
        $s = is_string($s) ? strtolower($s) : Payment::STATUS_SUCCESS;

        if ($s === 'all') {
            return [Payment::STATUS_SUCCESS, Payment::STATUS_FAILED, Payment::STATUS_PENDING];
        }

        if (in_array($s, [Payment::STATUS_SUCCESS, Payment::STATUS_FAILED, Payment::STATUS_PENDING], true)) {
            return [$s];
        }

        return [Payment::STATUS_SUCCESS];
    }

    /**
     * @param  list<string>  $statuses
     * @return array<string, mixed>
     */
    public function summary(Carbon $rangeStart, Carbon $rangeEnd, array $statuses): array
    {
        $scoped = Payment::query()
            ->whereIn('status', $statuses)
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd]);

        $totalRevenue = (float) (clone $scoped)->sum('amount');
        $transactionCount = (clone $scoped)->count();

        $now = Carbon::now();
        $thisMonthRevenue = (float) Payment::query()
            ->whereIn('status', $statuses)
            ->whereBetween('paid_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->sum('amount');

        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $lastMonthRevenue = (float) Payment::query()
            ->whereIn('status', $statuses)
            ->whereBetween('paid_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        return [
            'total_revenue' => $totalRevenue,
            'this_month_revenue' => $thisMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'transaction_count' => $transactionCount,
        ];
    }

    /**
     * @param  list<string>  $statuses
     * @return array<int, array{label: string, total: float}>
     */
    public function monthlySeries(Carbon $rangeStart, Carbon $rangeEnd, array $statuses): array
    {
        $out = [];
        $cursor = $rangeStart->copy()->startOfMonth();
        $endMonth = $rangeEnd->copy()->endOfMonth();

        while ($cursor->lte($endMonth)) {
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();
            $sliceStart = $rangeStart->copy()->max($monthStart);
            $sliceEnd = $rangeEnd->copy()->min($monthEnd);

            $total = (float) Payment::query()
                ->whereIn('status', $statuses)
                ->whereBetween('paid_at', [$sliceStart, $sliceEnd])
                ->sum('amount');

            $out[] = [
                'label' => $cursor->format('M Y'),
                'total' => $total,
            ];
            $cursor->addMonth();
        }

        return $out;
    }

    /**
     * @param  list<string>  $statuses
     * @return array<int, array{plan_id: int|null, name: string, total: float}>
     */
    public function revenueByPlan(Carbon $rangeStart, Carbon $rangeEnd, array $statuses): array
    {
        $rows = Payment::query()
            ->selectRaw('plan_id, SUM(amount) as revenue')
            ->whereIn('status', $statuses)
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
            ->groupBy('plan_id')
            ->get();

        $plans = Plan::query()->whereIn('id', $rows->pluck('plan_id'))->get()->keyBy('id');

        return $rows->map(function ($row) use ($plans): array {
            $plan = $plans->get($row->plan_id);

            return [
                'plan_id' => (int) $row->plan_id,
                'name' => $plan?->name ?? '—',
                'total' => (float) $row->revenue,
            ];
        })->sortByDesc('total')->values()->all();
    }

    /**
     * @param  list<string>  $statuses
     * @return array<int, array{month: string, revenue: float, payments: int}>
     */
    public function monthlyBreakdown(Carbon $rangeStart, Carbon $rangeEnd, array $statuses): array
    {
        $s = $this->monthlySeries($rangeStart, $rangeEnd, $statuses);
        $out = [];

        foreach ($s as $i => $item) {
            $cursor = $rangeStart->copy()->startOfMonth()->addMonths($i);
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();
            $sliceStart = $rangeStart->copy()->max($monthStart);
            $sliceEnd = $rangeEnd->copy()->min($monthEnd);

            $payments = Payment::query()
                ->whereIn('status', $statuses)
                ->whereBetween('paid_at', [$sliceStart, $sliceEnd])
                ->count();

            $out[] = [
                'month' => $item['label'],
                'revenue' => $item['total'],
                'payments' => $payments,
            ];
        }

        return $out;
    }

    /**
     * Full calendar year Jan–Dec for chart (current year), status-filtered.
     *
     * @param  list<string>  $statuses
     * @return array{labels: list<string>, series: list<float>}
     */
    public function calendarYearMonthlySeries(array $statuses, ?int $year = null): array
    {
        $year ??= Carbon::now()->year;
        $labels = [];
        $series = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::createFromDate($year, $m, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($year, $m, 1)->endOfMonth();
            $labels[] = $monthStart->format('M');
            $series[] = (float) Payment::query()
                ->whereIn('status', $statuses)
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount');
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }
}
