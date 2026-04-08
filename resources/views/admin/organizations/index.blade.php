@extends('layouts.admin')

@section('title', 'Organizations')

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
            <a href="{{ route('admin.organizations.create') }}" class="ti-btn ti-btn-primary font-medium">
                <i class="ri-add-line me-1"></i>
                {{ __('Add organization') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            @if (session('success'))
                <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif

            @if ($errors->has('delete'))
                <div class="bg-danger/10 text-danger border border-danger/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
                    {{ $errors->first('delete') }}
                    <button type="button" class="text-danger" data-bs-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif

            <div class="box">
                <div class="box-header">
                    <div class="flex justify-between items-center">
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
                        <div class="p-6 text-textmuted text-center">
                            {{ __('No organizations yet.') }}
                            <a href="{{ route('admin.organizations.create') }}" class="text-primary font-medium">{{ __('Create one') }}</a>
                            {{ __('or register from the public site.') }}
                        </div>
                    @else
                        <div class="table-responsive p-4">
                            <table id="dt-organizations" class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-50 border-y dark:bg-black/10">
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
                                        <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                            <td class="font-medium !px-4">
                                                <div class="flex items-center">
                                                    <span class="avatar avatar-sm bg-primary/10 text-primary rounded-full me-3">
                                                        {{ strtoupper(substr($org->name, 0, 1)) }}
                                                    </span>
                                                    {{ $org->name }}
                                                </div>
                                            </td>
                                            <td class="!px-4">
                                                @if ($org->status === \App\Models\Organization::STATUS_ACTIVE)
                                                    <span class="badge bg-success/10 text-success rounded-full">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-gray-100 text-gray-500 rounded-full">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="!px-4 text-sm">{{ $org->plan?->name ?? '—' }}</td>
                                            <td class="!px-4 text-sm text-textmuted" data-order="{{ $org->trial_ends_at?->timestamp ?? 0 }}">
                                                @if ($org->trial_ends_at)
                                                    {{ $org->trial_ends_at->format('M j, Y') }}<br>
                                                    <small>{{ $org->trial_ends_at->format('H:i') }}</small>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="!px-4"><span class="font-semibold">{{ $org->users_count }}</span></td>
                                            <td class="!px-4">
                                                @if ($org->onboarding_completed)
                                                    <span class="badge bg-info/10 text-info rounded-full">{{ __('Done') }}</span>
                                                @else
                                                    <span class="badge bg-warning/10 text-warning rounded-full">{{ __('Pending') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end !px-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.organizations.edit', $org) }}" class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0 p-2" title="{{ __('Edit') }}">
                                                        <i class="ri-pencil-line text-lg"></i>
                                                    </a>
                                                    @if ($org->users_count === 0)
                                                        <form action="{{ route('admin.organizations.destroy', $org) }}" method="post" class="inline" onsubmit="return confirm('{{ __('Delete this organization?') }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-danger !border-0 p-2" title="{{ __('Delete') }}">
                                                                <i class="ri-delete-bin-line text-lg"></i>
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
                    "<'flex flex-wrap items-center justify-between gap-4 mb-4'<'flex items-center'l><'flex items-center'f>>" +
                    "<'table-responsive'tr>" +
                    "<'flex flex-wrap items-center justify-between gap-4 mt-4'<'flex items-center text-xs text-textmuted'i><'flex items-center'p>>",
            });
            
            // Re-style the search input and length menu for Tailwind
            jQuery('.dataTables_filter input').addClass('ti-form-input !py-2 !px-3 !text-sm border-gray-200 focus:border-primary focus:ring-primary rounded-md');
            jQuery('.dataTables_length select').addClass('ti-form-select !py-2 !px-3 !text-sm border-gray-200 focus:border-primary focus:ring-primary rounded-md !w-20 mx-2');
        })();
    </script>
@endpush
