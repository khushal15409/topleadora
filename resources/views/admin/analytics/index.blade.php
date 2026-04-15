@extends('layouts.admin')

@section('title', __('Analytics'))

@push('vendor-css')
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Analytics Overview') }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Admin') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Analytics') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <form method="get" action="{{ route('admin.analytics.index') }}" class="flex items-center gap-2">
                <label class="text-textmuted text-[11px] font-bold uppercase"
                    for="analytics-year">{{ __('Snapshot Year') }}</label>
                <select name="year" id="analytics-year" class="ti-form-select !py-1 !px-2 !text-[12px] min-w-[100px]"
                    onchange="this.form.submit()">
                    @for ($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" @selected((int) $year === (int) $y)>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon ms-2 !mb-0"
                onclick="window.location.reload()" title="{{ __('Refresh') }}">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    @php
        $cards = [
            ['label' => __('ORGANIZATIONS'), 'value' => number_format($totalOrganizations), 'hint' => __('All platform tenants'), 'icon' => 'ri-building-4-line', 'color' => 'primary'],
            ['label' => __('SUBSCRIPTIONS'), 'value' => number_format($activeSubscriptions), 'hint' => __('Active paid plans'), 'icon' => 'ri-bank-card-line', 'color' => 'success'],
            ['label' => __('TOTAL REVENUE'), 'value' => '₹' . number_format($totalRevenue, 0), 'hint' => __('Life-time revenue'), 'icon' => 'ri-line-chart-line', 'color' => 'info'],
            ['label' => __('TOTAL LEADS'), 'value' => number_format($totalLeads), 'hint' => __('Global leads database'), 'icon' => 'ri-user-search-line', 'color' => 'warning'],
        ];
    @endphp

    <div class="grid grid-cols-12 gap-x-6 mb-6">
        @foreach ($cards as $stat)
            <div class="col-span-12 md:col-span-6 xxl:col-span-3">
                <div class="box shadow-none border border-defaultborder/10">
                    <div class="box-body !p-4">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <p class="text-textmuted text-[11px] font-bold uppercase tracking-widest mb-1">
                                    {{ $stat['label'] }}</p>
                                <h4 class="text-[1.25rem] font-bold mb-0 text-defaulttextcolor">{{ $stat['value'] }}</h4>
                                <p class="text-textmuted text-[10px] mt-1 mb-0">{{ $stat['hint'] }}</p>
                            </div>
                            <div
                                class="ti-avatar ti-avatar-md bg-{{ $stat['color'] }}/10 text-{{ $stat['color'] }} rounded-md p-2">
                                <i class="{{ $stat['icon'] }} text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-12 gap-x-6 mb-6">
        <div class="col-span-12 lg:col-span-4">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title">{{ __('Organization Growth') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('New signups by month') }}</p>
                </div>
                <div class="box-body">
                    <div id="analyticsOrgChart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title">{{ __('Revenue Performance') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Successful payments volume') }}</p>
                </div>
                <div class="box-body">
                    <div id="analyticsRevenueChart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title">{{ __('Leads Generation') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Leads captured across all orgs') }}</p>
                </div>
                <div class="box-body">
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
    <script>
        (function () {
            const payload = @json($chartPayload);
            if (!payload || typeof ApexCharts === 'undefined') return;

            function renderBar(el, series, color) {
                const domNode = document.querySelector(el);
                if (!domNode) return;
                new ApexCharts(domNode, {
                    chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
                    plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                    colors: [color],
                    series: [{ name: 'Count', data: series }],
                    xaxis: {
                        categories: payload.labels,
                        labels: { style: { colors: '#8c9097', fontSize: '11px' } },
                        axisBorder: { show: false }, axisTicks: { show: false }
                    },
                    yaxis: { min: 0, labels: { style: { colors: '#8c9097' } } },
                    grid: { borderColor: '#f1f1f1', strokeDashArray: 2 },
                    dataLabels: { enabled: false },
                    tooltip: { theme: 'light' },
                }).render();
            }

            renderBar('#analyticsOrgChart', payload.orgs, '#5c67f7'); // Primary
            renderBar('#analyticsRevenueChart', payload.revenue.map(v => Math.round(v)), '#26bf94'); // Success
            renderBar('#analyticsLeadsChart', payload.leads, '#eab308'); // Warning
        })();
    </script>
@endpush