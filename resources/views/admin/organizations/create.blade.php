@extends('layouts.admin')

@section('title', 'Add organization')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Add Organization') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="{{ route('admin.organizations.index') }}">
                            {{ __('Organizations') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Add New') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('admin.organizations.index') }}" class="ti-btn ti-btn-light font-medium">
                <i class="ri-arrow-left-line me-1"></i>
                {{ __('Back to List') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 lg:col-span-10 xl:col-span-8">
            <div class="box">
                <div class="box-header border-b">
                    <h5 class="box-title font-semibold">{{ __('Organization Details') }}</h5>
                    <p class="text-textmuted text-xs mt-1">{{ __('Create a tenant workspace. Assign a plan to skip trial, or leave empty for a 7-day trial.') }}</p>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ route('admin.organizations.store') }}">
                        @csrf
                        @include('admin.organizations._form', ['organization' => null, 'plans' => $plans])

                        <div class="mt-8 flex gap-3">
                            <button type="submit" class="ti-btn ti-btn-primary px-8">
                                {{ __('Create Organization') }}
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
