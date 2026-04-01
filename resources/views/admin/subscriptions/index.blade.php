@extends('layouts.admin')

@section('title', __('Subscriptions'))

@push('vendor-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@php
    $currencyFmt = static function (?float $amount, string $currency): string {
        if ($amount === null) {
            return '—';
        }
        $sym = strtoupper($currency) === 'INR' ? '₹' : $currency.' ';

        return $sym.number_format($amount, 0);
    };

    $filterLinks = [
        'all' => __('All'),
        'active' => __('Active plans'),
        'expired' => __('Expired plans'),
        'expiring' => __('Expiring in 7 days'),
        'trial' => __('Trial'),
    ];
@endphp

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Subscriptions') }}</h4>
            <p class="mb-0 text-body-secondary">
                {{ __('Track tenant billing periods, trials, and renewal risk from one place.') }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a
                href="{{ route('admin.subscriptions.index', $filter === 'all' ? ['export' => 'csv'] : ['filter' => $filter, 'export' => 'csv']) }}"
                class="btn btn-label-secondary"
            >
                <i class="icon-base ri ri-file-excel-2-line me-1"></i>{{ __('Export CSV') }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap gap-2 mb-4">
        @foreach ($filterLinks as $key => $label)
            <a
                href="{{ $key === 'all' ? route('admin.subscriptions.index') : route('admin.subscriptions.index', ['filter' => $key]) }}"
                @class([
                    'btn btn-sm',
                    'btn-primary' => ($filter === $key) || ($key === 'all' && ($filter === 'all' || $filter === '')),
                    'btn-label-secondary' => ! (($filter === $key) || ($key === 'all' && ($filter === 'all' || $filter === ''))),
                ])
            >{{ $label }}</a>
        @endforeach
    </div>

    <div class="alert alert-info py-2 small mb-4" role="note">
        <i class="icon-base ri ri-mail-send-line me-1"></i>
        {{ __('Automated expiry reminders can be wired to mail / WhatsApp in a future release.') }}
    </div>

    <div class="card mb-3">
        <div class="card-body py-3">
            <div class="row g-3 text-center text-md-start">
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">{{ __('Active') }}</div>
                    <div class="h5 mb-0 text-success">{{ number_format($counts['active']) }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">{{ __('Expired') }}</div>
                    <div class="h5 mb-0 text-danger">{{ number_format($counts['expired']) }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">{{ __('Expiring ≤7d') }}</div>
                    <div class="h5 mb-0 text-warning">{{ number_format($counts['expiring_7d']) }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">{{ __('Trial') }}</div>
                    <div class="h5 mb-0 text-info">{{ number_format($counts['trial']) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($rows->isEmpty())
        <div class="card">
            <div class="card-body text-body-secondary">
                {{ __('No rows match this filter.') }}
                <a href="{{ route('admin.subscriptions.index') }}">{{ __('Clear filters') }}</a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-datatable table-responsive">
                <table id="dt-subscriptions" class="table table-hover table-sm border-top">
                    <thead>
                        <tr>
                            <th>{{ __('Organization') }}</th>
                            <th>{{ __('Plan') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Start') }}</th>
                            <th>{{ __('End') }}</th>
                            <th>{{ __('Days left') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            @php
                                /** @var array $row */
                                $org = $row['organization'];
                                $endTs = $row['end_date']?->timestamp ?? 0;
                            @endphp
                            <tr>
                                <td class="fw-medium">{{ $row['organization_name'] }}</td>
                                <td>{{ $row['plan_name'] }}</td>
                                <td>
                                    @if ($row['status_key'] === 'active')
                                        <span class="badge rounded-pill bg-label-success">{{ $row['status_label'] }}</span>
                                    @elseif ($row['status_key'] === 'trial')
                                        <span class="badge rounded-pill bg-label-info">{{ $row['status_label'] }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-label-danger">{{ $row['status_label'] }}</span>
                                    @endif
                                </td>
                                <td data-order="{{ $row['start_date']?->timestamp ?? 0 }}">
                                    <span class="text-body-secondary small">{{ $row['start_date']?->format('M j, Y') ?? '—' }}</span>
                                </td>
                                <td data-order="{{ $endTs }}">
                                    <span class="text-body-secondary small">{{ $row['end_date']?->format('M j, Y') ?? '—' }}</span>
                                </td>
                                <td data-order="{{ $row['days_remaining'] ?? -9999 }}">
                                    <span @class([
                                        'fw-semibold',
                                        'text-success' => $row['days_color'] === 'success',
                                        'text-warning' => $row['days_color'] === 'warning',
                                        'text-danger' => $row['days_color'] === 'danger',
                                        'text-body-secondary' => $row['days_color'] === 'secondary',
                                    ])>{{ $row['days_display'] }}</span>
                                </td>
                                <td>{{ $currencyFmt($row['amount'], $row['currency']) }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                        <a
                                            href="{{ route('admin.organizations.edit', $org) }}"
                                            class="btn btn-sm btn-text-secondary"
                                            title="{{ __('View organization') }}"
                                        >
                                            <i class="icon-base ri ri-building-line"></i>
                                        </a>
                                        <form
                                            action="{{ route('admin.subscriptions.extend', $org) }}"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm(@json(__('Extend billing period by 30 days?')));"
                                        >
                                            @csrf
                                            <input type="hidden" name="days" value="30">
                                            <button type="submit" class="btn btn-sm btn-label-primary" title="{{ __('Extend plan') }}">
                                                <i class="icon-base ri ri-calendar-check-line"></i>
                                            </button>
                                        </form>
                                        <a
                                            href="{{ route('admin.subscriptions.change-plan', $org) }}"
                                            class="btn btn-sm btn-text-primary"
                                            title="{{ __('Change plan') }}"
                                        >
                                            <i class="icon-base ri ri-swap-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('vendor-js')
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js" crossorigin="anonymous"></script>
@endpush

@push('page-js')
    <script>
        (function () {
            if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) {
                return;
            }
            const $table = jQuery('#dt-subscriptions');
            if (!$table.length || $table.find('tbody tr').length === 0) {
                return;
            }
            $table.DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
                ],
                order: [[4, 'asc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: -1 },
                    { className: 'align-middle', targets: '_all' },
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search…',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    infoEmpty: 'No subscriptions',
                    infoFiltered: '(filtered from _MAX_)',
                    zeroRecords: 'No matching rows',
                    paginate: { next: 'Next', previous: 'Prev' },
                },
                dom:
                    "<'row align-items-center justify-content-between g-2 mb-3 px-3 pt-3'<'col-sm-12 col-md-6 d-flex align-items-center'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
                    "<'table-responsive'tr>" +
                    "<'row align-items-center justify-content-between g-2 px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
            });
        })();
    </script>
@endpush
