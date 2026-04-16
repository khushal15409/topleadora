@extends('layouts.admin')

@section('title', 'Edit organization')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Edit Organization') }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary"
                            href="{{ route('admin.organizations.index') }}">
                            {{ __('Organizations') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ $organization->slug }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center gap-2">
            <a href="{{ route('admin.organizations.index') }}" class="ti-btn ti-btn-light font-medium">
                <i class="ri-arrow-left-line me-1"></i>
                {{ __('Back to List') }}
            </a>
            @if ($organization->users_count === 0)
                <form action="{{ route('admin.organizations.destroy', $organization) }}" method="post" class="inline"
                    data-confirm="{{ __('Delete this organization? Users must be detached first.') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ti-btn ti-btn-danger font-medium">
                        <i class="ri-delete-bin-line me-1"></i>
                        {{ __('Delete') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 lg:col-span-10 xl:col-span-8">
            @if (session('success'))
                <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center"
                    role="alert">
                    {{ session('success') }}
                    <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif

            <div class="box">
                <div class="box-header border-b">
                    <h5 class="box-title font-semibold">{{ __('Workspace Settings') }}</h5>
                    <p class="text-textmuted text-xs mt-1">
                        {{ __('Update organization details, subscription plan, and trial settings.') }}</p>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ route('admin.organizations.update', $organization) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.organizations._form', ['organization' => $organization, 'plans' => $plans])

                        <div class="mt-8 flex gap-3 border-t pt-6 border-defaultborder/10">
                            <button type="submit" class="ti-btn ti-btn-primary px-8">
                                {{ __('Save Changes') }}
                            </button>
                            <a href="{{ route('admin.organizations.index') }}" class="ti-btn ti-btn-light">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection