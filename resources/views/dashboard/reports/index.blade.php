@extends('layouts.admin')

@section('title', __('Reports'))

@push('vendor-css')
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Reports') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Leads and pipeline for your workspace.') }}</p>
        </div>
        <div class="btn-group" role="group">
            @foreach (['today' => __('Today'), 'week' => __('This week'), 'month' => __('This month')] as $key => $label)
                <a
                    href="{{ route('dashboard.reports.index', ['period' => $key]) }}"
                    class="btn btn-sm {{ $period === $key ? 'btn-primary' : 'btn-label-secondary' }}"
                >{{ $label }}</a>
            @endforeach
        </div>
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
           ['label' => __('Total leads'), 'value' => number_format($summary['total_leads']), 'hint' => __('Created in period'), 'icon' => 'ri-user-search-line', 'tone' => 'primary'],
           ['label' => __('New leads'), 'value' => number_format($summary['new_leads']), 'hint' => __('Status: New'), 'icon' => 'ri-sparkling-line', 'tone' => 'success'],
           ['label' => __('Follow-ups pending'), 'value' => number_format($summary['followups_pending']), 'hint' => __('Due in period'), 'icon' => 'ri-calendar-todo-line', 'tone' => 'info'],
           ['label' => __('Closed deals'), 'value' => number_format($summary['closed_deals']), 'hint' => __('Updated in period'), 'icon' => 'ri-checkbox-circle-line', 'tone' => 'warning'],
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
                <div class="card h-100 border-0 shadow-sm rounded-4 border-start border-4 {{ $borderClass }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold text-heading">{{ $stat['label'] }}</span>
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center p-2 bg-lighter {{ $iconClass }}">
                                <i class="icon-base {{ $stat['icon'] }} icon-md"></i>
                            </span>
                        </div>
                        <h3 class="mb-1 fw-semibold">{{ $stat['value'] }}</h3>
                        <small class="text-body-secondary">{{ $stat['hint'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <h5 class="mb-0">{{ __('Leads growth') }}</h5>
                    <small class="text-body-secondary">{{ __('New leads per day') }}</small>
                </div>
                <div class="card-body pt-2" style="min-height: 300px;">
                    <div id="reportLeadsLineChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <h5 class="mb-0">{{ __('Pipeline in period') }}</h5>
                    <small class="text-body-secondary">{{ __('New · Interested · Follow-up · Closed') }}</small>
                </div>
                <div class="card-body pt-2" style="min-height: 300px;">
                    <div id="reportPipelineBarChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="mb-0">{{ __('Recent leads') }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentLeads as $l)
                        <tr>
                            <td class="fw-medium">{{ $l->name }}</td>
                            <td>{{ $l->phone ?? '—' }}</td>
                            <td><span class="badge bg-label-primary rounded-pill">{{ $l->statusLabel() }}</span></td>
                            <td class="small text-body-secondary">{{ $l->created_at?->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-body-secondary py-4">{{ __('No leads in this period.') }}</td>
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
    @php
        $reportChartPayload = [
            'growthLabels' => $growthLabels,
            'growthCounts' => $growthCounts,
            'pipelineLabels' => $pipelineLabels,
            'pipelineCounts' => $pipelineCounts,
        ];
    @endphp
    <script>
        window.__WP_CRM_REPORTS = @json($reportChartPayload);
    </script>
    <script>
        (function () {
            const payload = window.__WP_CRM_REPORTS;
            if (!payload || typeof ApexCharts === 'undefined' || typeof config === 'undefined') return;
            const labelColor = config.colors.textMuted;
            const borderColor = config.colors.borderColor;
            const fontFamily = config.fontFamily;

            const lineEl = document.querySelector('#reportLeadsLineChart');
            if (lineEl && payload.growthLabels.length) {
                new ApexCharts(lineEl, {
                    chart: { type: 'area', height: 280, toolbar: { show: false }, zoom: { enabled: false }, fontFamily },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 0.5, opacityFrom: 0.35, opacityTo: 0.05 },
                    },
                    colors: [config.colors.primary],
                    series: [{ name: '{{ __('Leads') }}', data: payload.growthCounts }],
                    xaxis: {
                        categories: payload.growthLabels,
                        labels: { style: { colors: labelColor, fontFamily, fontSize: '11px' } },
                    },
                    yaxis: {
                        min: 0,
                        labels: { style: { colors: labelColor, fontFamily } },
                    },
                    dataLabels: { enabled: false },
                    grid: { borderColor, strokeDashArray: 4 },
                    tooltip: { theme: 'light' },
                }).render();
            }

            const barEl = document.querySelector('#reportPipelineBarChart');
            if (barEl) {
                new ApexCharts(barEl, {
                    chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily },
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
                    colors: [config.colors.primary],
                    series: [{ name: '{{ __('Leads') }}', data: payload.pipelineCounts }],
                    xaxis: {
                        categories: payload.pipelineLabels,
                        labels: { style: { colors: labelColor, fontFamily, fontSize: '11px' } },
                    },
                    yaxis: {
                        min: 0,
                        labels: { style: { colors: labelColor, fontFamily } },
                    },
                    dataLabels: { enabled: false },
                    grid: { borderColor, strokeDashArray: 4 },
                    tooltip: { theme: 'light' },
                }).render();
            }
        })();
    </script>
@endpush
