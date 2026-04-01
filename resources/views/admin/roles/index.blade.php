@extends('layouts.admin')

@section('title', __('Roles & Permissions'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Roles & Permissions') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Create roles and assign permissions.') }}</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleCreateModal">
            <i class="icon-base ri ri-add-line me-1"></i>{{ __('Add role') }}
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <strong>{{ __('Please fix the errors and try again.') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Permissions') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        @php
                            $isLocked = in_array($role->name, [\App\Support\Roles::SUPER_ADMIN, \App\Support\Roles::ORGANIZATION], true);
                            $payload = [
                                'id' => $role->id,
                                'name' => $role->name,
                                'permissions' => $role->permissions->pluck('name')->values()->all(),
                                'locked' => $isLocked,
                            ];
                            $json = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                        @endphp
                        <tr>
                            <td class="fw-medium">{{ $role->name }}</td>
                            <td>
                                <span class="badge rounded-pill bg-label-primary">{{ number_format($role->permissions_count) }}</span>
                            </td>
                            <td class="text-body-secondary small" data-order="{{ $role->created_at?->timestamp ?? 0 }}">
                                {{ $role->created_at?->format('M j, Y H:i') ?? '—' }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill js-role-edit"
                                        data-role="{{ $json }}"
                                        title="{{ __('Edit') }}"
                                    >
                                        <i class="icon-base ri ri-pencil-line icon-20px"></i>
                                    </button>
                                    <form
                                        method="post"
                                        action="{{ route('admin.roles.destroy', $role) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('{{ __('Delete this role?') }}');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                                            title="{{ __('Delete') }}"
                                            @disabled($isLocked)
                                        >
                                            <i class="icon-base ri ri-delete-bin-6-line icon-20px"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="roleCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form method="post" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">{{ __('Add role') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label" for="create-role-name">{{ __('Role name') }}</label>
                                <input
                                    type="text"
                                    id="create-role-name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Sales Manager"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Use a short, readable name.') }}</div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label d-block">{{ __('Permissions') }}</label>
                                <div class="border rounded-3 p-3" style="max-height: 18rem; overflow:auto;">
                                    <div class="row g-2">
                                        @foreach ($permissions as $p)
                                            <div class="col-md-6">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $p['name'] }}">
                                                    <span class="form-check-label">{{ $p['label'] }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('permissions')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="roleEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form method="post" action="#" id="role-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">{{ __('Edit role') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label" for="edit-role-name">{{ __('Role name') }}</label>
                                <input type="text" id="edit-role-name" name="name" class="form-control" required>
                                <div class="form-text" id="role-edit-locked-hint"></div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label d-block">{{ __('Permissions') }}</label>
                                <div class="border rounded-3 p-3" style="max-height: 18rem; overflow:auto;">
                                    <div class="row g-2">
                                        @foreach ($permissions as $p)
                                            <div class="col-md-6">
                                                <label class="form-check">
                                                    <input class="form-check-input js-edit-perm" type="checkbox" name="permissions[]" value="{{ $p['name'] }}">
                                                    <span class="form-check-label">{{ $p['label'] }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="role-edit-submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        (function () {
            if (typeof bootstrap === 'undefined') return;
            const editModalEl = document.getElementById('roleEditModal');
            if (!editModalEl) return;
            const editModal = new bootstrap.Modal(editModalEl);
            const form = document.getElementById('role-edit-form');
            const nameInput = document.getElementById('edit-role-name');
            const hint = document.getElementById('role-edit-locked-hint');
            const submit = document.getElementById('role-edit-submit');
            const permInputs = Array.from(document.querySelectorAll('.js-edit-perm'));

            function clearPerms() {
                permInputs.forEach(i => i.checked = false);
            }

            document.querySelectorAll('.js-role-edit').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const raw = btn.getAttribute('data-role');
                    if (!raw) return;
                    const data = JSON.parse(raw);
                    form.action = '{{ url('/admin/roles') }}/' + data.id;
                    nameInput.value = data.name;
                    clearPerms();
                    (data.permissions || []).forEach(function (p) {
                        const el = permInputs.find(i => i.value === p);
                        if (el) el.checked = true;
                    });
                    const locked = !!data.locked;
                    nameInput.disabled = locked;
                    permInputs.forEach(i => i.disabled = locked);
                    submit.disabled = locked;
                    hint.textContent = locked ? '{{ __('This role is locked by the system.') }}' : '';
                    editModal.show();
                });
            });
        })();
    </script>
@endpush

