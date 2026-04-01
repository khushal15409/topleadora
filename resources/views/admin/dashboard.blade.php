@extends('layouts.admin')

@section('title', 'Dashboard')

@push('vendor-css')
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@section('content')
    @php
        $toneBorder = [
            'primary' => 'border-primary border-opacity-50',
            'success' => 'border-success border-opacity-50',
            'info' => 'border-info border-opacity-50',
            'warning' => 'border-warning border-opacity-50',
        ];
        $toneIcon = [
            'primary' => 'text-primary',
            'success' => 'text-success',
            'info' => 'text-info',
            'warning' => 'text-warning',
        ];
    @endphp

    @if (($freeAccessMode ?? false) && auth()->user()?->hasRole(\App\Support\Roles::ORGANIZATION))
        <div class="alert alert-info alert-dismissible mb-4" role="alert">
            <strong>{{ __('Free access mode is enabled.') }}</strong>
            {{ __('No subscription is required and all CRM features are unlocked.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="wp-crm-dashboard-hero card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 p-lg-5 position-relative">
            <div class="row align-items-center gy-4">
                <div class="col-lg-8">
                    <span class="badge bg-label-primary mb-2">{{ $hero['badge'] }}</span>
                    <h4 class="mb-2 text-heading">{{ $hero['title'] }}</h4>
                    <p class="mb-0 text-body-secondary col-lg-11 lh-lg">{{ $hero['subtitle'] }}</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        @foreach ($quickLinks as $link)
                            <a href="{{ route($link['route']) }}" class="btn btn-sm btn-primary">
                                <i class="icon-base {{ $link['icon'] }} me-1"></i>{{ $link['label'] }}
                            </a>
                        @endforeach
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-label-secondary">
                            <i class="icon-base ri ri-user-settings-line me-1"></i>{{ __('Profile') }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div
                        class="rounded-4 p-4 bg-lighter wp-crm-dashboard-hero-panel d-inline-block text-start w-100"
                        style="max-width: 22rem;"
                    >
                        <div class="small text-uppercase text-muted fw-semibold mb-2">{{ __('Today’s focus') }}</div>
                        <ul class="list-unstyled mb-0 small text-body-secondary lh-base">
                            @foreach ($insights as $line)
                                <li class="d-flex gap-2 mb-2">
                                    <i class="icon-base ri ri-checkbox-circle-fill text-success flex-shrink-0 mt-1"></i>
                                    <span>{{ $line }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @isset($crmSummary)
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 wp-crm-crm-widget bg-primary bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-uppercase text-muted fw-semibold">{{ __('Today’s follow-ups') }}</span>
                            <i class="icon-base ri ri-calendar-check-line text-primary"></i>
                        </div>
                        <h3 class="fw-semibold mt-2 mb-0">{{ number_format($crmSummary['today_followups']) }}</h3>
                        <a href="{{ route('dashboard.followups.index', ['tab' => 'today']) }}" class="small stretched-link text-decoration-none">{{ __('Open follow-ups') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 wp-crm-crm-widget bg-success bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-uppercase text-muted fw-semibold">{{ __('Total leads') }}</span>
                            <i class="icon-base ri ri-user-search-line text-success"></i>
                        </div>
                        <h3 class="fw-semibold mt-2 mb-0">{{ number_format($crmSummary['total_leads']) }}</h3>
                        <a href="{{ route('dashboard.leads.index') }}" class="small stretched-link text-decoration-none">{{ __('View leads') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 wp-crm-crm-widget bg-warning bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-uppercase text-muted fw-semibold">{{ __('Closed deals') }}</span>
                            <i class="icon-base ri ri-checkbox-circle-line text-warning"></i>
                        </div>
                        <h3 class="fw-semibold mt-2 mb-0">{{ number_format($crmSummary['closed_deals']) }}</h3>
                        <a href="{{ route('dashboard.leads.index', ['status' => \App\Models\Lead::STATUS_CLOSED]) }}" class="small stretched-link text-decoration-none">{{ __('See closed') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <div class="row g-4 mb-2">
        @foreach ($stats as $stat)
            @php
                $b = $stat['tone'] ?? 'primary';
                $borderClass = $toneBorder[$b] ?? $toneBorder['primary'];
                $iconClass = $toneIcon[$b] ?? $toneIcon['primary'];
            @endphp
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 wp-crm-stat-card border-start border-4 {{ $borderClass }} shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="fw-semibold text-heading">{{ $stat['label'] }}</span>
                            <span class="rounded-3 wp-crm-stat-icon d-inline-flex align-items-center justify-content-center {{ $iconClass }}">
                                <i class="icon-base {{ $stat['icon'] }} icon-md"></i>
                            </span>
                        </div>
                        <h3 class="mb-1 fw-semibold">{{ $stat['value'] }}</h3>
                        <small class="text-body-secondary d-block">{{ $stat['hint'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @isset($subscriptionDashboard)
        @php
            $sd = $subscriptionDashboard['counts'];
            $subToneBorder = [
                'expired' => 'border-danger border-opacity-50',
                'expiring' => 'border-warning border-opacity-50',
                'trial' => 'border-info border-opacity-50',
                'manage' => 'border-primary border-opacity-50',
            ];
        @endphp
        <h5 class="mb-3 mt-2 text-heading">{{ __('Subscription monitoring') }}</h5>
        <div class="row g-4 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 wp-crm-stat-card border-start border-4 {{ $subToneBorder['expired'] }} shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-semibold text-heading">{{ __('Total expired') }}</span>
                            <span class="rounded-3 wp-crm-stat-icon d-inline-flex align-items-center justify-content-center text-danger">
                                <i class="icon-base ri ri-error-warning-line icon-md"></i>
                            </span>
                        </div>
                        <h3 class="mb-0 fw-semibold">{{ number_format($sd['expired']) }}</h3>
                        <small class="text-body-secondary d-block">{{ __('Lapsed trials or billing') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 wp-crm-stat-card border-start border-4 {{ $subToneBorder['expiring'] }} shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-semibold text-heading">{{ __('Expiring ≤ 7 days') }}</span>
                            <span class="rounded-3 wp-crm-stat-icon d-inline-flex align-items-center justify-content-center text-warning">
                                <i class="icon-base ri ri-timer-flash-line icon-md"></i>
                            </span>
                        </div>
                        <h3 class="mb-0 fw-semibold">{{ number_format($sd['expiring_7d']) }}</h3>
                        <small class="text-body-secondary d-block">{{ __('Renewal outreach candidates') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 wp-crm-stat-card border-start border-4 {{ $subToneBorder['trial'] }} shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-semibold text-heading">{{ __('Trial workspaces') }}</span>
                            <span class="rounded-3 wp-crm-stat-icon d-inline-flex align-items-center justify-content-center text-info">
                                <i class="icon-base ri ri-gift-line icon-md"></i>
                            </span>
                        </div>
                        <h3 class="mb-0 fw-semibold">{{ number_format($sd['trial']) }}</h3>
                        <small class="text-body-secondary d-block">{{ __('Not on a paid cycle yet') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="card h-100 text-decoration-none wp-crm-stat-card border-start border-4 {{ $subToneBorder['manage'] }} shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <span class="fw-semibold text-heading mb-2">{{ __('Manage subscriptions') }}</span>
                        <span class="small text-primary">{{ __('Open table & filters') }} →</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border border-warning border-opacity-35">
            <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="mb-0">{{ __('Expiring soon') }}</h5>
                    <small class="text-body-secondary">{{ __('Organizations with an end date in the next 7 days') }}</small>
                </div>
                <a href="{{ route('admin.subscriptions.index', ['filter' => 'expiring']) }}" class="btn btn-sm btn-label-warning">{{ __('View filter') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="border-top">
                        <tr>
                            <th>{{ __('Organization') }}</th>
                            <th>{{ __('Plan') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Days left') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptionDashboard['expiringSoon'] as $er)
                            <tr>
                                <td class="fw-medium">{{ $er['organization_name'] }}</td>
                                <td class="text-body-secondary small">{{ $er['plan_name'] }}</td>
                                <td>
                                    @if ($er['status_key'] === 'active')
                                        <span class="badge rounded-pill bg-label-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-label-info">{{ __('Trial') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-warning">{{ $er['days_display'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-body-secondary py-4">{{ __('No subscriptions ending in the next 7 days.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endisset

    <div class="row g-4 mb-2">
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header border-bottom pb-3 d-flex flex-column">
                    <h5 class="mb-0">{{ $chartPayload['weeklyCaption'] }}</h5>
                    <small class="text-body-secondary">{{ __('Pulled from your live database') }}</small>
                </div>
                <div class="card-body pt-2">
                    <div id="weeklyOverviewChart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header border-bottom pb-3">
                    <h5 class="mb-0">{{ $chartPayload['lineCaption'] }}</h5>
                    <small class="text-body-secondary">{{ __('Smoothed trend for the week') }}</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-center pt-4">
                    <div id="totalProfitLineChart" class="mb-2"></div>
                    <p class="text-center small text-muted mb-0">{{ __('Latest point highlights current momentum') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header border-bottom pb-3">
                    <h5 class="mb-0">{{ $chartPayload['columnCaption'] }}</h5>
                    <small class="text-body-secondary">{{ __('Compact view of the last five periods') }}</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-center pt-4">
                    <div id="sessionsColumnChart" class="mb-2"></div>
                    <p class="text-center small text-muted mb-0">{{ __('Taller bars signal busier days') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm overflow-hidden">
        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0">{{ $tableTitle }}</h5>
                <small class="text-body-secondary">{{ __('Latest entries — refined for your role') }}</small>
            </div>
            @if ($dashboardRole === 'super_admin')
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-label-primary">{{ __('Open inbox') }}</a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 wp-crm-dashboard-table">
                <thead class="border-top">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('When') }}</th>
                        <th class="text-end">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentRows as $row)
                        <tr>
                            <td class="fw-medium">{{ $row['name'] }}</td>
                            <td class="text-body-secondary text-truncate" style="max-width: 14rem;">{{ $row['detail'] }}</td>
                            <td class="text-body-secondary small">{{ $row['meta'] }}</td>
                            <td class="text-end">
                                <span class="badge rounded-pill bg-label-{{ $row['badge_variant'] }}">
                                    {{ $row['badge'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-body-secondary py-5">{{ $tableEmpty }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="{{ asset('materio/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endpush

@push('page-js')
    <script>
        window.__WP_CRM_DASHBOARD = @json($chartPayload);
    </script>
    <script src="{{ asset('materio/assets/js/dashboard-wp-crm.js') }}"></script>
@endpush
