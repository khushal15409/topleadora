<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExtendSubscriptionRequest;
use App\Http\Requests\Admin\SubscriptionChangePlanRequest;
use App\Models\Organization;
use App\Models\Plan;
use App\Services\SubscriptionMonitoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionMonitoringController extends Controller
{
    public function index(Request $request, SubscriptionMonitoringService $service): View|StreamedResponse
    {
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($request, $service);
        }

        $filter = $request->query('filter', 'all');
        $filter = is_string($filter) ? $filter : 'all';
        if (! in_array($filter, ['all', 'active', 'expired', 'expiring', 'trial'], true)) {
            $filter = 'all';
        }
        $allRows = $service->allRows();
        $rows = $service->filterRows($allRows, $filter === 'all' ? null : $filter);
        $counts = $service->dashboardCounts($allRows);

        return view('admin.subscriptions.index', [
            'rows' => $rows,
            'counts' => $counts,
            'filter' => $filter,
        ]);
    }

    public function extend(ExtendSubscriptionRequest $request, Organization $organization): RedirectResponse
    {
        $organization->syncExpiredSubscriptions();
        $days = (int) $request->validated('days');
        $sub = $organization->activeSubscription();

        if ($sub !== null) {
            $sub->update([
                'end_date' => $sub->end_date->copy()->addDays($days),
                'status' => \App\Models\Subscription::STATUS_ACTIVE,
            ]);

            return redirect()
                ->route('admin.subscriptions.index')
                ->with('success', __('Extended subscription by :days days.', ['days' => $days]));
        }

        $plan = $organization->plan
            ?? Plan::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->first();

        abort_if($plan === null, 422, __('No plan available to restore subscription.'));

        $organization->activateSubscription($plan);

        return redirect()
            ->route('admin.subscriptions.index')
            ->with('success', __('Subscription reactivated with a new :days-day period.', ['days' => 30]));
    }

    public function changePlanForm(Organization $organization, SubscriptionMonitoringService $service): View
    {
        $organization->loadMissing('plan');
        $plans = Plan::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $row = $service->summarizeOrganization($organization);

        return view('admin.subscriptions.change-plan', [
            'organization' => $organization,
            'plans' => $plans,
            'row' => $row,
        ]);
    }

    public function changePlan(SubscriptionChangePlanRequest $request, Organization $organization): RedirectResponse
    {
        $plan = Plan::query()->whereKey($request->validated('plan_id'))->where('is_active', true)->firstOrFail();
        $organization->activateSubscription($plan);

        return redirect()
            ->route('admin.subscriptions.index')
            ->with('success', __('Plan updated to :plan.', ['plan' => $plan->name]));
    }

    private function exportCsv(Request $request, SubscriptionMonitoringService $service): StreamedResponse
    {
        $filter = $request->query('filter', 'all');
        $filter = is_string($filter) ? $filter : 'all';
        if (! in_array($filter, ['all', 'active', 'expired', 'expiring', 'trial'], true)) {
            $filter = 'all';
        }
        $allRows = $service->allRows();
        $rows = $service->filterRows($allRows, $filter === 'all' ? null : $filter);

        $filename = 'subscriptions-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, [
                'Organization',
                'Plan',
                'Status',
                'Start date',
                'End date',
                'Days remaining',
                'Amount',
                'Currency',
            ]);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['organization_name'],
                    $r['plan_name'],
                    $r['status_label'],
                    $r['start_date']?->toDateString() ?? '',
                    $r['end_date']?->toDateString() ?? '',
                    $r['days_remaining'] ?? '',
                    $r['amount'] ?? '',
                    $r['currency'] ?? '',
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
