<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SubscriptionMonitoringService
{
    /**
     * @return array<string, mixed>
     */
    public function summarizeOrganization(Organization $organization): array
    {
        $organization->loadMissing('plan', 'subscriptions.plan');
        $today = Carbon::today();
        $activeSub = $organization->activeSubscription();

        if ($activeSub !== null) {
            return $this->paidRow($organization, $activeSub, $today);
        }

        if ($organization->trialIsActive()) {
            return $this->trialRow($organization, $today);
        }

        return $this->expiredRow($organization, $today);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function allRows(): Collection
    {
        return Organization::query()
            ->with(['plan', 'subscriptions.plan'])
            ->orderBy('name')
            ->get()
            ->map(fn (Organization $org) => $this->summarizeOrganization($org));
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return Collection<int, array<string, mixed>>
     */
    public function filterRows(Collection $rows, ?string $filter): Collection
    {
        if ($filter === null || $filter === '' || $filter === 'all') {
            return $rows;
        }

        return $rows->filter(function (array $row) use ($filter): bool {
            return match ($filter) {
                'active' => $row['status_key'] === 'active',
                'expired' => $row['status_key'] === 'expired',
                'expiring' => $row['expiring_within_7'] === true,
                'trial' => $row['status_key'] === 'trial',
                default => true,
            };
        })->values();
    }

    /**
     * @return array<string, int>
     */
    public function dashboardCounts(Collection $rows): array
    {
        return [
            'active' => $rows->where('status_key', 'active')->count(),
            'expired' => $rows->where('status_key', 'expired')->count(),
            'expiring_7d' => $rows->where('expiring_within_7', true)
                ->filter(fn (array $r) => $r['status_key'] !== 'expired')
                ->count(),
            'trial' => $rows->where('status_key', 'trial')->count(),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return Collection<int, array<string, mixed>>
     */
    public function expiringSoon(Collection $rows, int $withinDays = 7): Collection
    {
        return $rows
            ->filter(function (array $row) use ($withinDays): bool {
                if ($row['status_key'] === 'expired') {
                    return false;
                }
                $d = $row['days_remaining'];
                if (! is_int($d)) {
                    return false;
                }

                return $d >= 0 && $d <= $withinDays;
            })
            ->sortBy('days_remaining')
            ->values();
    }

    /**
     * @return array<string, mixed>
     */
    private function paidRow(Organization $organization, Subscription $activeSub, Carbon $today): array
    {
        $end = $activeSub->end_date->copy()->startOfDay();
        $start = $activeSub->start_date->copy()->startOfDay();
        $daysRemaining = (int) $today->diffInDays($end, false);
        $plan = $activeSub->plan;

        return [
            'organization' => $organization,
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
            'plan_name' => $plan?->name ?? '—',
            'status_key' => 'active',
            'status_label' => __('Active'),
            'start_date' => $start,
            'end_date' => $end,
            'days_remaining' => $daysRemaining,
            'days_display' => $daysRemaining >= 0 ? (string) $daysRemaining : __('Expired'),
            'days_color' => $this->daysColor($daysRemaining),
            'amount' => $plan !== null ? (float) $plan->price_monthly : null,
            'currency' => $plan?->currency ?? 'INR',
            'expiring_within_7' => $daysRemaining >= 0 && $daysRemaining <= 7,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function trialRow(Organization $organization, Carbon $today): array
    {
        $end = $organization->trial_ends_at?->copy()->startOfDay();
        $start = $organization->created_at?->copy()->startOfDay();
        $daysRemaining = $end !== null ? (int) $today->diffInDays($end, false) : 0;
        $plan = $organization->plan;

        return [
            'organization' => $organization,
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
            'plan_name' => $plan !== null ? __('Trial · :plan', ['plan' => $plan->name]) : __('Trial'),
            'status_key' => 'trial',
            'status_label' => __('Trial'),
            'start_date' => $start,
            'end_date' => $end,
            'days_remaining' => $daysRemaining,
            'days_display' => $daysRemaining >= 0 ? (string) $daysRemaining : __('Expired'),
            'days_color' => $this->daysColor($daysRemaining),
            'amount' => null,
            'currency' => 'INR',
            'expiring_within_7' => $daysRemaining >= 0 && $daysRemaining <= 7,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function expiredRow(Organization $organization, Carbon $today): array
    {
        $lastSub = $organization->subscriptions()->orderByDesc('end_date')->first();

        if ($lastSub !== null && $lastSub->end_date->copy()->startOfDay()->lt($today)) {
            $plan = $lastSub->plan;
            $end = $lastSub->end_date->copy()->startOfDay();
            $start = $lastSub->start_date->copy()->startOfDay();
            $daysRemaining = (int) $today->diffInDays($end, false);
            $planName = $plan?->name ?? '—';

            return [
                'organization' => $organization,
                'organization_id' => $organization->id,
                'organization_name' => $organization->name,
                'plan_name' => $planName,
                'status_key' => 'expired',
                'status_label' => __('Expired'),
                'start_date' => $start,
                'end_date' => $end,
                'days_remaining' => $daysRemaining,
                'days_display' => __('Expired'),
                'days_color' => 'danger',
                'amount' => $plan !== null ? (float) $plan->price_monthly : null,
                'currency' => $plan?->currency ?? 'INR',
                'expiring_within_7' => false,
            ];
        }

        $trialEnd = $organization->trial_ends_at?->copy()->startOfDay();
        if ($trialEnd !== null) {
            $daysRemaining = (int) $today->diffInDays($trialEnd, false);

            return [
                'organization' => $organization,
                'organization_id' => $organization->id,
                'organization_name' => $organization->name,
                'plan_name' => $organization->plan?->name ?? __('Trial lapsed'),
                'status_key' => 'expired',
                'status_label' => __('Expired'),
                'start_date' => $organization->created_at?->copy()->startOfDay(),
                'end_date' => $trialEnd,
                'days_remaining' => $daysRemaining,
                'days_display' => __('Expired'),
                'days_color' => 'danger',
                'amount' => null,
                'currency' => 'INR',
                'expiring_within_7' => false,
            ];
        }

        return [
            'organization' => $organization,
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
            'plan_name' => $organization->plan?->name ?? '—',
            'status_key' => 'expired',
            'status_label' => __('Expired'),
            'start_date' => null,
            'end_date' => null,
            'days_remaining' => null,
            'days_display' => '—',
            'days_color' => 'secondary',
            'amount' => $organization->plan !== null ? (float) $organization->plan->price_monthly : null,
            'currency' => $organization->plan?->currency ?? 'INR',
            'expiring_within_7' => false,
        ];
    }

    private function daysColor(int $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return 'danger';
        }
        if ($daysRemaining <= 7) {
            return 'warning';
        }

        return 'success';
    }
}
