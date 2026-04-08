@extends('layouts.admin')

@section('title', __('Marketing Leads'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Marketing Leads') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Marketing') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Leads') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('admin.marketing.leads.export', request()->query()) }}"
                class="ti-btn ti-btn-primary font-medium">
                <i class="ri-file-download-line me-1"></i>{{ __('Export CSV') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <!-- Filter Form -->
        <div class="col-span-12 mb-6">
            <div class="box">
                <div class="box-body">
                    <form method="get" action="{{ request()->url() }}">
                        <div class="grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-12 md:col-span-3 lg:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase text-textmuted mb-2">{{ __('Service') }}</label>
                                <select name="service_id" class="ti-form-select ti-form-select-sm">
                                    <option value="">{{ __('All services') }}</option>
                                    @foreach ($services as $s)
                                        <option value="{{ $s->id }}" @selected(request('service_id') == $s->id)>{{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-3 lg:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase text-textmuted mb-2">{{ __('From date') }}</label>
                                <input type="date" name="from" class="ti-form-input ti-form-input-sm"
                                    value="{{ request('from') }}">
                            </div>
                            <div class="col-span-12 md:col-span-3 lg:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase text-textmuted mb-2">{{ __('To date') }}</label>
                                <input type="date" name="to" class="ti-form-input ti-form-input-sm"
                                    value="{{ request('to') }}">
                            </div>
                            <div class="col-span-12 md:col-span-6 lg:col-span-4">
                                <label
                                    class="block text-xs font-bold uppercase text-textmuted mb-2">{{ __('Search') }}</label>
                                <input type="text" name="q" class="ti-form-input ti-form-input-sm"
                                    value="{{ request('q') }}" placeholder="{{ __('Name, email, phone, city…') }}">
                            </div>
                            <div class="col-span-12 md:col-span-3 lg:col-span-2">
                                <button type="submit" class="ti-btn ti-btn-primary w-full !mb-0">
                                    <i class="ri-filter-line me-1"></i>{{ __('Apply Filters') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="col-span-12">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Submissions') }}</h4>
                    <p class="text-textmuted text-xs mt-1">
                        {{ __('Leads captured from public landing pages and contact forms.') }}</p>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-50 border-y dark:bg-black/10">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Name') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Contact Info') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Service') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Location') }}</th>
                                    <th scope="col" class="!py-3 !px-4">{{ __('Date') }}</th>
                                    <th scope="col" class="!py-3 !px-4 d-none d-xl-table-cell">{{ __('Source / UTM') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leads as $lead)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4 font-medium">
                                            {{ $lead->name }}
                                        </td>
                                        <td class="!px-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium">{{ $lead->phone }}</span>
                                                <span class="text-xs text-textmuted">{{ $lead->email ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td class="!px-4 text-sm">
                                            {{ $lead->service?->name ?? '—' }}
                                        </td>
                                        <td class="!px-4 text-sm">
                                            <div class="flex flex-col">
                                                <span>{{ $lead->country_name }}</span>
                                                <span class="text-xs text-textmuted">{{ $lead->city ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td class="!px-4 text-sm text-textmuted">
                                            {{ $lead->created_at?->format('M j, Y') }}<br>
                                            <small>{{ $lead->created_at?->format('H:i') }}</small>
                                        </td>
                                        <td class="!px-4 d-none d-xl-table-cell">
                                            <code
                                                class="text-[10px] bg-gray-100 p-1 rounded">{{ $lead->source_page ?? '—' }}</code>
                                            @if ($lead->utm_source || $lead->utm_medium || $lead->utm_campaign)
                                                <div class="text-[10px] text-textmuted mt-1">
                                                    {{ $lead->utm_source }} / {{ $lead->utm_medium }} · {{ $lead->utm_campaign }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-textmuted py-12">
                                            {{ __('No marketing leads yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($leads->hasPages())
                    <div class="box-footer p-4 border-t">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection