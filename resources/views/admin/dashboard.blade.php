@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $hero = $hero ?? null;
        $stats = $stats ?? [];
        $quickLinks = $quickLinks ?? [];
        $insights = $insights ?? [];
        $recentRows = $recentRows ?? [];
        $tableTitle = $tableTitle ?? __('Recent activity');
        $tableEmpty = $tableEmpty ?? __('No recent activity.');
        $crmSummary = $crmSummary ?? null;
        $dashboardRole = $dashboardRole ?? 'organization';
    @endphp

    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-1">
                {{ $hero['title'] ?? __('Dashboard') }}
            </h5>
            <p class="text-textmuted text-[12px] mb-0">{{ $hero['subtitle'] ?? __('Role-based overview') }}</p>
        </div>

        <div class="flex xl:my-auto right-content align-items-center gap-2">
            @if (!empty($hero['badge']))
                <span class="badge bg-primary/10 text-primary rounded-full px-3 py-2 text-[11px] font-semibold border border-primary/20">
                    <i class="ri-shield-user-line me-1"></i>{{ $hero['badge'] }}
                </span>
            @endif
            <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon !mb-0" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (($freeAccessMode ?? false) && auth()->user()?->hasRole(\App\Support\Roles::ORGANIZATION))
        <div class="bg-info/10 text-info border border-info/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
            <div class="flex items-center gap-2">
                <i class="ri-information-line text-lg"></i>
                <span><strong>{{ __('Free access mode is enabled.') }}</strong> {{ __('All features unlocked.') }}</span>
            </div>
            <button type="button" class="text-info hover:text-info/80" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    {{-- New role-based dashboard --}}
    <div class="grid grid-cols-12 gap-x-6 gap-y-6">
        {{-- Metrics --}}
        @foreach ($stats as $s)
            @php
                $tone = $s['tone'] ?? 'primary';
                $toneMap = [
                    'primary' => 'bg-primary/10 text-primary border-primary/20',
                    'success' => 'bg-success/10 text-success border-success/20',
                    'info' => 'bg-info/10 text-info border-info/20',
                    'warning' => 'bg-warning/10 text-warning border-warning/20',
                    'danger' => 'bg-danger/10 text-danger border-danger/20',
                ];
                $pillCls = $toneMap[$tone] ?? $toneMap['primary'];
            @endphp
            <div class="col-span-12 md:col-span-6 xl:col-span-3">
                <div class="box shadow-none border border-defaultborder/10 h-full">
                    <div class="box-body !p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-textmuted text-[11px] font-bold uppercase tracking-wider mb-1">{{ $s['label'] ?? '' }}</p>
                                <p class="text-[1.35rem] font-bold mb-1 truncate">{{ $s['value'] ?? '—' }}</p>
                                @if (!empty($s['hint']))
                                    <p class="text-[11px] text-textmuted mb-0 truncate">{{ $s['hint'] }}</p>
                                @endif
                            </div>
                            @if (!empty($s['icon']))
                                <span class="ti-avatar ti-avatar-md rounded-md border {{ $pillCls }}">
                                    <i class="{{ $s['icon'] }} text-[18px]"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- CRM summary (Org / Sales) --}}
        @if (is_array($crmSummary))
            <div class="col-span-12">
                <div class="box shadow-none border border-defaultborder/10">
                    <div class="box-header !border-b !border-defaultborder/10">
                        <h4 class="box-title font-semibold">{{ __('CRM snapshot') }}</h4>
                        <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Today’s focus metrics from your workspace.') }}</p>
                    </div>
                    <div class="box-body">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                                <div class="p-4 rounded-md border border-defaultborder/10">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-textmuted mb-1">{{ __('Pending followups') }}</p>
                                    <p class="text-2xl font-bold mb-0">{{ number_format((int) ($crmSummary['today_followups'] ?? 0)) }}</p>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                                <div class="p-4 rounded-md border border-defaultborder/10">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-textmuted mb-1">{{ __('Total leads') }}</p>
                                    <p class="text-2xl font-bold mb-0">{{ number_format((int) ($crmSummary['total_leads'] ?? 0)) }}</p>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                                <div class="p-4 rounded-md border border-defaultborder/10">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-textmuted mb-1">{{ __('Closed deals') }}</p>
                                    <p class="text-2xl font-bold mb-0">{{ number_format((int) ($crmSummary['closed_deals'] ?? 0)) }}</p>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                                <div class="p-4 rounded-md border border-defaultborder/10">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-textmuted mb-1">{{ __('Broadcasts') }}</p>
                                    <p class="text-2xl font-bold mb-0">{{ number_format((int) ($crmSummary['total_broadcasts'] ?? 0)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Quick links + Insights --}}
        <div class="col-span-12 xl:col-span-7">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ __('Quick actions') }}</h4>
                    <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Jump to the modules you use most.') }}</p>
                </div>
                <div class="box-body">
                    <div class="grid grid-cols-12 gap-3">
                        @foreach ($quickLinks as $q)
                            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                                <a href="{{ route($q['route']) }}"
                                   class="p-4 border border-defaultborder/10 rounded-md flex items-center gap-3 hover:bg-gray-50/60 transition-colors">
                                    <span class="w-10 h-10 rounded-md bg-primary/10 text-primary flex items-center justify-center">
                                        <i class="{{ $q['icon'] ?? 'ri-arrow-right-line' }} text-lg"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="mb-0 font-semibold truncate">{{ $q['label'] ?? '' }}</p>
                                        <p class="mb-0 text-[10px] text-textmuted">{{ __('Open') }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-5">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ __('Insights') }}</h4>
                    <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Guidance based on how teams win in this workflow.') }}</p>
                </div>
                <div class="box-body">
                    <ul class="space-y-3">
                        @foreach ($insights as $ins)
                            <li class="flex gap-2 text-sm">
                                <i class="ri-checkbox-circle-line text-success mt-0.5"></i>
                                <span class="text-defaulttextcolor">{{ $ins }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Recent activity table --}}
        <div class="col-span-12">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ $tableTitle }}</h4>
                    <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Latest items for your role dashboard.') }}</p>
                </div>
                <div class="box-body !p-0">
                    @if (empty($recentRows))
                        <div class="p-12 text-center text-textmuted">{{ $tableEmpty }}</div>
                    @else
                        <div class="table-responsive p-4">
                            <table class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th class="!py-3 !px-4">{{ __('Name') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Detail') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Meta') }}</th>
                                        <th class="!py-3 !px-4 text-end">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentRows as $row)
                                        @php
                                            $variant = $row['badge_variant'] ?? 'primary';
                                            $cls = match($variant) {
                                                'success' => 'bg-success/10 text-success border-success/20',
                                                'warning' => 'bg-warning/10 text-warning border-warning/20',
                                                'danger' => 'bg-danger/10 text-danger border-danger/20',
                                                'secondary' => 'bg-gray-100 text-gray-600 border-gray-200',
                                                default => 'bg-primary/10 text-primary border-primary/20',
                                            };
                                        @endphp
                                        <tr class="border-b last:border-0 hover:bg-gray-50/20 transition-colors h-14">
                                            <td class="!px-4 font-semibold">{{ $row['name'] ?? '—' }}</td>
                                            <td class="!px-4 text-textmuted text-[12px]">{{ $row['detail'] ?? '—' }}</td>
                                            <td class="!px-4 text-textmuted text-[12px]">{{ $row['meta'] ?? '—' }}</td>
                                            <td class="!px-4 text-end">
                                                <span class="badge rounded-full px-3 py-1 text-[10px] border {{ $cls }}">{{ $row['badge'] ?? '—' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Super admin: subscription monitor --}}
        @if ($dashboardRole === 'super_admin' && isset($subscriptionDashboard))
            <div class="col-span-12">
                <div class="box shadow-none border border-defaultborder/10">
                    <div class="box-header !border-b !border-defaultborder/10 flex flex-wrap justify-between items-center gap-3">
                        <div>
                            <h4 class="box-title font-semibold">{{ __('Subscription monitor') }}</h4>
                            <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Expiring soon (next 7 days).') }}</p>
                        </div>
                        <a href="{{ route('admin.subscriptions.index', ['filter' => 'expiring']) }}" class="ti-btn ti-btn-light font-medium !mb-0">{{ __('View all') }}</a>
                    </div>
                    <div class="box-body !p-0">
                        <div class="table-responsive p-4">
                            <table class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th class="!py-3 !px-4">{{ __('Organization') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Plan') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Status') }}</th>
                                        <th class="!py-3 !px-4 text-end">{{ __('Days left') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (($subscriptionDashboard['expiringSoon'] ?? []) as $er)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/20 transition-colors h-14">
                                            <td class="!px-4 font-semibold">{{ $er['organization_name'] }}</td>
                                            <td class="!px-4 text-textmuted text-[12px]">{{ $er['plan_name'] }}</td>
                                            <td class="!px-4">
                                                @if (($er['status_key'] ?? '') === 'active')
                                                    <span class="badge bg-success/10 text-success border border-success/20 rounded-full px-3 py-1 text-[10px]">{{ __('Paid') }}</span>
                                                @else
                                                    <span class="badge bg-info/10 text-info border border-info/20 rounded-full px-3 py-1 text-[10px]">{{ __('Trial') }}</span>
                                                @endif
                                            </td>
                                            <td class="!px-4 text-end">
                                                <span class="badge bg-warning/10 text-warning border border-warning/20 rounded-full px-3 py-1 text-[10px]">{{ $er['days_display'] }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-textmuted py-10">{{ __('No expiring subscriptions right now.') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
