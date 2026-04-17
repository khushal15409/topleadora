@extends('layouts.admin')

@section('title', __('Revenue Analytics'))

@push('vendor-css')
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@php
    $money = static function (float $amount): string {
        return money_local($amount, 0);
    };

    $exportQuery = array_merge(request()->only(['range', 'date_from', 'date_to', 'payment_status', 'q']), ['export' => 'csv']);
@endphp

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Revenue Analytics') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Admin') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Revenue') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('admin.revenue.index', $exportQuery) }}" class="ti-btn ti-btn-light font-medium !mb-0 shadow-sm border border-defaultborder/10">
                <i class="ri-file-excel-2-line me-1"></i>{{ __('Export CSV') }}
            </a>
            <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon ms-2 !mb-0" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    {{-- Filter Section --}}
    <div class="box shadow-none border border-defaultborder/10 mb-6">
        <div class="box-body">
            <form method="get" action="{{ route('admin.revenue.index') }}" class="grid grid-cols-12 gap-x-6 gap-y-4 items-end">
                <div class="col-span-12 lg:col-span-6">
                    <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider mb-2 block">{{ __('Quick Range') }}</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['today' => __('Today'), 'week' => __('Week'), 'month' => __('Month'), 'year' => __('Year'), 'custom' => __('Custom')] as $key => $lbl)
                            <div class="ti-form-radio-group">
                                <input
                                    type="radio"
                                    class="ti-form-radio hidden peer"
                                    name="range"
                                    id="revenue-range-{{ $key }}"
                                    value="{{ $key }}"
                                    @checked((string) $range === $key)
                                    onchange="this.form.submit()"
                                >
                                <label class="ti-btn ti-btn-soft-secondary !py-1 !px-3 !text-[12px] peer-checked:!bg-primary peer-checked:!text-white cursor-pointer" for="revenue-range-{{ $key }}">{{ $lbl }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ((string) $range === 'custom')
                    <div class="col-span-12 md:col-span-3 lg:col-span-2">
                        <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('From') }}</label>
                        <input type="date" name="date_from" class="ti-form-input !py-2" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-span-12 md:col-span-3 lg:col-span-2">
                        <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('To') }}</label>
                        <input type="date" name="date_to" class="ti-form-input !py-2" value="{{ $dateTo }}">
                    </div>
                @endif

                <div class="col-span-12 md:col-span-4 lg:col-span-2">
                    <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('Status') }}</label>
                    <select name="payment_status" class="ti-form-select !py-2" onchange="this.form.submit()">
                        <option value="{{ \App\Models\Payment::STATUS_SUCCESS }}" @selected((string) $paymentStatus === \App\Models\Payment::STATUS_SUCCESS)>{{ __('Success') }}</option>
                        <option value="{{ \App\Models\Payment::STATUS_FAILED }}" @selected((string) $paymentStatus === \App\Models\Payment::STATUS_FAILED)>{{ __('Failed') }}</option>
                        <option value="{{ \App\Models\Payment::STATUS_PENDING }}" @selected((string) $paymentStatus === \App\Models\Payment::STATUS_PENDING)>{{ __('Pending') }}</option>
                        <option value="all" @selected((string) $paymentStatus === 'all')>{{ __('All statuses') }}</option>
                    </select>
                </div>

                <div class="col-span-12 md:col-span-8 lg:col-span-4 flex gap-2">
                    <div class="flex-1">
                        <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('Search') }}</label>
                        <input type="search" name="q" class="ti-form-input !py-2" value="{{ $search }}" placeholder="{{ __('Org name…') }}">
                    </div>
                    <button type="submit" class="ti-btn ti-btn-primary-full !mb-0 self-end">{{ __('Search') }}</button>
                </div>
            </form>
            <div class="mt-3 flex items-center gap-2 text-textmuted text-[11px]">
                <i class="ri-information-line text-[14px]"></i>
                {{ __('Viewing: :from — :to', ['from' => $rangeStart->format('M j, Y'), 'to' => $rangeEnd->format('M j, Y')]) }}
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-12 gap-x-6 mb-6">
        <div class="col-span-12 md:col-span-6 xxl:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-textmuted text-[12px] font-medium mb-1 tracking-tight">{{ __('TOTAL REVENUE') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-defaulttextcolor">{{ $money($summary['total_revenue']) }}</h4>
                            <p class="text-textmuted text-[10px] mt-1 mb-0">{{ __('In selected period') }}</p>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-primary/10 text-primary rounded-md p-2 shadow-none">
                            <i class="ri-money-dollar-circle-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-6 xxl:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-textmuted text-[12px] font-medium mb-1 tracking-tight">{{ __('THIS MONTH') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-defaulttextcolor">{{ $money($summary['this_month_revenue']) }}</h4>
                            <p class="text-textmuted text-[10px] mt-1 mb-0">{{ __('Calendar month revenue') }}</p>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-info/10 text-info rounded-md p-2 shadow-none">
                            <i class="ri-calendar-event-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-6 xxl:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-textmuted text-[12px] font-medium mb-1 tracking-tight">{{ __('LAST MONTH') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-defaulttextcolor">{{ $money($summary['last_month_revenue']) }}</h4>
                            <p class="text-textmuted text-[10px] mt-1 mb-0">{{ __('Full calendar month') }}</p>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-warning/10 text-warning rounded-md p-2 shadow-none">
                            <i class="ri-history-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-6 xxl:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-textmuted text-[12px] font-medium mb-1 tracking-tight">{{ __('TRANSACTIONS') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-defaulttextcolor">{{ number_format($summary['transaction_count']) }}</h4>
                            <p class="text-textmuted text-[10px] mt-1 mb-0">{{ __('Total payment count') }}</p>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-success/10 text-success rounded-md p-2 shadow-none">
                            <i class="ri-bank-card-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-12 gap-x-6 mb-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title">{{ __('Revenue Trend') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Calendar year :year (Jan–Dec), status filter applied', ['year' => $rangeEnd->year]) }}</p>
                </div>
                <div class="box-body">
                    <div id="revenueYearChart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 xl:col-span-4">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title">{{ __('Revenue by Plan') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Selected date range data') }}</p>
                </div>
                <div class="box-body flex items-center justify-center">
                    @if (count($byPlan) > 0 && collect($chartPayload['planSeries'])->sum() > 0)
                        <div id="revenuePlanChart" class="w-full"></div>
                    @else
                        <div class="text-center py-20">
                            <div class="avatar avatar-xl bg-gray-50 text-textmuted rounded-full mb-3 mx-auto shadow-none border border-dashed border-gray-200">
                                <i class="ri-pie-chart-2-line text-2xl"></i>
                            </div>
                            <p class="text-textmuted text-[12px]">{{ __('No plan revenue found.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Breakdown & List --}}
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 xxl:col-span-12">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title">{{ __('Detailed Breakdown') }}</h4>
                </div>
                <div class="box-body !p-0">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 lg:col-span-5 border-e border-defaultborder/10">
                            <div class="box-header !border-b-0">
                                <h6 class="font-bold text-sm">{{ __('Monthly Summary') }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="ti-custom-table table-hover text-nowrap w-full">
                                    <thead class="bg-gray-100/50 dark:bg-black/20">
                                        <tr>
                                            <th class="!py-3 !px-4">{{ __('Month') }}</th>
                                            <th class="!py-3 !px-4 text-end">{{ __('Revenue') }}</th>
                                            <th class="!py-3 !px-4 text-end">{{ __('Txs') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($breakdown as $row)
                                            <tr class="border-b last:border-0 h-12">
                                                <td class="font-medium !px-4">{{ $row['month'] }}</td>
                                                <td class="text-end !px-4">{{ $money($row['revenue']) }}</td>
                                                <td class="text-end !px-4 text-textmuted text-xs">{{ number_format($row['payments']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center p-4 text-textmuted">{{ __('No data.') }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-7">
                            <div class="box-header !border-b-0 flex justify-between items-center">
                                <h6 class="font-bold text-sm">{{ __('Recent Transactions') }}</h6>
                                <p class="text-[10px] text-textmuted">{{ __('Last 20 records') }}</p>
                            </div>
                            <div class="table-responsive">
                                <table class="ti-custom-table table-hover text-nowrap w-full">
                                    <thead class="bg-gray-100/50 dark:bg-black/20">
                                        <tr>
                                            <th class="!py-3 !px-4">{{ __('Organization') }}</th>
                                            <th class="!py-3 !px-4 text-end">{{ __('Amount') }}</th>
                                            <th class="!py-3 !px-4">{{ __('Status') }}</th>
                                            <th class="!py-3 !px-4">{{ __('Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($payments as $p)
                                            <tr class="border-b last:border-0 h-12">
                                                <td class="!px-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-xs">{{ $p->organization?->name ?? '—' }}</span>
                                                        <span class="text-[10px] text-textmuted">{{ $p->plan?->name ?? '—' }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-end !px-4 font-bold">{{ money_local((float) $p->amount, 0) }}</td>
                                                <td class="!px-4">
                                                    @if ($p->status === \App\Models\Payment::STATUS_SUCCESS)
                                                        <span class="badge bg-success/10 text-success rounded-full px-2 py-1 text-[9px] uppercase">{{ __('Paid') }}</span>
                                                    @elseif ($p->status === \App\Models\Payment::STATUS_FAILED)
                                                        <span class="badge bg-danger/10 text-danger rounded-full px-2 py-1 text-[9px] uppercase">{{ __('Fail') }}</span>
                                                        @if (! empty($p->failure_reason))
                                                            <div class="text-[10px] text-textmuted mt-1" title="{{ $p->failure_reason }}">{{ \Illuminate\Support\Str::limit($p->failure_reason, 80) }}</div>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-warning/10 text-warning rounded-full px-2 py-1 text-[9px] uppercase">{{ __('Hold') }}</span>
                                                    @endif
                                                </td>
                                                <td class="!px-4 text-textmuted text-[10px]">{{ $p->paid_at?->format('M j, Y') ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center p-4 text-textmuted">{{ __('No transactions found.') }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($payments->hasPages())
                                <div class="p-3 border-t border-defaultborder/10">
                                    {{ $payments->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
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
        window.__WP_CRM_REVENUE_CHARTS = @json(array_merge($chartPayload, ['currencySymbol' => currency_context()['currency_code']]));
    </script>
    <script src="{{ asset('materio/assets/js/revenue-analytics.js') }}"></script>
@endpush

@push('vendor-js')
    <script src="{{ asset('materio/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endpush

@push('page-js')
    <script>
        window.__WP_CRM_REVENUE_CHARTS = @json(array_merge($chartPayload, ['currencySymbol' => currency_context()['currency_code']]));
    </script>
    <script src="{{ asset('materio/assets/js/revenue-analytics.js') }}"></script>
@endpush
