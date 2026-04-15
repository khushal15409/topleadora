@extends('layouts.admin')

@section('title', 'API Settings')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Configuration') }}</h5>
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
                            {{ __('Settings') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex xl:my-auto right-content align-items-center">
            <div class="pe-1 xl:mb-0">
                <a href="{{ route('dashboard.api.keys.index') }}" class="ti-btn ti-btn-primary-full text-white !mb-0">
                    <i class="ri-shield-keyhole-line me-1"></i> API Security
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    @if(session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-6 flex justify-between items-center" role="alert">
            <div class="flex items-center gap-2">
                <i class="ri-checkbox-circle-fill text-lg"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-success hover:text-success/80" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box shadow-none">
                <div class="box-header !border-b !border-defaultborder/10 flex items-center gap-3">
                    <div class="avatar avatar-sm rounded-sm bg-primary/10 text-primary">
                        <i class="ri-settings-4-line text-[16px]"></i>
                    </div>
                    <div>
                        <h4 class="box-title font-semibold">{{ __('API Configuration') }}</h4>
                        <p class="text-textmuted text-[0.7rem] mt-1 mb-0">{{ __('Manage global webhooks and network security restrictions.') }}</p>
                    </div>
                </div>
                
                <div class="box-body md:p-8">
                    <form action="{{ route('dashboard.api.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Webhook URL Configuration -->
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <i class="ri-global-line text-textmuted text-[18px] me-2"></i>
                                <h6 class="font-semibold text-[14px] mb-0">{{ __('Delivery Webhook URL') }}</h6>
                            </div>
                            <div class="ms-md-6 border-s border-defaultborder/50 ps-4">
                                <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="webhook_url">{{ __('Endpoint URL') }}</label>
                                <input type="url" 
                                    class="form-control bg-light border-0 shadow-none text-[13px] @error('webhook_url') !border-danger @enderror" 
                                    id="webhook_url" name="webhook_url" 
                                    value="{{ old('webhook_url', $organization->webhook_url) }}" 
                                    placeholder="https://yourserver.com/api/webhook"
                                    autocomplete="url">
                                
                                @error('webhook_url')
                                    <p class="text-danger text-[12px] mt-2 font-semibold"><i class="ri-error-warning-line me-1"></i> {{ $message }}</p>
                                @else
                                    <p class="text-textmuted text-[11px] mt-2 mb-0">{{ __('We will send Delivery Reports (DLR) as JSON POST requests to this URL whenever a message status updates.') }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="border-defaultborder/10 my-6 border-dashed">

                        <!-- Network Security Configuration -->
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <i class="ri-shield-lock-line text-textmuted text-[18px] me-2"></i>
                                <h6 class="font-semibold text-[14px] mb-0">{{ __('Network Security (IP Whitelisting)') }}</h6>
                            </div>
                            <div class="ms-md-6 border-s border-defaultborder/50 ps-4">
                                <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="allowed_ips">{{ __('Allowed IP Addresses') }}</label>
                                
                                @php
                                    $ips = $organization->allowed_ips ? implode(', ', json_decode($organization->allowed_ips)) : '';
                                @endphp
                                
                                <textarea 
                                    class="form-control bg-light border-0 shadow-none font-mono text-[13px] leading-relaxed @error('allowed_ips') !border-danger @enderror" 
                                    id="allowed_ips" name="allowed_ips" rows="4" 
                                    placeholder="192.168.1.1, 10.0.0.1, 172.16.0.0/12">{{ old('allowed_ips', $ips) }}</textarea>
                                
                                @error('allowed_ips')
                                    <p class="text-danger text-[12px] mt-2 font-semibold"><i class="ri-error-warning-line me-1"></i> {{ $message }}</p>
                                @else
                                    <p class="text-textmuted text-[11px] mt-2 mb-0">{!! __('Comma-separated list of IPv4 addresses or Subnets. If left completely blank, requests from <strong class="text-defaulttextcolor">ANY</strong> IP address holding a valid Bearer Token will be permitted.') !!}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end pt-4 mt-6 border-t border-defaultborder/10">
                            <button type="submit" class="ti-btn ti-btn-primary-full px-6 !mb-0">
                                <i class="ri-save-3-line me-1"></i> {{ __('Save Configuration') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection