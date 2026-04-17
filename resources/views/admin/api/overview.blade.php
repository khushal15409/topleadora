@extends('layouts.admin')

@section('title', 'API Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('API Performance') }}</h5>
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
                            {{ __('Overview') }}
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
                <a href="{{ route('dashboard.api.keys.index') }}" class="ti-btn ti-btn-primary-full text-white !mb-0">
                    <i class="ri-key-2-line me-1"></i> API Keys
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-12 xl:col-span-12 col-span-12">
            <div class="grid grid-cols-12 gap-x-6">
                <!-- Card 1: Total Calls -->
                <div class="xl:col-span-3 sm:col-span-6 col-span-12">
                    <div class="box overflow-hidden shadow-none border border-defaultborder/10">
                        <div class="box-body !p-0">
                            <div class="flex items-start justify-between p-5 !pb-2">
                                <div>
                                    <h6 class="font-medium mb-2 text-[0.9rem] text-textmuted uppercase tracking-wider">{{ __('Total API Calls') }}</h6>
                                    <div class="flex items-center text-nowrap">
                                        <div class="me-3">
                                            <span class="avatar !rounded-full bg-primary-gradient !text-white text-xl p-3 shadow-lg">
                                                <i class="ri-pulse-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-2xl font-bold mb-0">{{ number_format($stats['total_calls']) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 !pt-3 pb-4">
                                <p class="mb-0 text-[11px] text-textmuted">{{ __('Overall lifetime API usage') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Success Rate -->
                <div class="xl:col-span-3 sm:col-span-6 col-span-12">
                    <div class="box overflow-hidden shadow-none border border-defaultborder/10">
                        <div class="box-body !p-0">
                            <div class="flex items-start justify-between p-5 !pb-2">
                                <div>
                                    <h6 class="font-medium mb-2 text-[0.9rem] text-textmuted uppercase tracking-wider">{{ __('Success Rate') }}</h6>
                                    <div class="flex items-center text-nowrap">
                                        <div class="me-3">
                                            <span class="avatar !rounded-full bg-success-gradient !text-white text-xl p-3 shadow-lg">
                                                <i class="ri-check-double-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-2xl font-bold mb-0">{{ $stats['success_rate'] }}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 !pt-3 pb-4">
                                <p class="mb-0 text-[11px] text-success font-semibold">{{ __('Delivered messages') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Failed Calls -->
                <div class="xl:col-span-3 sm:col-span-6 col-span-12">
                    <div class="box overflow-hidden shadow-none border border-defaultborder/10">
                        <div class="box-body !p-0">
                            <div class="flex items-start justify-between p-5 !pb-2">
                                <div>
                                    <h6 class="font-medium mb-2 text-[0.9rem] text-textmuted uppercase tracking-wider">{{ __('Failed Requests') }}</h6>
                                    <div class="flex items-center text-nowrap">
                                        <div class="me-3">
                                            <span class="avatar !rounded-full bg-danger-gradient !text-white text-xl p-3 shadow-lg">
                                                <i class="ri-error-warning-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-2xl font-bold mb-0">{{ number_format($stats['failed_calls']) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 !pt-3 pb-4">
                                <p class="mb-0 text-[11px] text-danger">{{ __('Errors & un-deliverables') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Wallet Balance -->
                <div class="xl:col-span-3 sm:col-span-6 col-span-12">
                    <div class="box overflow-hidden shadow-none border border-defaultborder/10">
                        <div class="box-body !p-0">
                            <div class="flex items-start justify-between p-5 !pb-2">
                                <div>
                                    <h6 class="font-medium mb-2 text-[0.9rem] text-textmuted uppercase tracking-wider">{{ __('Wallet Balance') }}</h6>
                                    <div class="flex items-center text-nowrap">
                                        <div class="me-3">
                                            <span class="avatar !rounded-full bg-info-gradient !text-white text-xl p-3 shadow-lg">
                                                <i class="ri-wallet-3-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-2xl font-bold mb-0">{{ money_local((float) $stats['wallet_balance'], 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 !pt-3 pb-4">
                                <p class="mb-0 text-[11px] text-info font-semibold">{{ __('Current available funds') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="box shadow-none">
                <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                    <div>
                        <h4 class="box-title font-semibold">{{ __('Recent API Activity') }}</h4>
                        <p class="text-textmuted text-[0.7rem] mt-1 mb-0">{{ __('Latest execution logs from all active tokens.') }}</p>
                    </div>
                    <a href="{{ route('dashboard.api.logs') }}" class="ti-btn ti-btn-light btn-sm !text-[11px] border border-defaultborder">{{ __('View All Logs') }}</a>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-100/50 dark:bg-black/20">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Type') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Phone') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider text-end">{{ __('Date & Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_logs'] as $log)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4">
                                            <span class="badge {{ $log->type == 'otp' ? 'bg-primary/10 text-primary' : 'bg-success/10 text-success' }} !text-[10px] px-2 py-1">
                                                {{ strtoupper($log->type) }}
                                            </span>
                                        </td>
                                        <td class="font-bold !px-4 text-sm">{{ $log->phone }}</td>
                                        <td class="!px-4">
                                            @if($log->status == 'success')
                                                <span class="badge bg-success/10 text-success rounded-full px-3 text-[10px]"><i class="ri-check-line me-1"></i>{{ __('Delivered') }}</span>
                                            @else
                                                <span class="badge bg-danger/10 text-danger rounded-full px-3 text-[10px]"><i class="ri-error-warning-line me-1"></i>{{ __('Failed') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end !px-4 text-textmuted text-[12px]">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-textmuted py-12">{{ __('No API usage recorded yet.') }}</td>
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