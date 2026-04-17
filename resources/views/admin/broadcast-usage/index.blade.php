@extends('layouts.admin')

@section('title', __('Broadcast usage'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Broadcast usage') }}</h5>
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
                            {{ __('Broadcast usage') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10">
                    <div class="flex flex-wrap justify-between items-end gap-4">
                        <div>
                            <h4 class="box-title font-semibold">{{ __('WhatsApp broadcast usage') }}</h4>
                            <p class="text-textmuted text-xs mt-1">{{ __('Broadcast totals per organization for the selected date range.') }}</p>
                        </div>

                        <form method="get" action="{{ route('admin.broadcast-usage.index') }}" class="flex flex-wrap items-end gap-3">
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="q">{{ __('Organization') }}</label>
                                <input type="search" id="q" name="q" value="{{ request('q') }}"
                                       class="ti-form-input !py-2 !px-3 !text-sm w-[220px]"
                                       placeholder="{{ __('Search…') }}">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="from">{{ __('From') }}</label>
                                <input type="date" id="from" name="from" value="{{ request('from') }}"
                                       class="ti-form-input !py-2 !px-3 !text-sm w-[160px]">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="to">{{ __('To') }}</label>
                                <input type="date" id="to" name="to" value="{{ request('to') }}"
                                       class="ti-form-input !py-2 !px-3 !text-sm w-[160px]">
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="ti-btn ti-btn-primary font-medium !mb-0 shadow-sm">{{ __('Apply') }}</button>
                                <a href="{{ route('admin.broadcast-usage.index') }}" class="ti-btn ti-btn-light font-medium !mb-0">{{ __('Reset') }}</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box-body !p-0">
                    @if ($orgs->isEmpty())
                        <div class="p-20 text-center">
                            <p class="text-textmuted mb-2">{{ __('No organizations found.') }}</p>
                            <a href="{{ route('admin.broadcast-usage.index') }}" class="text-primary font-bold decoration-2">{{ __('Clear filters') }}</a>
                        </div>
                    @else
                        <div class="table-responsive p-4">
                            <table class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th class="!py-3 !px-4">{{ __('Organization') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Total broadcasts sent') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Total messages sent') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Last broadcast') }}</th>
                                        <th class="!py-3 !px-4 text-end">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orgs as $org)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/20 transition-colors h-14">
                                            <td class="!px-4 font-bold text-sm text-defaulttextcolor">{{ $org->name }}</td>
                                            <td class="!px-4 text-sm font-medium">{{ number_format((int) ($org->total_broadcasts_sent ?? 0)) }}</td>
                                            <td class="!px-4 text-sm font-medium">{{ number_format((int) ($org->total_messages_sent ?? 0)) }}</td>
                                            <td class="!px-4 text-[12px] text-textmuted" data-order="{{ $org->last_broadcast_at?->timestamp ?? 0 }}">
                                                {{ $org->last_broadcast_at ? \Illuminate\Support\Carbon::parse($org->last_broadcast_at)->format('M j, Y H:i') : '—' }}
                                            </td>
                                            <td class="text-end !px-4">
                                                <a href="{{ route('admin.broadcast-usage.show', $org) }}" class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0">{{ __('View details') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-4 py-3 border-t border-defaultborder/10">
                            {{ $orgs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

