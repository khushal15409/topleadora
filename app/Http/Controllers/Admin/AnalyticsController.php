<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Subscription;
use App\Support\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $year = (int) $request->query('year', (int) now()->year);
        if ($year < 2020 || $year > now()->year + 1) {
            $year = (int) now()->year;
        }

        $totalOrganizations = Organization::query()->count();
        $activeSubscriptions = Subscription::query()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereDate('end_date', '>=', Carbon::today())
            ->count();
        $totalRevenue = (float) Payment::query()
            ->where('status', Payment::STATUS_SUCCESS)
            ->sum('amount');
        $totalLeads = Lead::query()->count();

        $orgGrowth = $this->monthlyCount(Organization::query(), 'created_at', $year);
        $leadGrowth = $this->monthlyCount(Lead::query(), 'created_at', $year);
        $revenueGrowth = $this->monthlySum(Payment::query()->where('status', Payment::STATUS_SUCCESS), 'paid_at', 'amount', $year);

        $chartPayload = [
            'labels' => $orgGrowth['labels'],
            'orgs' => $orgGrowth['series'],
            'leads' => $leadGrowth['series'],
            'revenue' => $revenueGrowth['series'],
        ];

        return view('admin.analytics.index', compact(
            'year',
            'totalOrganizations',
            'activeSubscriptions',
            'totalRevenue',
            'totalLeads',
            'chartPayload'
        ));
    }

    /**
     * @return array{labels: list<string>, series: list<int>}
     */
    private function monthlyCount($query, string $dateCol, int $year): array
    {
        $rows = (clone $query)
            ->selectRaw("DATE_FORMAT($dateCol, '%Y-%m') as ym, COUNT(*) as c")
            ->whereYear($dateCol, $year)
            ->groupBy('ym')
            ->pluck('c', 'ym');

        $labels = [];
        $series = [];
        for ($m = 1; $m <= 12; $m++) {
            $ym = sprintf('%04d-%02d', $year, $m);
            $labels[] = Carbon::createFromDate($year, $m, 1)->format('M');
            $series[] = (int) ($rows[$ym] ?? 0);
        }

        return ['labels' => $labels, 'series' => $series];
    }

    /**
     * @return array{labels: list<string>, series: list<float>}
     */
    private function monthlySum($query, string $dateCol, string $sumCol, int $year): array
    {
        $rows = (clone $query)
            ->selectRaw("DATE_FORMAT($dateCol, '%Y-%m') as ym, SUM($sumCol) as s")
            ->whereYear($dateCol, $year)
            ->groupBy('ym')
            ->pluck('s', 'ym');

        $labels = [];
        $series = [];
        for ($m = 1; $m <= 12; $m++) {
            $ym = sprintf('%04d-%02d', $year, $m);
            $labels[] = Carbon::createFromDate($year, $m, 1)->format('M');
            $series[] = (float) ($rows[$ym] ?? 0);
        }

        return ['labels' => $labels, 'series' => $series];
    }
}

