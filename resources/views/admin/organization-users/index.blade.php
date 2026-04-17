@extends('layouts.admin')

@section('title', 'Organization Users')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Organization Users') }}</h5>
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
                            {{ __('Users') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <div class="pe-1 xl:mb-0">
                <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon me-2 !mb-0" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            {{-- Filter Box --}}
            <div class="box shadow-none border border-defaultborder/10 mb-6">
                <div class="box-body">
                    <form method="get" action="{{ route('admin.users.index') }}" class="grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="filter-org">{{ __('Organization') }}</label>
                            <select name="organization_id" id="filter-org" class="ti-form-select !py-2 !px-3">
                                <option value="">{{ __('All organizations') }}</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}" @selected((string) $organizationId === (string) $org->id)>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="filter-role">{{ __('Role') }}</label>
                            <select name="role" id="filter-role" class="ti-form-select !py-2 !px-3">
                                <option value="">{{ __('All roles') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected((string) $roleFilter === (string) $role->name)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-5 flex flex-wrap gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary-full font-medium !mb-0">
                                <i class="ri-filter-3-line me-1"></i>
                                {{ __('Apply filters') }}
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="ti-btn ti-btn-light font-medium !mb-0">{{ __('Reset') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ __('All Tenant Accounts') }}</h4>
                    <p class="text-textmuted text-xs mt-1">
                        {{ __('Excluding platform Super Admin. Manage user access across all organization workspaces.') }}
                    </p>
                </div>
                <div class="box-body !p-0">
                    @if ($users->isEmpty())
                        <div class="p-12 text-center">
                            <div class="avatar avatar-xl bg-gray-100 text-textmuted rounded-full mb-3 mx-auto shadow-none">
                                <i class="ri-user-unfollow-line text-2xl"></i>
                            </div>
                            <h6 class="font-bold text-[14px] mb-1">{{ __('No users found') }}</h6>
                            <p class="text-textmuted text-[12px] mb-0">{{ __('No users match the current filtrations.') }}</p>
                        </div>
                    @else
                        <div class="table-responsive p-4">
                            <table id="dt-org-users" class="ti-custom-table table-hover text-nowrap w-full datatable" data-disable-last-sort="1">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('User info') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Organization') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Roles') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Joined') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $u)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                            <td class="!px-4">
                                                <div class="flex items-center">
                                                    <span class="avatar avatar-sm bg-primary/10 text-primary rounded-full me-3">
                                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    </span>
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-sm text-defaulttextcolor">{{ $u->name }}</span>
                                                        <span class="text-[11px] text-textmuted">{{ $u->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="!px-4">
                                                <span class="text-sm font-medium">{{ $u->organization?->name ?? '—' }}</span>
                                            </td>
                                            <td class="!px-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @forelse ($u->roles as $role)
                                                        <span class="badge bg-primary/10 text-primary rounded-full px-2 py-1 text-[10px] border border-primary/20">{{ $role->name }}</span>
                                                    @empty
                                                        <span class="text-textmuted text-xs">—</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="!px-4">
                                                @if ($u->status === \App\Models\User::STATUS_ACTIVE)
                                                    <span class="badge bg-success/10 text-success rounded-full px-2 py-1 text-[10px] border border-success/20">
                                                        <i class="ri-checkbox-circle-line me-1"></i>{{ __('Active') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger/10 text-danger rounded-full px-2 py-1 text-[10px] border border-danger/20">
                                                        <i class="ri-close-circle-line me-1"></i>{{ __('Inactive') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="!px-4 text-sm text-textmuted" data-order="{{ $u->created_at?->timestamp ?? 0 }}">
                                                {{ $u->created_at?->format('M j, Y') ?? '—' }}
                                            </td>
                                            <td class="text-end !px-4">
                                                <a href="{{ route('admin.users.show', $u) }}" class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0 p-2" title="{{ __('View Details') }}">
                                                    <i class="ri-eye-line text-lg"></i>
                                                </a>
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
