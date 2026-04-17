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
        // Amounts are stored in INR; currency column here is legacy/display only.
        return money_local((float) $amount, 0);
    };

    $filterLinks = [
        'all' => __('All'),
        'active' => __('Active'),
        'expired' => __('Expired'),
        'expiring' => __('Expiring ≤7d'),
        'trial' => __('Trial'),
    ];
@endphp

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Subscriptions') }}</h5>
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
                            {{ __('Subscriptions') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('admin.subscriptions.index', $filter === 'all' ? ['export' => 'csv'] : ['filter' => $filter, 'export' => 'csv']) }}" class="ti-btn ti-btn-light font-medium !mb-0 shadow-sm border border-defaultborder/10">
                <i class="ri-file-excel-2-line me-1"></i>{{ __('Export CSV') }}
            </a>
            <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon ms-2 !mb-0" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
            <div class="flex items-center">
                <i class="ri-checkbox-circle-line me-2 text-lg"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    {{-- Stats Summary --}}
    <div class="grid grid-cols-12 gap-x-6 mb-6">
        <div class="col-span-12 md:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-textmuted text-[11px] font-bold uppercase tracking-widest mb-1">{{ __('Active') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-success">{{ number_format($counts['active']) }}</h4>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-success/10 text-success rounded-md p-2">
                            <i class="ri-checkbox-circle-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-textmuted text-[11px] font-bold uppercase tracking-widest mb-1">{{ __('Expired') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-danger">{{ number_format($counts['expired']) }}</h4>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-danger/10 text-danger rounded-md p-2">
                            <i class="ri-close-circle-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-textmuted text-[11px] font-bold uppercase tracking-widest mb-1">{{ __('Expiring ≤7d') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-warning">{{ number_format($counts['expiring_7d']) }}</h4>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-warning/10 text-warning rounded-md p-2">
                            <i class="ri-time-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-3">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-body !p-4">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-textmuted text-[11px] font-bold uppercase tracking-widest mb-1">{{ __('Trial') }}</p>
                            <h4 class="text-[1.25rem] font-bold mb-0 text-info">{{ number_format($counts['trial']) }}</h4>
                        </div>
                        <div class="ti-avatar ti-avatar-md bg-info/10 text-info rounded-md p-2">
                            <i class="ri-user-star-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box shadow-none border border-defaultborder/10">
        <div class="box-header !border-b !border-defaultborder/10 flex flex-wrap justify-between items-center gap-4">
            <div class="flex flex-wrap gap-2">
                @foreach ($filterLinks as $key => $label)
                    <a href="{{ $key === 'all' ? route('admin.subscriptions.index') : route('admin.subscriptions.index', ['filter' => $key]) }}"
                       @class([
                           'ti-btn ti-btn-sm !font-medium !mb-0 transition-all',
                           'ti-btn-primary-full' => ($filter === $key) || ($key === 'all' && ($filter === 'all' || $filter === '')),
                           'ti-btn-light' => ! (($filter === $key) || ($key === 'all' && ($filter === 'all' || $filter === ''))),
                       ])
                    >{{ $label }}</a>
                @endforeach
            </div>
            <div class="flex items-center gap-2 text-textmuted text-[11px]">
                <i class="ri-information-line text-[14px]"></i>
                {{ __('Refining visibility by plan status.') }}
            </div>
        </div>
        <div class="box-body !p-0">
            @if ($rows->isEmpty())
                <div class="p-20 text-center">
                    <p class="text-textmuted mb-2">{{ __('No subscriptions found for this criteria.') }}</p>
                    <a href="{{ route('admin.subscriptions.index') }}" class="text-primary font-bold decoration-2">{{ __('Clear filters') }}</a>
                </div>
            @else
                <div class="table-responsive p-4">
                    <table id="dt-subscriptions" class="ti-custom-table table-hover text-nowrap w-full">
                        <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                            <tr>
                                <th class="!py-3 !px-4">{{ __('Organization') }}</th>
                                <th class="!py-3 !px-4">{{ __('Plan') }}</th>
                                <th class="!py-3 !px-4">{{ __('Status') }}</th>
                                <th class="!py-3 !px-4">{{ __('Start') }}</th>
                                <th class="!py-3 !px-4">{{ __('Expiry') }}</th>
                                <th class="!py-3 !px-4">{{ __('Time Left') }}</th>
                                <th class="!py-3 !px-4 text-end">{{ __('Amount') }}</th>
                                <th class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $org = $row['organization'];
                                @endphp
                                <tr class="border-b last:border-0 hover:bg-gray-50/20 transition-colors h-14">
                                    <td class="!px-4 font-bold text-sm text-defaulttextcolor">{{ $row['organization_name'] }}</td>
                                    <td class="!px-4 text-sm">{{ $row['plan_name'] }}</td>
                                    <td class="!px-4">
                                        @if ($row['status_key'] === 'active')
                                            <span class="badge bg-success/10 text-success rounded-full px-2 py-1 text-[10px] border border-success/20">{{ $row['status_label'] }}</span>
                                        @elseif ($row['status_key'] === 'trial')
                                            <span class="badge bg-info/10 text-info rounded-full px-2 py-1 text-[10px] border border-info/20">{{ $row['status_label'] }}</span>
                                        @else
                                            <span class="badge bg-danger/10 text-danger rounded-full px-2 py-1 text-[10px] border border-danger/20">{{ $row['status_label'] }}</span>
                                        @endif
                                    </td>
                                    <td class="!px-4 text-[12px] text-textmuted" data-order="{{ $row['start_date']?->timestamp ?? 0 }}">
                                        {{ $row['start_date']?->format('M j, Y') ?? '—' }}
                                    </td>
                                    <td class="!px-4 text-[12px] text-textmuted" data-order="{{ $row['end_date']?->timestamp ?? 0 }}">
                                        {{ $row['end_date']?->format('M j, Y') ?? '—' }}
                                    </td>
                                    <td class="!px-4" data-order="{{ $row['days_remaining'] ?? -9999 }}">
                                        <span @class([
                                            'font-bold text-xs',
                                            'text-success' => $row['days_color'] === 'success',
                                            'text-warning' => $row['days_color'] === 'warning',
                                            'text-danger' => $row['days_color'] === 'danger',
                                            'text-textmuted' => $row['days_color'] === 'secondary',
                                        ])>{{ $row['days_display'] }}</span>
                                    </td>
                                    <td class="!px-4 text-end font-medium">{{ $currencyFmt($row['amount'], $row['currency']) }}</td>
                                    <td class="text-end !px-4">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('admin.organizations.edit', $org) }}" class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0" title="{{ __('Organization') }}">
                                                <i class="ri-building-line"></i>
                                            </a>
                                            <form action="{{ route('admin.subscriptions.extend', $org) }}" method="post" data-confirm="{{ __('Extend billing period by 30 days?') }}">
                                                @csrf
                                                <input type="hidden" name="days" value="30">
                                                <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0" title="{{ __('Extend (+30d)') }}">
                                                    <i class="ri-calendar-check-line"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.subscriptions.change-plan', $org) }}" class="ti-btn ti-btn-sm ti-btn-soft-info !border-0" title="{{ __('Change Plan') }}">
                                                <i class="ri-swap-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
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
                    lengthMenu: 'Show _MENU_',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    infoEmpty: 'No subscriptions',
                    infoFiltered: '(filtered from _MAX_)',
                    zeroRecords: 'No matching rows',
                    paginate: { next: 'Next', previous: 'Prev' },
                },
                dom:
                    "<'flex flex-wrap items-center justify-between gap-4 mb-4'<'flex items-center text-xs'l><'flex items-center'f>>" +
                    "<'table-responsive'tr>" +
                    "<'flex flex-wrap items-center justify-between gap-4 mt-4'<'flex items-center text-xs text-textmuted'i><'flex items-center'p>>",
            });
            
            // Re-style for compatibility
            jQuery('.dataTables_filter input').addClass('ti-form-input !py-2 !px-3 !text-sm border-gray-200 focus:border-primary focus:ring-primary rounded-md');
            jQuery('.dataTables_length select').addClass('ti-form-select !py-2 !px-3 !text-sm border-gray-200 focus:border-primary focus:ring-primary rounded-md !w-20 mx-2');
        })();
    </script>
@endpush
