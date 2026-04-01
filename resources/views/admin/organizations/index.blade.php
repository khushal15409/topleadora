@extends('layouts.admin')

@section('title', 'Organizations')

@push('vendor-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Organizations</h4>
            <p class="mb-0 text-body-secondary">
                Tenant workspaces, trials, and plans. Users with the Organization role belong to one organization.
            </p>
        </div>
        <a href="{{ route('admin.organizations.create') }}" class="btn btn-primary">
            <i class="icon-base ri ri-add-line me-1"></i>
            Add organization
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->has('delete'))
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            {{ $errors->first('delete') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($organizations->isEmpty())
        <div class="card">
            <div class="card-body text-body-secondary">
                No organizations yet.
                <a href="{{ route('admin.organizations.create') }}">Create one</a>
                or register from the public site.
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-datatable table-responsive">
                <table id="dt-organizations" class="table table-hover table-sm border-top">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Plan</th>
                            <th>Trial ends</th>
                            <th>Users</th>
                            <th>Onboarding</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organizations as $org)
                            <tr>
                                <td class="fw-medium">{{ $org->name }}</td>
                                <td>
                                    @if ($org->status === \App\Models\Organization::STATUS_ACTIVE)
                                        <span class="badge rounded-pill bg-label-success">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $org->plan?->name ?? '—' }}</td>
                                <td data-order="{{ $org->trial_ends_at?->timestamp ?? 0 }}">
                                    @if ($org->trial_ends_at)
                                        <span class="text-body-secondary small">{{ $org->trial_ends_at->format('M j, Y H:i') }}</span>
                                    @else
                                        <span class="text-body-secondary">—</span>
                                    @endif
                                </td>
                                <td>{{ $org->users_count }}</td>
                                <td>
                                    @if ($org->onboarding_completed)
                                        <span class="badge rounded-pill bg-label-success">Done</span>
                                    @else
                                        <span class="badge rounded-pill bg-label-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a
                                            href="{{ route('admin.organizations.edit', $org) }}"
                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            title="Edit"
                                        >
                                            <i class="icon-base ri ri-pencil-line icon-20px"></i>
                                        </a>
                                        @if ($org->users_count === 0)
                                            <form
                                                action="{{ route('admin.organizations.destroy', $org) }}"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Delete this organization?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill" title="Delete">
                                                    <i class="icon-base ri ri-delete-bin-6-line icon-20px"></i>
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
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    infoEmpty: 'No organizations',
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
