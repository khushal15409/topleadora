@extends('layouts.admin')

@section('title', __('Organizations'))

@push('vendor-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Organizations') }}</h5>
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
                            {{ __('Organizations') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('admin.organizations.create') }}" class="ti-btn ti-btn-primary font-medium !mb-0 shadow-sm">
                <i class="ri-add-line me-1"></i>{{ __('Add organization') }}
            </a>
            <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon ms-2 !mb-0" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
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

            @if ($errors->has('delete'))
                <div class="bg-danger/10 text-danger border border-danger/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
                    <div class="flex items-center">
                        <i class="ri-error-warning-line me-2 text-lg"></i>
                        {{ $errors->first('delete') }}
                    </div>
                    <button type="button" class="text-danger" data-bs-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif

            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10">
                    <div class="flex flex-wrap justify-between items-center gap-2">
                        <div>
                            <h4 class="box-title font-semibold">{{ __('All Organizations') }}</h4>
                            <p class="text-textmuted text-xs mt-1">
                                {{ __('Tenant workspaces, trials, and plans. Users with the Organization role belong to one organization.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="box-body !p-0">
                    @if ($organizations->isEmpty())
                        <div class="p-20 text-center">
                            <p class="text-textmuted mb-2">{{ __('No organizations found.') }}</p>
                            <a href="{{ route('admin.organizations.create') }}" class="text-primary font-bold decoration-2">{{ __('Create one') }}</a>
                        </div>
                    @else
                        <div class="table-responsive p-4">
                            <table id="dt-organizations" class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Name') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Status') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Plan') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Trial ends') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Users') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Onboarding') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($organizations as $org)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/20 transition-colors h-14">
                                            <td class="!px-4">
                                                <div class="flex items-center">
                                                    <div class="ti-avatar ti-avatar-sm bg-primary/10 text-primary rounded-md me-3 font-bold">
                                                        {{ strtoupper(substr($org->name, 0, 1)) }}
                                                    </div>
                                                    <span class="font-bold text-sm text-defaulttextcolor">{{ $org->name }}</span>
                                                </div>
                                            </td>
                                            <td class="!px-4">
                                                @if ($org->status === \App\Models\Organization::STATUS_ACTIVE)
                                                    <span class="badge bg-success/10 text-success rounded-full px-2 py-1 text-[10px] border border-success/20">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-gray-100 text-gray-500 rounded-full px-2 py-1 text-[10px] border border-gray-200">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="!px-4 text-sm font-medium">{{ $org->plan?->name ?? '—' }}</td>
                                            <td class="!px-4 text-[12px] text-textmuted" data-order="{{ $org->trial_ends_at?->timestamp ?? 0 }}">
                                                @if ($org->trial_ends_at)
                                                    <div class="flex flex-col">
                                                        <span>{{ $org->trial_ends_at->format('M j, Y') }}</span>
                                                        <span class="text-[10px]">{{ $org->trial_ends_at->format('H:i') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-textmuted/50">—</span>
                                                @endif
                                            </td>
                                            <td class="!px-4"><span class="ti-avatar ti-avatar-xs bg-light text-defaulttextcolor rounded-full text-[11px]">{{ $org->users_count }}</span></td>
                                            <td class="!px-4">
                                                @if ($org->onboarding_completed)
                                                    <span class="badge bg-info/10 text-info rounded-full px-2 py-1 text-[10px] border border-info/20">{{ __('Done') }}</span>
                                                @else
                                                    <span class="badge bg-warning/10 text-warning rounded-full px-2 py-1 text-[10px] border border-warning/20">{{ __('Pending') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end !px-4">
                                                <div class="flex justify-end gap-1">
                                                    <a href="{{ route('admin.organizations.edit', $org) }}" class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0" title="{{ __('Edit') }}">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    @if ($org->users_count === 0)
                                                        <form action="{{ route('admin.organizations.destroy', $org) }}" method="post" class="inline" onsubmit="return confirm('{{ __('Delete this organization?') }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-danger !border-0" title="{{ __('Delete') }}">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif
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
            const $table = jQuery('#dt-organizations');
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
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: -1 },
                    { className: 'align-middle', targets: '_all' },
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search…',
                    lengthMenu: 'Show _MENU_',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    infoEmpty: 'No organizations',
                    infoFiltered: '(filtered from _MAX_)',
                    zeroRecords: 'No matching rows',
                    paginate: { next: 'Next', previous: 'Prev' },
                },
                dom:
                    "<'flex flex-wrap items-center justify-between gap-4 mb-4'<'flex items-center text-xs'l><'flex items-center'f>>" +
                    "<'table-responsive'tr>" +
                    "<'flex flex-wrap items-center justify-between gap-4 mt-4'<'flex items-center text-xs text-textmuted'i><'flex items-center'p>>",
            });
            
            // Re-style the search input and length menu for Tailwind
            jQuery('.dataTables_filter input').addClass('ti-form-input !py-2 !px-3 !text-sm border-gray-200 focus:border-primary focus:ring-primary rounded-md');
            jQuery('.dataTables_length select').addClass('ti-form-select !py-2 !px-3 !text-sm border-gray-200 focus:border-primary focus:ring-primary rounded-md !w-20 mx-2');
        })();
    </script>
@endpush
