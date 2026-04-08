@extends('gcc.layouts.app')

@section('title', 'Organization users')

@push('vendor-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Organization users</h4>
            <p class="mb-0 text-body-secondary">
                All tenant accounts (excluding platform Super Admin). Filter by workspace or role.
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="filter-org">Organization</label>
                    <select name="organization_id" id="filter-org" class="form-select">
                        <option value="">All organizations</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}" @selected((string) $organizationId === (string) $org->id)>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="filter-role">Role</label>
                    <select name="role" id="filter-role" class="form-select">
                        <option value="">All roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected((string) $roleFilter === (string) $role->name)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-base ri ri-filter-3-line me-1"></i>
                        Apply filters
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    @if ($users->isEmpty())
        <div class="card">
            <div class="card-body text-body-secondary">
                No users match the current filters.
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-datatable table-responsive">
                <table id="dt-org-users" class="table table-hover table-sm border-top">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organization</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr>
                                <td class="text-body-secondary">{{ $u->id }}</td>
                                <td class="fw-medium">{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->organization?->name ?? '—' }}</td>
                                <td>
                                    @forelse ($u->roles as $role)
                                        <span class="badge rounded-pill bg-label-primary me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-body-secondary">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    @if ($u->status === \App\Models\User::STATUS_ACTIVE)
                                        <span class="badge rounded-pill bg-label-success">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td data-order="{{ $u->created_at?->timestamp ?? 0 }}">
                                    <span class="text-body-secondary small">{{ $u->created_at?->format('M j, Y H:i') ?? '—' }}</span>
                                </td>
                                <td class="text-end">
                                    <a
                                        href="{{ route('admin.users.show', $u) }}"
                                        class="btn btn-sm btn-text-primary"
                                    >
                                        View
                                    </a>
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
            const $table = jQuery('#dt-org-users');
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
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: -1 },
                    { className: 'align-middle', targets: '_all' },
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search…',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    infoEmpty: 'No users',
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
