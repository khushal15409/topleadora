<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\RevenueAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RevenueAnalyticsController extends Controller
{
    public function index(Request $request, RevenueAnalyticsService $service): View|StreamedResponse
    {
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($request, $service);
        }

        [$rangeStart, $rangeEnd] = $service->resolveDateRange($request);
        $statuses = $service->resolveStatuses($request);
        $summary = $service->summary($rangeStart, $rangeEnd, $statuses);
        $yearChart = $service->calendarYearMonthlySeries($statuses, $rangeEnd->year);
        $periodMonthly = $service->monthlySeries($rangeStart, $rangeEnd, $statuses);
        $byPlan = $service->revenueByPlan($rangeStart, $rangeEnd, $statuses);
        $breakdown = $service->monthlyBreakdown($rangeStart, $rangeEnd, $statuses);

        $search = trim((string) $request->query('q', ''));

        $payments = Payment::query()
            ->with(['organization', 'plan'])
            ->whereIn('status', $statuses)
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
            ->when($search !== '', function ($q) use ($search): void {
                $term = $search;
                $q->whereHas('organization', function ($oq) use ($term): void {
                    $oq->where('name', 'like', '%'.$term.'%');
                });
            })
            ->orderByDesc('paid_at')
            ->paginate(15)
            ->withQueryString();

        $chartPayload = [
            'yearLabels' => $yearChart['labels'],
            'yearSeries' => $yearChart['series'],
            'periodLabels' => array_column($periodMonthly, 'label'),
            'periodSeries' => array_column($periodMonthly, 'total'),
            'planLabels' => array_column($byPlan, 'name'),
            'planSeries' => array_column($byPlan, 'total'),
        ];

        return view('admin.revenue.index', [
            'rangeStart' => $rangeStart,
            'rangeEnd' => $rangeEnd,
            'range' => $request->query('range', 'month'),
            'paymentStatus' => $request->query('payment_status', Payment::STATUS_SUCCESS),
            'dateFrom' => $request->query('date_from', $rangeStart->toDateString()),
            'dateTo' => $request->query('date_to', $rangeEnd->toDateString()),
            'summary' => $summary,
            'byPlan' => $byPlan,
            'breakdown' => $breakdown,
            'payments' => $payments,
            'chartPayload' => $chartPayload,
            'search' => $search,
        ]);
    }

    private function exportCsv(Request $request, RevenueAnalyticsService $service): StreamedResponse
    {
        [$rangeStart, $rangeEnd] = $service->resolveDateRange($request);
        $statuses = $service->resolveStatuses($request);

        $search = trim((string) $request->query('q', ''));

        $rows = Payment::query()
            ->with(['organization', 'plan'])
            ->whereIn('status', $statuses)
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
            ->when($search !== '', function ($q) use ($search): void {
                $q->whereHas('organization', function ($oq) use ($search): void {
                    $oq->where('name', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('paid_at')
            ->get();

        $filename = 'revenue-payments-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['Organization', 'Plan', 'Amount', 'Currency', 'Paid at', 'Status']);
            foreach ($rows as $p) {
                fputcsv($out, [
                    $p->organization?->name ?? '',
                    $p->plan?->name ?? '',
                    $p->amount,
                    $p->currency,
                    $p->paid_at?->toDateTimeString() ?? '',
                    $p->status,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
