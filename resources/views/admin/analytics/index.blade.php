@extends('layouts.admin')

@section('title', __('Analytics'))

@push('vendor-css')
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Analytics') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Platform KPIs across all organizations.') }}</p>
        </div>
        <form method="get" action="{{ route('admin.analytics.index') }}" class="d-flex gap-2 align-items-center">
            <label class="text-body-secondary small mb-0" for="year">{{ __('Year') }}</label>
            <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()">
                @for ($y = now()->year; $y >= now()->year - 4; $y--)
                    <option value="{{ $y }}" @selected((int) $year === (int) $y)>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

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
        $cards = [
            ['label' => __('Total organizations'), 'value' => number_format($totalOrganizations), 'hint' => __('All tenants'), 'icon' => 'ri-building-4-line', 'tone' => 'primary'],
            ['label' => __('Active subscriptions'), 'value' => number_format($activeSubscriptions), 'hint' => __('Valid paid periods'), 'icon' => 'ri-bank-card-line', 'tone' => 'success'],
            ['label' => __('Total revenue'), 'value' => number_format($totalRevenue, 2), 'hint' => __('Successful payments'), 'icon' => 'ri-line-chart-line', 'tone' => 'info'],
            ['label' => __('Total leads'), 'value' => number_format($totalLeads), 'hint' => __('Across all orgs'), 'icon' => 'ri-user-search-line', 'tone' => 'warning'],
        ];
    @endphp

    <div class="row g-4 mb-4">
        @foreach ($cards as $stat)
            @php
                $b = $stat['tone'] ?? 'primary';
                $borderClass = $toneBorder[$b] ?? $toneBorder['primary'];
                $iconClass = $toneIcon[$b] ?? $toneIcon['primary'];
            @endphp
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 wp-crm-stat-card border-start border-4 {{ $borderClass }} shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
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

    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header border-bottom pb-3">
                    <h5 class="mb-0">{{ __('Organization growth') }}</h5>
                    <small class="text-body-secondary">{{ __('New organizations per month') }}</small>
                </div>
                <div class="card-body pt-4">
                    <div id="analyticsOrgChart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header border-bottom pb-3">
                    <h5 class="mb-0">{{ __('Revenue growth') }}</h5>
                    <small class="text-body-secondary">{{ __('Successful payments per month') }}</small>
                </div>
                <div class="card-body pt-4">
                    <div id="analyticsRevenueChart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header border-bottom pb-3">
                    <h5 class="mb-0">{{ __('Leads growth') }}</h5>
                    <small class="text-body-secondary">{{ __('New leads per month') }}</small>
                </div>
                <div class="card-body pt-4">
                    <div id="analyticsLeadsChart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="{{ asset('materio/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endpush

@push('page-js')
    @php
        $analyticsPayload = $chartPayload;
    @endphp
    <script>
        window.__WP_CRM_ADMIN_ANALYTICS = @json($analyticsPayload);
    </script>
    <script>
        (function () {
            const payload = window.__WP_CRM_ADMIN_ANALYTICS;
            if (!payload || typeof ApexCharts === 'undefined' || typeof config === 'undefined') return;
            const labelColor = config.colors.textMuted;
            const borderColor = config.colors.borderColor;
            const fontFamily = config.fontFamily;

            function renderBar(el, series, color) {
                if (!el) return;
                new ApexCharts(el, {
                    chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily },
                    plotOptions: { bar: { borderRadius: 8, columnWidth: '50%' } },
                    colors: [color],
                    series: [{ name: 'Total', data: series }],
                    xaxis: { categories: payload.labels, labels: { style: { colors: labelColor, fontFamily, fontSize: '11px' } } },
                    yaxis: { min: 0, labels: { style: { colors: labelColor, fontFamily } } },
                    grid: { borderColor, strokeDashArray: 4 },
                    dataLabels: { enabled: false },
                    tooltip: { theme: 'light' },
                }).render();
            }

            renderBar(document.querySelector('#analyticsOrgChart'), payload.orgs, config.colors.primary);
            renderBar(document.querySelector('#analyticsLeadsChart'), payload.leads, config.colors.info);
            renderBar(document.querySelector('#analyticsRevenueChart'), payload.revenue.map(v => Math.round(v)), config.colors.success);
        })();
    </script>
@endpush

