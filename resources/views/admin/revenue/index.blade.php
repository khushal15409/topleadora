@extends('layouts.admin')

@section('title', __('Revenue Analytics'))

@push('vendor-css')
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@php
    $money = static function (float $amount): string {
        return '₹'.number_format($amount, 0);
    };

    $exportQuery = array_merge(request()->only(['range', 'date_from', 'date_to', 'payment_status', 'q']), ['export' => 'csv']);
@endphp

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Revenue Analytics') }}</h4>
            <p class="mb-0 text-body-secondary">
                {{ __('Payments tied to subscription checkouts — totals, trends, and plan mix.') }}
            </p>
        </div>
        <a href="{{ route('admin.revenue.index', $exportQuery) }}" class="btn btn-label-secondary">
            <i class="icon-base ri ri-file-excel-2-line me-1"></i>{{ __('Export CSV') }}
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="{{ route('admin.revenue.index') }}" class="row g-3 align-items-end">
                <div class="col-12">
                    <span class="text-uppercase small text-muted fw-semibold">{{ __('Date range') }}</span>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach (['today' => __('Today'), 'week' => __('This week'), 'month' => __('This month'), 'year' => __('This year'), 'custom' => __('Custom')] as $key => $lbl)
                            <input
                                type="radio"
                                class="btn-check"
                                name="range"
                                id="revenue-range-{{ $key }}"
                                value="{{ $key }}"
                                @checked((string) $range === $key)
                                onchange="this.form.submit()"
                            >
                            <label class="btn btn-sm btn-outline-secondary" for="revenue-range-{{ $key }}">{{ $lbl }}</label>
                        @endforeach
                    </div>
                </div>
                @if ((string) $range === 'custom')
                    <div class="col-md-3">
                        <label class="form-label">{{ __('From') }}</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('To') }}</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label">{{ __('Payment status') }}</label>
                    <select name="payment_status" class="form-select" onchange="this.form.submit()">
                        <option value="{{ \App\Models\Payment::STATUS_SUCCESS }}" @selected((string) $paymentStatus === \App\Models\Payment::STATUS_SUCCESS)>{{ __('Success') }}</option>
                        <option value="{{ \App\Models\Payment::STATUS_FAILED }}" @selected((string) $paymentStatus === \App\Models\Payment::STATUS_FAILED)>{{ __('Failed') }}</option>
                        <option value="{{ \App\Models\Payment::STATUS_PENDING }}" @selected((string) $paymentStatus === \App\Models\Payment::STATUS_PENDING)>{{ __('Pending') }}</option>
                        <option value="all" @selected((string) $paymentStatus === 'all')>{{ __('All statuses') }}</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-4">
                    <label class="form-label">{{ __('Search organization') }}</label>
                    <div class="input-group">
                        <input type="search" name="q" class="form-control" value="{{ $search }}" placeholder="{{ __('Name…') }}">
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                </div>
                @if ((string) $range === 'custom')
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                @endif
            </form>
            <p class="small text-muted mb-0 mt-3">
                {{ __('Filtered period: :from — :to', ['from' => $rangeStart->format('M j, Y'), 'to' => $rangeEnd->format('M j, Y')]) }}
            </p>
        </div>
    </div>

    <div class="row g-4 mb-2">
        <div class="col-sm-6 col-xl-3">
            <div class="card wp-crm-revenue-card wp-crm-revenue-card--total shadow h-100 border-0">
                <div class="card-body p-4">
                    <div class="small text-white text-white-50 text-uppercase fw-semibold mb-2">{{ __('Total revenue') }}</div>
                    <h3 class="mb-0 text-white fw-bold">{{ $money($summary['total_revenue']) }}</h3>
                    <small class="text-white-50">{{ __('In selected period') }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card wp-crm-revenue-card wp-crm-revenue-card--month shadow h-100 border-0">
                <div class="card-body p-4">
                    <div class="small text-white text-white-50 text-uppercase fw-semibold mb-2">{{ __('This month') }}</div>
                    <h3 class="mb-0 text-white fw-bold">{{ $money($summary['this_month_revenue']) }}</h3>
                    <small class="text-white-50">{{ __('Calendar month · status filter') }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card wp-crm-revenue-card wp-crm-revenue-card--last shadow h-100 border-0">
                <div class="card-body p-4">
                    <div class="small text-white text-white-50 text-uppercase fw-semibold mb-2">{{ __('Last month') }}</div>
                    <h3 class="mb-0 text-white fw-bold">{{ $money($summary['last_month_revenue']) }}</h3>
                    <small class="text-white-50">{{ __('Previous calendar month') }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card wp-crm-revenue-card wp-crm-revenue-card--tx shadow h-100 border-0">
                <div class="card-body p-4">
                    <div class="small text-white text-white-50 text-uppercase fw-semibold mb-2">{{ __('Transactions') }}</div>
                    <h3 class="mb-0 text-white fw-bold">{{ number_format($summary['transaction_count']) }}</h3>
                    <small class="text-white-50">{{ __('In selected period') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card shadow-sm h-100">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">{{ __('Revenue trend') }}</h5>
                    <small class="text-body-secondary">{{ __('Calendar year :year (Jan–Dec), status filter applied', ['year' => $rangeEnd->year]) }}</small>
                </div>
                <div class="card-body pt-2">
                    <div id="revenueYearChart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">{{ __('Revenue by plan') }}</h5>
                    <small class="text-body-secondary">{{ __('Selected date range') }}</small>
                </div>
                <div class="card-body pt-2 d-flex align-items-center justify-content-center">
                    @if (count($byPlan) > 0 && collect($chartPayload['planSeries'])->sum() > 0)
                        <div id="revenuePlanChart" class="w-100"></div>
                    @else
                        <p class="text-body-secondary small mb-0 text-center py-5">{{ __('No plan revenue in this range.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">{{ __('Period detail (by month)') }}</h5>
                    <small class="text-body-secondary">{{ __('Within your selected filters') }}</small>
                </div>
                <div class="card-body pt-2">
                    @if (count($chartPayload['periodLabels']) > 0)
                        <div id="revenuePeriodChart" style="max-width: 48rem;"></div>
                    @else
                        <p class="text-body-secondary small mb-0">{{ __('No monthly buckets for this range.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0">{{ __('Monthly breakdown') }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 border-top">
                <thead>
                    <tr>
                        <th>{{ __('Month') }}</th>
                        <th class="text-end">{{ __('Total revenue') }}</th>
                        <th class="text-end">{{ __('Payments') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($breakdown as $row)
                        <tr>
                            <td class="fw-medium">{{ $row['month'] }}</td>
                            <td class="text-end">{{ $money($row['revenue']) }}</td>
                            <td class="text-end">{{ number_format($row['payments']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-body-secondary py-4">{{ __('No data.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0">{{ __('Recent payments') }}</h5>
                <small class="text-body-secondary">{{ __('Filtered list with pagination') }}</small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 border-top">
                <thead>
                    <tr>
                        <th>{{ __('Organization') }}</th>
                        <th>{{ __('Plan') }}</th>
                        <th class="text-end">{{ __('Amount') }}</th>
                        <th>{{ __('Payment date') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $p)
                        <tr>
                            <td class="fw-medium">{{ $p->organization?->name ?? '—' }}</td>
                            <td>{{ $p->plan?->name ?? '—' }}</td>
                            <td class="text-end">{{ $p->currency === 'INR' ? '₹' : $p->currency }}{{ number_format((float) $p->amount, 0) }}</td>
                            <td class="text-body-secondary small">{{ $p->paid_at?->format('M j, Y H:i') ?? '—' }}</td>
                            <td>
                                @if ($p->status === \App\Models\Payment::STATUS_SUCCESS)
                                    <span class="badge rounded-pill bg-label-success">{{ __('Success') }}</span>
                                @elseif ($p->status === \App\Models\Payment::STATUS_FAILED)
                                    <span class="badge rounded-pill bg-label-danger">{{ __('Failed') }}</span>
                                @else
                                    <span class="badge rounded-pill bg-label-warning">{{ __('Pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-5">{{ __('No payments match your filters.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
            <div class="card-body border-top pt-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection

@push('vendor-js')
    <script src="{{ asset('materio/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endpush

@push('page-js')
    <script>
        window.__WP_CRM_REVENUE_CHARTS = @json(array_merge($chartPayload, ['currencySymbol' => '₹']));
    </script>
    <script src="{{ asset('materio/assets/js/revenue-analytics.js') }}"></script>
@endpush
