@extends('layouts.admin')

@section('title', __('Landing Pages'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Landing Pages') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Marketing') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Landing Pages') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('admin.marketing.landing-pages.create') }}" class="ti-btn ti-btn-primary font-medium">
                <i class="ri-add-line me-1"></i>{{ __('Add landing page') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
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
                    <h4 class="box-title font-semibold">{{ __('All Pages') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('SEO, slug, and page content for /leads/{slug}') }}</p>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-50 border-y dark:bg-black/10">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Slug') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('City') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Service') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Country') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Active') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pages as $p)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4">
                                            <code class="text-primary text-xs bg-primary/10 px-2 py-1 rounded">{{ $p->slug }}</code>
                                        </td>
                                        <td class="!px-4 font-medium">{{ $p->city_label ?: '—' }}</td>
                                        <td class="!px-4 text-sm">{{ $p->service?->name }}</td>
                                        <td class="!px-4 text-sm">{{ $p->country?->name }}</td>
                                        <td class="!px-4">
                                            @if ($p->is_active)
                                                <span class="badge bg-success/10 text-success rounded-full">{{ __('Yes') }}</span>
                                            @else
                                                <span class="badge bg-gray-100 text-gray-500 rounded-full">{{ __('No') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end !px-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.marketing.landing-pages.edit', $p) }}" class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0 p-2" title="{{ __('Edit') }}">
                                                    <i class="ri-pencil-line text-lg"></i>
                                                </a>
                                                <form action="{{ route('admin.marketing.landing-pages.destroy', $p) }}" method="post" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-danger !border-0 p-2" title="{{ __('Delete') }}">
                                                        <i class="ri-delete-bin-line text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-textmuted py-12">{{ __('No landing pages.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
