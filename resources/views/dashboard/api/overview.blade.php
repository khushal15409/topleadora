@extends('layouts.admin')

@section('title', 'API Overview')

@section('content')
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('API Service Overview') }}
            </h5>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xl:col-span-4 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md rounded-full bg-primary text-white">
                                <i class="ri-pulse-line"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-textmuted mb-1">{{ __('Total Requests') }}</p>
                            <h4 class="font-semibold mb-0">{{ number_format($stats['total_calls']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-4 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md rounded-full bg-success text-white">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-textmuted mb-1">{{ __('Success Rate') }}</p>
                            <h4 class="font-semibold mb-0">{{ $stats['success_rate'] }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-4 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md rounded-full bg-warning text-white">
                                <i class="ri-wallet-line"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-textmuted mb-1">{{ __('Wallet Balance') }}</p>
                            <h4 class="font-semibold mb-0">{{ number_format($stats['wallet_balance'], 2) }}
                                {{ __('Credits') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">{{ __('Recent API Activity') }}</h4>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table text-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('Timestamp') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Message') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_logs'] as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $log->type == 'otp' ? 'bg-info/10 text-info' : 'bg-success/10 text-success' }}">
                                                {{ strtoupper($log->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->phone }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $log->status == 'success' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td class="truncate max-w-xs">{{ $log->message }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">{{ __('No activity recorded yet.') }}</td>
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