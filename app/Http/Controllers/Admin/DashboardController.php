<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Organization;
use App\Models\User;
use App\Services\SubscriptionMonitoringService;
use App\Support\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->hasRole(Roles::SUPER_ADMIN)) {
            return view('admin.dashboard', $this->superAdminData());
        }

        $user->loadMissing('organization.plan');

        return view('admin.dashboard', $this->organizationData($user));
    }

    /**
     * @return array<string, mixed>
     */
    private function superAdminData(): array
    {
        $organizationsCount = Organization::query()->count();
        $tenantUsersCount = User::query()->whereNotNull('organization_id')->count();
        $inboxTotal = Contact::query()->count();
        $inboxUnread = Contact::query()->where('is_read', false)->count();
        $subscriptionMonitor = app(SubscriptionMonitoringService::class);
        $monitorRows = $subscriptionMonitor->allRows();
        $subCounts = $subscriptionMonitor->dashboardCounts($monitorRows);
        $expiringSoonRows = $subscriptionMonitor->expiringSoon($monitorRows)->take(10)->all();

        $recentContacts = Contact::query()
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn(Contact $c) => [
                'name' => $c->name,
                'detail' => $c->email,
                'meta' => $c->created_at?->diffForHumans() ?? '—',
                'badge' => $c->is_read ? __('Read') : __('Unread'),
                'badge_variant' => $c->is_read ? 'success' : 'warning',
            ])
            ->all();

        $inboxLast7 = $this->countsLastDays(
            Contact::query(),
            'created_at',
            7
        );

        $orgsLast7 = $this->countsLastDays(
            Organization::query(),
            'created_at',
            7
        );

        $lineTrend = $this->normalizeSparkline($inboxLast7);
        $columnBars = array_slice($orgsLast7, -5);
        if (max($columnBars) === 0) {
            $columnBars = [2, 4, 3, 5, 4];
        }

        return [
            'dashboardRole' => 'super_admin',
            'hero' => [
                'title' => __('Platform control center'),
                'subtitle' => __('Monitor tenants, inbound interest, and subscription health at a glance.'),
                'badge' => __('SuperAdmin'),
            ],
            'stats' => [
                [
                    'label' => __('Organizations'),
                    'value' => number_format($organizationsCount),
                    'hint' => __('Active workspaces on the platform'),
                    'icon' => 'ri-building-4-line',
                    'tone' => 'primary',
                ],
                [
                    'label' => __('Tenant users'),
                    'value' => number_format($tenantUsersCount),
                    'hint' => __('People signed in under an organization'),
                    'icon' => 'ri-team-line',
                    'tone' => 'success',
                ],
                [
                    'label' => __('Inbox messages'),
                    'value' => number_format($inboxTotal),
                    'hint' => $inboxUnread > 0
                        ? __(':count unread in inbox', ['count' => $inboxUnread])
                        : __('All caught up'),
                    'icon' => 'ri-mail-open-line',
                    'tone' => 'info',
                ],
                [
                    'label' => __('Active subscriptions'),
                    'value' => number_format($subCounts['active']),
                    'hint' => __('Paid periods currently valid'),
                    'icon' => 'ri-bank-card-line',
                    'tone' => 'warning',
                ],
            ],
            'insights' => [
                __('Route unread contact messages to owners so nothing sits in the queue over a weekend.'),
                __('Organizations on trial convert best when onboarding is finished within 48 hours.'),
                __('Keep plans aligned with broadcast limits so upsell moments feel natural, not forced.'),
            ],
            'quickLinks' => [
                ['label' => __('Organizations'), 'route' => 'admin.organizations.index', 'icon' => 'ri-building-4-line'],
                ['label' => __('Subscriptions'), 'route' => 'admin.subscriptions.index', 'icon' => 'ri-repeat-line'],
                ['label' => __('Revenue'), 'route' => 'admin.revenue.index', 'icon' => 'ri-line-chart-line'],
                ['label' => __('Contact inbox'), 'route' => 'admin.contacts.index', 'icon' => 'ri-inbox-line'],
            ],
            'tableTitle' => __('Latest contact messages'),
            'tableEmpty' => __('No messages yet — your landing contact form will populate this list.'),
            'recentRows' => $recentContacts,
            'chartPayload' => [
                'weeklyBar' => $this->scaleForBarChart($inboxLast7),
                'weeklyCategories' => $this->lastSevenDayLabels(),
                'weeklySeriesName' => __('Messages'),
                'lineTrend' => $lineTrend,
                'columnBars' => array_map(fn(int $n) => min(100, $n * 10 + 15), $columnBars),
                'weeklyCaption' => __('Inbound messages (7 days)'),
                'lineCaption' => __('Inquiry momentum'),
                'columnCaption' => __('New organizations (5 recent days)'),
            ],
            'subscriptionDashboard' => [
                'counts' => $subCounts,
                'expiringSoon' => $expiringSoonRows,
            ],
            'crmSummary' => [
                'today_followups' => 0, // Super admin doesn't usually handle followups
                'total_leads' => Lead::query()->count(),
                'closed_deals' => Lead::query()->where('status', Lead::STATUS_CLOSED)->count(),
                'total_broadcasts' => \App\Models\Broadcast::query()->count(),
                'total_organizations' => $organizationsCount,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function organizationData(User $user): array
    {
        $org = $user->organization;
        $orgId = $user->organization_id;

        $teamCount = $orgId
            ? User::query()->where('organization_id', $orgId)->count()
            : 0;

        $trialEnds = $org?->trial_ends_at;
        $hasPlan = $org?->hasPaidPlan();
        $accessLabel = $hasPlan
            ? __('Subscribed')
            : ($org?->trialIsActive() ? __('Trial active') : __('Needs plan'));

        $trialHint = __('Upgrade to restore CRM');
        if ($trialEnds !== null && $org?->trialIsActive() && !$hasPlan) {
            $trialHint = __('Trial ends :when', ['when' => $trialEnds->diffForHumans()]);
        } elseif ($hasPlan) {
            $trialHint = __('Full CRM unlocked');
        }

        $planName = $org?->activeSubscription()?->plan?->name
            ?? $org?->plan?->name
            ?? __('No plan');

        $teamLast7 = $orgId
            ? $this->countsLastDays(
                User::query()->where('organization_id', $orgId),
                'created_at',
                7
            )
            : array_fill(0, 7, 0);

        $recentTeam = $orgId
            ? User::query()
                ->where('organization_id', $orgId)
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn(User $u) => [
                    'name' => $u->name,
                    'detail' => $u->email,
                    'meta' => $u->created_at?->diffForHumans() ?? '—',
                    'badge' => $u->id === $user->id ? __('You') : __('Member'),
                    'badge_variant' => $u->id === $user->id ? 'primary' : 'secondary',
                ])
                ->all()
            : [];

        $crmSummary = null;
        if ($orgId) {
            $base = fn(): Builder => Lead::query()
                ->forOrganization((int) $orgId)
                ->when(
                    !$user->canViewAllOrganizationLeads(),
                    fn($q) => $q->where('assigned_to', $user->id)
                );
            $crmSummary = [
                'today_followups' => $base()
                    ->whereNull('followup_completed_at')
                    ->whereDate('next_followup_at', Carbon::now()->toDateString())
                    ->count(),
                'total_leads' => $base()->count(),
                'closed_deals' => $base()->where('status', Lead::STATUS_CLOSED)->count(),
                'total_broadcasts' => $org?->broadcasts()->count() ?? 0,
            ];
        }

        return [
            'dashboardRole' => 'organization',
            'hero' => [
                'title' => __('Welcome back, :name', ['name' => Str::before($user->name, ' ')]),
                'subtitle' => __('Your workspace overview — align the team and keep CRM access healthy.'),
                'badge' => __('Organization'),
            ],
            'stats' => [
                [
                    'label' => __('Organization'),
                    'value' => Str::limit($org?->name ?? '—', 24),
                    'hint' => $planName,
                    'icon' => 'ri-building-line',
                    'tone' => 'primary',
                ],
                [
                    'label' => __('Team members'),
                    'value' => number_format($teamCount),
                    'hint' => __('People with access under this tenant'),
                    'icon' => 'ri-group-line',
                    'tone' => 'success',
                ],
                [
                    'label' => __('Access status'),
                    'value' => $accessLabel,
                    'hint' => $trialHint,
                    'icon' => 'ri-shield-check-line',
                    'tone' => 'info',
                ],
                [
                    'label' => __('Your role'),
                    'value' => $user->roles->pluck('name')->first() ?? __('Member'),
                    'hint' => __('Profile & security live under your avatar'),
                    'icon' => 'ri-user-settings-line',
                    'tone' => 'warning',
                ],
            ],
            'insights' => [
                __('Sharpen follow-ups by keeping pipeline stages consistent across the team.'),
                __('Use broadcasts sparingly: segmented sends outperform batch blasts for revenue.'),
                __('When trial time is short, prioritize onboarding tasks that reveal value on day one.'),
            ],
            'quickLinks' => [
                ['label' => __('Leads'), 'route' => 'dashboard.leads.index', 'icon' => 'ri-user-search-line'],
                ['label' => __('Pipeline'), 'route' => 'dashboard.pipeline.index', 'icon' => 'ri-git-merge-line'],
                ['label' => __('Follow-ups'), 'route' => 'dashboard.followups.index', 'icon' => 'ri-time-line'],
                ['label' => __('Broadcast'), 'route' => 'dashboard.broadcast.index', 'icon' => 'ri-send-plane-line'],
                ['label' => __('Reports'), 'route' => 'dashboard.reports.index', 'icon' => 'ri-file-chart-line'],
            ],
            'tableTitle' => __('Recent team members'),
            'tableEmpty' => __('Invite colleagues from Settings when you are ready to scale the workspace.'),
            'recentRows' => $recentTeam,
            'chartPayload' => [
                'weeklyBar' => $this->scaleForBarChart($teamLast7),
                'weeklyCategories' => $this->lastSevenDayLabels(),
                'weeklySeriesName' => __('Seats'),
                'lineTrend' => $this->normalizeSparkline($teamLast7),
                'columnBars' => $this->barsFromSeries(array_slice($teamLast7, -5)),
                'weeklyCaption' => __('Team growth signal (7 days)'),
                'lineCaption' => __('Engagement curve'),
                'columnCaption' => __('New seats (5 recent days)'),
            ],
            'crmSummary' => $crmSummary,
        ];
    }

    /**
     * @param  Builder<Model>  $query
     * @return array<int, int>
     */
    private function countsLastDays($query, string $column, int $days): array
    {
        $counts = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $day = Carbon::now()->startOfDay()->subDays($i);
            $counts[] = (clone $query)->whereDate($column, $day->toDateString())->count();
        }

        return $counts;
    }

    /**
     * @return array<int, string>
     */
    private function lastSevenDayLabels(): array
    {
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subDays($i)->format('D');
        }

        return $labels;
    }

    /**
     * @param  array<int, int>  $series
     */
    private function scaleForBarChart(array $series): array
    {
        $max = max($series) ?: 1;

        return array_map(function (int $n) use ($max) {
            $t = $n / $max;

            return (int) round(35 + $t * 55);
        }, $series);
    }

    /**
     * @param  array<int, int>  $series
     * @return array<int, int>
     */
    private function normalizeSparkline(array $series): array
    {
        $max = max($series) ?: 1;

        return array_values(array_map(function (int $n) use ($max) {
            return max(5, min(90, (int) round(($n / $max) * 85)));
        }, $series));
    }

    /**
     * @param  array<int, int>  $slice
     * @return array<int, int>
     */
    private function barsFromSeries(array $slice): array
    {
        if ($slice === []) {
            return [25, 40, 30, 55, 45];
        }
        $max = max($slice) ?: 1;

        return array_map(fn(int $n) => min(100, max(20, (int) round(($n / $max) * 75 + 20))), $slice);
    }
}
