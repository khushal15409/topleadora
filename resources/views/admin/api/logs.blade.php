@extends('layouts.admin')

@section('title', 'API Logs')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Usage Logs') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="{{ route('dashboard.api.overview') }}">
                            {{ __('API Client') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Usage Logs') }}
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
            <div class="xl:mb-0">
                <a href="{{ route('dashboard.api.wallet') }}" class="ti-btn ti-btn-primary-full text-white !mb-0">
                    <i class="ri-wallet-3-line me-1"></i> View Wallet
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <!-- Filters -->
        <div class="col-span-12">
            <div class="box shadow-none">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold"><i class="ri-filter-3-line me-1 text-textmuted"></i> {{ __('Filter Logs') }}</h4>
                </div>
                <div class="box-body">
                    <form action="{{ route('dashboard.api.logs') }}" method="GET" class="grid grid-cols-12 gap-4 items-end">
                        <div class="xl:col-span-3 lg:col-span-4 col-span-12">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" class="form-control text-sm bg-light" placeholder="e.g. +1234567" value="{{ request('phone') }}">
                        </div>
                        <div class="xl:col-span-2 lg:col-span-3 col-span-12">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('Event Type') }}</label>
                            <select name="type" class="form-control text-sm bg-light">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="otp" {{ request('type') == 'otp' ? 'selected' : '' }}>{{ __('OTP Message') }}</option>
                                <option value="whatsapp" {{ request('type') == 'whatsapp' ? 'selected' : '' }}>{{ __('WhatsApp') }}</option>
                            </select>
                        </div>
                        <div class="xl:col-span-2 lg:col-span-3 col-span-12">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('Status') }}</label>
                            <select name="status" class="form-control text-sm bg-light">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>{{ __('Delivered (Success)') }}</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            </select>
                        </div>
                        <div class="xl:col-span-3 lg:col-span-3 col-span-12">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider">{{ __('Execution Date') }}</label>
                            <input type="date" name="date" class="form-control text-sm bg-light" value="{{ request('date') }}">
                        </div>
                        <div class="xl:col-span-2 lg:col-span-12 col-span-12 flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary-full flex-grow !mb-0">{{ __('Search') }}</button>
                            @if(request()->anyFilled(['phone', 'type', 'status', 'date']))
                                <a href="{{ route('dashboard.api.logs') }}" class="ti-btn ti-btn-light border !mb-0 px-3 flex-shrink-0" title="Clear Filters">
                                    <i class="ri-close-line"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-span-12">
            <div class="box shadow-none overflow-hidden">
                <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                    <div>
                        <h4 class="box-title font-semibold">{{ __('API Request History') }}</h4>
                        <p class="text-textmuted text-[0.7rem] mt-1 mb-0">{{ __('Detailed view of all programmatic executions.') }}</p>
                    </div>
                    <span class="badge bg-light text-textmuted border border-defaultborder px-3 py-1 text-[11px]">
                        {{ __('Total:') }} {{ number_format($logs->total()) }} {{ __('Records') }}
                    </span>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Type') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Recipient') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Payload Snippet') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Delivery Status') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider text-end">{{ __('Processed Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4">
                                            <div class="flex items-center gap-2">
                                                @if($log->type == 'otp')
                                                    <div class="avatar avatar-xs rounded-sm bg-primary/10 text-primary">
                                                        <i class="ri-shield-keyhole-line text-[12px]"></i>
                                                    </div>
                                                @else
                                                    <div class="avatar avatar-xs rounded-sm bg-success/10 text-success">
                                                        <i class="ri-whatsapp-line text-[12px]"></i>
                                                    </div>
                                                @endif
                                                <span class="font-bold text-[12px] text-defaulttextcolor uppercase">{{ $log->type }}</span>
                                            </div>
                                        </td>
                                        <td class="!px-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-sm">{{ $log->phone }}</span>
                                                <span class="text-textmuted text-[10px] font-mono">ID: {{ substr(md5($log->id), 0, 8) }}</span>
                                            </div>
                                        </td>
                                        <td class="!px-4">
                                            <div class="truncate text-textmuted text-[12px]" style="max-width: 250px;" title="{{ $log->message }}">
                                                "{{ Str::limit($log->message, 35) }}"
                                            </div>
                                        </td>
                                        <td class="!px-4">
                                            @if($log->status == 'success')
                                                <span class="badge bg-success/10 text-success rounded-full px-3 text-[10px] border border-success/20">
                                                    <i class="ri-check-line me-1"></i> {{ __('Delivered') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger/10 text-danger rounded-full px-3 text-[10px] border border-danger/20" title="{{ $log->response }}">
                                                    <i class="ri-error-warning-line me-1"></i> {{ __('Failed') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end !px-4 text-textmuted text-[12px]">
                                            <span class="block">{{ $log->created_at->format('M d, Y') }}</span>
                                            <span class="block text-[10px] mt-[1px]">{{ $log->created_at->format('h:i A') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-textmuted py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="avatar avatar-lg bg-light text-textmuted rounded-full mb-3 shadow-none border">
                                                    <i class="ri-file-search-line text-2xl"></i>
                                                </div>
                                                <h6 class="font-bold text-[14px] mb-1">{{ __('No execution logs found.') }}</h6>
                                                <p class="text-[12px] mb-0">{{ __('Try adjusting your filters or send a test API payload.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($logs->hasPages())
                    <div class="box-footer p-4 border-t border-defaultborder/10">
                        {{ $logs->links('pagination::bootstrap-5') }} <!-- Keeping standard pagination style if configured, otherwise default tailwind -->
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection