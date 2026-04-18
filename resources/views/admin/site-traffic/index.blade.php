@extends('layouts.admin')

@section('title', __('Website traffic'))

@section('content')
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Public website traffic') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <span class="flex items-center text-primary">{{ __('Admin') }}</span>
                        <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                    </li>
                    <li class="text-[12px] text-textmuted">{{ __('Website traffic') }}</li>
                </ol>
            </nav>
            <p class="text-textmuted text-xs mt-2 mb-0 max-w-3xl">
                {{ __('Counts page views on your marketing site (homepage, pricing, blog, lead landings, etc.). Repeated refreshes of the same URL by the same visitor within the dedupe window are stored once. Bots are flagged and can be filtered out.') }}
            </p>
        </div>
    </div>

    @php
        $cards = [
            ['label' => __('Page views'), 'value' => number_format($totalViews), 'hint' => __('In selected range & filters'), 'icon' => 'ri-eye-line', 'color' => 'primary'],
            ['label' => __('Unique IPs'), 'value' => number_format($uniqueIps), 'hint' => __('Approx. distinct visitors'), 'icon' => 'ri-earth-line', 'color' => 'info'],
            ['label' => __('Sessions'), 'value' => number_format($uniqueSessions), 'hint' => __('Distinct session IDs (when available)'), 'icon' => 'ri-fingerprint-line', 'color' => 'success'],
            ['label' => __('Top paths'), 'value' => (string) $topPaths->count(), 'hint' => __('Listed below (max 12)'), 'icon' => 'ri-links-line', 'color' => 'warning'],
        ];
    @endphp

    <div class="grid grid-cols-12 gap-x-6 mb-6">
        @foreach ($cards as $stat)
            <div class="col-span-12 sm:col-span-6 xxl:col-span-3">
                <div class="box shadow-none border border-defaultborder/10">
                    <div class="box-body !p-4">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <p class="text-textmuted text-[11px] font-bold uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                                <h4 class="text-[1.25rem] font-bold mb-0 text-defaulttextcolor">{{ $stat['value'] }}</h4>
                                <p class="text-textmuted text-[10px] mt-1 mb-0">{{ $stat['hint'] }}</p>
                            </div>
                            <div class="ti-avatar ti-avatar-md bg-{{ $stat['color'] }}/10 text-{{ $stat['color'] }} rounded-md p-2">
                                <i class="{{ $stat['icon'] }} text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-12 gap-x-6 mb-6">
        <div class="col-span-12 xxl:col-span-5">
            <div class="box shadow-none border border-defaultborder/10 h-full">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ __('Top paths') }}</h4>
                    <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Most opened URLs (path only) in this range.') }}</p>
                </div>
                <div class="box-body !p-0">
                    @if ($topPaths->isEmpty())
                        <div class="p-8 text-center text-textmuted text-sm">{{ __('No visits recorded for this range yet.') }}</div>
                    @else
                        <div class="table-responsive">
                            <table class="ti-custom-table table-hover text-nowrap w-full mb-0">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th class="!py-3 !px-4">{{ __('Path') }}</th>
                                        <th class="!py-3 !px-4 text-end">{{ __('Views') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topPaths as $row)
                                        <tr class="border-b last:border-0">
                                            <td class="!px-4 text-sm max-w-[280px] truncate" title="/{{ $row->path }}">/{{ $row->path }}</td>
                                            <td class="!px-4 text-end font-semibold">{{ number_format((int) $row->visits) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-span-12 xxl:col-span-7">
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10">
                    <div class="flex flex-wrap justify-between items-end gap-4">
                        <div>
                            <h4 class="box-title font-semibold">{{ __('Visit log') }}</h4>
                            <p class="text-textmuted text-xs mt-1 mb-0">{{ __('Recent requests with IP, referrer, and agent summary.') }}</p>
                        </div>
                        <form method="get" action="{{ route('admin.site-traffic.index') }}" class="flex flex-wrap items-end gap-3">
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="from">{{ __('From') }}</label>
                                <input type="date" id="from" name="from" value="{{ $from }}"
                                       class="ti-form-input !py-2 !px-3 !text-sm w-[160px]">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="to">{{ __('To') }}</label>
                                <input type="date" id="to" name="to" value="{{ $to }}"
                                       class="ti-form-input !py-2 !px-3 !text-sm w-[160px]">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="path">{{ __('Path contains') }}</label>
                                <input type="search" id="path" name="path" value="{{ $pathFilter }}"
                                       class="ti-form-input !py-2 !px-3 !text-sm w-[180px]"
                                       placeholder="{{ __('e.g. pricing') }}">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-textmuted" for="bots">{{ __('Bots') }}</label>
                                <select id="bots" name="bots" class="ti-form-select !py-2 !px-3 !text-sm w-[140px]">
                                    <option value="exclude" @selected($bots === 'exclude')>{{ __('Hide bots') }}</option>
                                    <option value="all" @selected($bots === 'all')>{{ __('All traffic') }}</option>
                                    <option value="only" @selected($bots === 'only')>{{ __('Bots only') }}</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="ti-btn ti-btn-primary font-medium !mb-0 shadow-sm">{{ __('Apply') }}</button>
                                <a href="{{ route('admin.site-traffic.index') }}" class="ti-btn ti-btn-light font-medium !mb-0">{{ __('Reset') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body !p-0">
                    @if ($visits->isEmpty())
                        <div class="p-12 text-center text-textmuted">{{ __('No rows match your filters.') }}</div>
                    @else
                        <div class="table-responsive p-0">
                            <table class="ti-custom-table table-hover text-nowrap w-full mb-0">
                                <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                    <tr>
                                        <th class="!py-3 !px-4">{{ __('Time') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Path') }}</th>
                                        <th class="!py-3 !px-4">{{ __('IP') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Referrer') }}</th>
                                        <th class="!py-3 !px-4">{{ __('Agent') }}</th>
                                        <th class="!py-3 !px-4 text-center">{{ __('Bot') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($visits as $v)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/20 align-top">
                                            <td class="!px-4 text-[12px] text-textmuted whitespace-nowrap">{{ $v->created_at?->timezone(config('app.timezone'))->format('M j, Y H:i') }}</td>
                                            <td class="!px-4 text-sm">
                                                <span class="font-medium">/{{ $v->path }}</span>
                                                @if ($v->query_string)
                                                    <span class="text-textmuted text-[11px] block truncate max-w-[220px]" title="?{{ $v->query_string }}">?{{ \Illuminate\Support\Str::limit($v->query_string, 48) }}</span>
                                                @endif
                                                @if ($v->route_name)
                                                    <span class="text-[10px] text-primary block">{{ $v->route_name }}</span>
                                                @endif
                                            </td>
                                            <td class="!px-4 text-[12px] font-mono">{{ $v->ip_address }}</td>
                                            <td class="!px-4 text-[11px] text-textmuted max-w-[200px]">
                                                @if ($v->referer)
                                                    <span class="line-clamp-2" title="{{ $v->referer }}">{{ \Illuminate\Support\Str::limit($v->referer, 80) }}</span>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="!px-4 text-[11px] text-textmuted max-w-[200px]">
                                                <span class="line-clamp-2" title="{{ $v->user_agent }}">{{ \Illuminate\Support\Str::limit($v->user_agent ?? '—', 70) }}</span>
                                            </td>
                                            <td class="!px-4 text-center">
                                                @if ($v->is_bot)
                                                    <span class="badge bg-warning/10 text-warning border border-warning/20 text-[10px]">{{ __('Yes') }}</span>
                                                @else
                                                    <span class="badge bg-success/10 text-success border border-success/20 text-[10px]">{{ __('No') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3 border-t border-defaultborder/10">
                            {{ $visits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
