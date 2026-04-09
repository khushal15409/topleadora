@extends('layouts.admin')

@section('title', __('Analytics Reports'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Analytics Reports') }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Dashboard') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Reports') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <div class="inline-flex rounded-md shadow-sm" role="group">
                @foreach (['today' => __('Today'), 'week' => __('Week'), 'month' => __('Month')] as $key => $label)
                    <a href="{{ route('dashboard.reports.index', ['period' => $key]) }}"
                        class="ti-btn !mb-0 px-4 py-2 text-sm font-medium {{ $period === $key ? 'ti-btn-primary z-10' : 'ti-btn-light border-defaultborder group-first:rounded-s-md group-last:rounded-e-md' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    @php
        $cards = [
            ['label' => __('Total Leads'), 'value' => number_format($summary['total_leads']), 'hint' => __('Created in period'), 'icon' => 'ri-user-search-line', 'color' => 'primary'],
            ['label' => __('New Leads'), 'value' => number_format($summary['new_leads']), 'hint' => __('Status: New'), 'icon' => 'ri-sparkling-line', 'color' => 'success'],
            ['label' => __('Follow-ups'), 'value' => number_format($summary['followups_pending']), 'hint' => __('Due in period'), 'icon' => 'ri-calendar-todo-line', 'color' => 'info'],
            ['label' => __('Closed Deals'), 'value' => number_format($summary['closed_deals']), 'hint' => __('Won / Converted'), 'icon' => 'ri-checkbox-circle-line', 'color' => 'warning'],
        ];
    @endphp

    <div class="grid grid-cols-12 gap-x-6">
        @foreach ($cards as $stat)
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="box">
                    <div class="box-body !p-5">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="text-textmuted text-xs font-semibold mb-1 uppercase tracking-wider">
                                    {{ $stat['label'] }}</p>
                                <h4 class="text-2xl font-bold mb-0">{{ $stat['value'] }}</h4>
                            </div>
                            <div class="avatar avatar-lg bg-{{ $stat['color'] }}/10 text-{{ $stat['color'] }} rounded-md">
                                <i class="{{ $stat['icon'] }} text-2xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-xs text-textmuted">
                            <span class="text-{{ $stat['color'] }} me-1 font-medium"><i class="ri-arrow-right-line"></i></span>
                            {{ $stat['hint'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Chart Section -->
        <div class="col-span-12 lg:col-span-7">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Leads Growth') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('New leads acquired per day in this period.') }}</p>
                </div>
                <div class="box-body !pt-0">
                    <div id="reportLeadsLineChart" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-5">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Pipeline Distribution') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Lead distribution across pipeline stages.') }}</p>
                </div>
                <div class="box-body !pt-0">
                    <div id="reportPipelineBarChart" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Recent Leads Table -->
        <div class="col-span-12">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Recent Lead Activity') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('The most recent leads captured in this period.') }}</p>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-50 border-y dark:bg-black/10">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Lead Name') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Phone Number') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Status') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Date Captured') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentLeads as $l)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4 font-medium">{{ $l->name }}</td>
                                        <td class="!px-4 text-sm text-textmuted">{{ $l->phone ?? '—' }}</td>
                                        <td class="!px-4">
                                            <span
                                                class="badge bg-primary/10 text-primary rounded-full px-3">{{ $l->statusLabel() }}</span>
                                        </td>
                                        <td class="!px-4 text-sm text-textmuted">
                                            {{ $l->created_at?->format('M j, Y') }}
                                        </td>
                                        <td class="!px-4 text-end">
                                            <a href="{{ route('dashboard.leads.edit', $l) }}"
                                                class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0 p-2">
                                                <i class="ri-eye-line text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-textmuted py-12">
                                            {{ __('No recent leads found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
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
            if (!payload || typeof ApexCharts === 'undefined') return;

            const primaryColor = '#845adf';
            const textMuted = '#8c9097';
            const borderColor = '#f3f3f3';

            // Area Chart
            const areaOptions = {
                chart: { type: 'area', height: 320, toolbar: { show: false }, zoom: { enabled: false } },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] }
                },
                colors: [primaryColor],
                series: [{ name: '{{ __('New Leads') }}', data: payload.growthCounts }],
                xaxis: {
                    categories: payload.growthLabels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: textMuted, fontSize: '11px', fontWeight: 500 } }
                },
                yaxis: {
                    labels: { style: { colors: textMuted, fontSize: '11px', fontWeight: 500 } }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: borderColor, strokeDashArray: 4 },
                tooltip: { x: { show: true }, theme: 'light' }
            };
            new ApexCharts(document.querySelector('#reportLeadsLineChart'), areaOptions).render();

            // Bar Chart
            const barOptions = {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '40%', distributed: true } },
                colors: [primaryColor, '#23b7e5', '#f5b849', '#49b6f5', '#26bf94'],
                series: [{ name: '{{ __('Count') }}', data: payload.pipelineCounts }],
                xaxis: {
                    categories: payload.pipelineLabels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: textMuted, fontSize: '11px', fontWeight: 500 } }
                },
                yaxis: {
                    labels: { style: { colors: textMuted, fontSize: '11px', fontWeight: 500 } }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: borderColor, strokeDashArray: 4 },
                legend: { show: false }
            };
            new ApexCharts(document.querySelector('#reportPipelineBarChart'), barOptions).render();
        })();
    </script>
@endpush