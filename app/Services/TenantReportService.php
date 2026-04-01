<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Carbon;

class TenantReportService
{
    /**
     * @return array{start: Carbon, end: Carbon}
     */
    public function periodRange(string $period): array
    {
        $end = Carbon::now()->endOfDay();
        $start = match ($period) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfDay(),
        };

        return ['start' => $start, 'end' => $end];
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user, string $period): array
    {
        if (! in_array($period, ['today', 'week', 'month'], true)) {
            $period = 'week';
        }

        ['start' => $start, 'end' => $end] = $this->periodRange($period);

        $q = fn () => Lead::query()->visibleTo($user);

        $totalLeads = $q()
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $newLeads = $q()
            ->where('status', Lead::STATUS_NEW)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $followupsPending = $q()
            ->whereNull('followup_completed_at')
            ->whereNotNull('next_followup_at')
            ->whereBetween('next_followup_at', [$start, $end])
            ->count();

        $closedDeals = $q()
            ->where('status', Lead::STATUS_CLOSED)
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $byDate = $q()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $growthLabels = [];
        $growthCounts = [];
        $cursor = $start->copy()->startOfDay();
        $lastDay = $end->copy()->startOfDay();
        while ($cursor->lte($lastDay)) {
            $key = $cursor->toDateString();
            $growthLabels[] = $cursor->format('M j');
            $growthCounts[] = (int) ($byDate[$key] ?? 0);
            $cursor->addDay();
        }

        $pipelineKeys = [
            Lead::STATUS_NEW => __('New'),
            Lead::STATUS_INTERESTED => __('Interested'),
            Lead::STATUS_FOLLOW_UP => __('Follow-up'),
            Lead::STATUS_CLOSED => __('Closed'),
        ];
        $pipelineLabels = array_values($pipelineKeys);
        $pipelineCounts = [];
        foreach (array_keys($pipelineKeys) as $status) {
            $pipelineCounts[] = $q()
                ->where('status', $status)
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        $recentLeads = $q()
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('id')
            ->limit(12)
            ->get(['id', 'name', 'phone', 'status', 'created_at']);

        return [
            'period' => $period,
            'start' => $start,
            'end' => $end,
            'summary' => [
                'total_leads' => $totalLeads,
                'new_leads' => $newLeads,
                'followups_pending' => $followupsPending,
                'closed_deals' => $closedDeals,
            ],
            'growthLabels' => $growthLabels,
            'growthCounts' => $growthCounts,
            'pipelineLabels' => $pipelineLabels,
            'pipelineCounts' => $pipelineCounts,
            'recentLeads' => $recentLeads,
        ];
    }
}
