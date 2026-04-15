@extends('layouts.admin')

@section('title', __('Roles & Permissions'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Roles & Permissions') }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Admin') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Roles & Permissions') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <button type="button" class="ti-btn ti-btn-primary font-medium" data-hs-overlay="#roleCreateModal">
                <i class="ri-add-line me-1"></i>{{ __('Add role') }}
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center"
            role="alert">
            {{ session('success') }}
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('User Roles') }}</h4>
                    <p class="text-textmuted text-xs mt-1">
                        {{ __('Create roles and assign permissions to manage user access levels.') }}</p>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-50 border-y dark:bg-black/10">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Role Name') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Permissions Count') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Created Date') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
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
                                    <tr class="border-b last:border-0 hover:bg-gray-50/20 transition-colors h-14">
                                        <td class="font-medium !px-4">
                                            <div class="flex items-center">
                                                <div class="ti-avatar ti-avatar-sm bg-primary/10 text-primary rounded-md me-3">
                                                    <i class="ri-shield-user-line"></i>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-sm text-defaulttextcolor">{{ $role->name }}</span>
                                                    @if($isLocked)
                                                        <span class="text-[10px] text-orange-500 flex items-center gap-1">
                                                            <i class="ri-lock-password-line"></i> {{ __('System Protected') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="!px-4">
                                            <span class="badge bg-primary/10 text-primary rounded-full px-2 py-1 text-[10px] border border-primary/20">{{ number_format($role->permissions_count) }} {{ __('Perms') }}</span>
                                        </td>
                                        <td class="text-textmuted text-[12px] !px-4">
                                            {{ $role->created_at?->format('M j, Y') ?? '—' }}
                                        </td>
                                        <td class="text-end !px-4">
                                            <div class="flex justify-end gap-1">
                                                <button type="button"
                                                    class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0 js-role-edit"
                                                    data-role="{{ $json }}" title="{{ __('Edit') }}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form method="post" action="{{ route('admin.roles.destroy', $role) }}"
                                                    class="inline" onsubmit="return confirm('{{ __('Delete this role?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="ti-btn ti-btn-sm ti-btn-soft-danger !border-0"
                                                        title="{{ __('Delete') }}" @disabled($isLocked)>
                                                        <i class="ri-delete-bin-line"></i>
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
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="roleCreateModal"
        class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all lg:max-w-4xl lg:w-full m-3 lg:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div
                class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7] w-full">
                <form method="post" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">{{ __('Add New Role') }}</h3>
                        <button type="button"
                            class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800"
                            data-hs-overlay="#roleCreateModal">
                            <span class="sr-only">{{ __('Close') }}</span>
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 lg:col-span-4">
                                <label class="block text-sm font-medium mb-2"
                                    for="create-role-name">{{ __('Role name') }}</label>
                                <input type="text" id="create-role-name" name="name"
                                    class="ti-form-input @error('name') !border-danger @enderror" value="{{ old('name') }}"
                                    placeholder="{{ __('Sales Manager') }}" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-textmuted text-xs mt-2">{{ __('Use a short, readable name for the role.') }}
                                </p>
                            </div>
                            <div class="col-span-12 lg:col-span-8">
                                <label class="block text-sm font-medium mb-2">{{ __('Permissions') }}</label>
                                <div
                                    class="border rounded-md p-4 bg-gray-50/50 dark:bg-black/10 max-h-[400px] overflow-y-auto">
                                    <div class="grid grid-cols-12 gap-4">
                                        @foreach ($permissions as $p)
                                            <div class="col-span-12 md:col-span-6">
                                                <div class="flex items-center">
                                                    <input class="ti-form-checkbox rounded-sm" type="checkbox"
                                                        name="permissions[]" value="{{ $p['name'] }}"
                                                        id="perm-{{ $loop->index }}">
                                                    <label class="ms-3 text-sm"
                                                        for="perm-{{ $loop->index }}">{{ $p['label'] }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('permissions')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button" class="ti-btn ti-btn-light"
                            data-hs-overlay="#roleCreateModal">{{ __('Cancel') }}</button>
                        <button type="submit" class="ti-btn ti-btn-primary">{{ __('Create Role') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="roleEditModal"
        class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all lg:max-w-4xl lg:w-full m-3 lg:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div
                class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7] w-full">
                <form method="post" action="#" id="role-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">{{ __('Edit Role') }}</h3>
                        <button type="button"
                            class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800"
                            data-hs-overlay="#roleEditModal">
                            <span class="sr-only">{{ __('Close') }}</span>
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 lg:col-span-4">
                                <label class="block text-sm font-medium mb-2"
                                    for="edit-role-name">{{ __('Role name') }}</label>
                                <input type="text" id="edit-role-name" name="name" class="ti-form-input" required>
                                <p class="text-danger text-xs mt-2" id="role-edit-locked-hint"></p>
                            </div>
                            <div class="col-span-12 lg:col-span-8">
                                <label class="block text-sm font-medium mb-2">{{ __('Permissions') }}</label>
                                <div
                                    class="border rounded-md p-4 bg-gray-50/50 dark:bg-black/10 max-h-[400px] overflow-y-auto">
                                    <div class="grid grid-cols-12 gap-4">
                                        @foreach ($permissions as $p)
                                            <div class="col-span-12 md:col-span-6">
                                                <div class="flex items-center">
                                                    <input class="ti-form-checkbox rounded-sm js-edit-perm" type="checkbox"
                                                        name="permissions[]" value="{{ $p['name'] }}"
                                                        id="edit-perm-{{ $loop->index }}">
                                                    <label class="ms-3 text-sm"
                                                        for="edit-perm-{{ $loop->index }}">{{ $p['label'] }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button" class="ti-btn ti-btn-light"
                            data-hs-overlay="#roleEditModal">{{ __('Cancel') }}</button>
                        <button type="submit" class="ti-btn ti-btn-primary"
                            id="role-edit-submit">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        (function () {
            // Using Preline HS shadowing instead of Bootstrap Modal
            const editModalEl = document.getElementById('roleEditModal');
            if (!editModalEl) return;

            const form = document.getElementById('role-edit-form');
            const nameInput = document.getElementById('edit-role-name');
            const hint = document.getElementById('role-edit-locked-hint');
            const submit = document.getElementById('role-edit-submit');
            const permInputs = Array.from(document.querySelectorAll('.js-edit-perm'));

            function clearPerms() {
                permInputs.forEach(i => {
                    i.checked = false;
                    i.disabled = false;
                });
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

                    // Trigger Preline overlay
                    if (window.HSOverlay) {
                        HSOverlay.open(editModalEl);
                    }
                });
            });
        })();
    </script>
@endpush