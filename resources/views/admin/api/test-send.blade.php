@extends('layouts.admin')

@section('title', __('Test OTP & WhatsApp'))

@section('content')
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Test send') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="{{ route('dashboard.api.overview') }}">
                            {{ __('API Client') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">{{ __('Test OTP & WhatsApp') }}</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box shadow-none mb-6">
                <div class="box-header !border-b !border-defaultborder/10 flex items-center gap-3">
                    <div class="avatar avatar-sm rounded-sm bg-primary/10 text-primary">
                        <i class="ri-send-plane-fill text-[16px]"></i>
                    </div>
                    <div>
                        <h4 class="box-title font-semibold">{{ __('Send test OTP & WhatsApp') }}</h4>
                        <p class="text-textmuted text-[0.7rem] mt-1 mb-0">
                            {{ __('Enter a mobile number and a 4-digit OTP. The same message is sent through the OTP (SMS) channel and WhatsApp, using the same billing and logging as the API.') }}
                        </p>
                    </div>
                </div>
                <div class="box-body md:p-8">
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($whatsappConfigured)
                            <span class="badge bg-success/10 text-success border border-success/20">{{ __('WhatsApp Cloud API: configured') }}</span>
                        @else
                            <span class="badge bg-warning/10 text-warning border border-warning/20">{{ __('WhatsApp Cloud API: not configured — test uses simulation') }}</span>
                        @endif
                        <span class="badge bg-primary/10 text-primary border border-primary/10">{{ __('Wallet') }}: ₹{{ number_format((float) ($organization->wallet_balance ?? 0), 2) }}</span>
                    </div>

                    @if (session('test_send_otp') || session('test_send_whatsapp'))
                        @php
                            $otpS = session('test_send_otp');
                            $waS = session('test_send_whatsapp');
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="border border-defaultborder/20 rounded-md p-4 {{ ($otpS['status'] ?? 0) === 200 && ($otpS['body']['success'] ?? false) ? 'bg-success/5 border-success/20' : 'bg-danger/5 border-danger/20' }}">
                                <h6 class="font-semibold text-sm mb-2">{{ __('OTP (SMS channel)') }}</h6>
                                @if(($otpS['status'] ?? 0) === 200 && ($otpS['body']['success'] ?? false))
                                    <p class="text-success text-sm mb-0">{{ $otpS['body']['message'] ?? __('Sent.') }}</p>
                                @else
                                    <p class="text-danger text-sm mb-0">{{ $otpS['body']['error'] ?? ($otpS['body']['message'] ?? __('Failed.')) }}</p>
                                @endif
                            </div>
                            <div class="border border-defaultborder/20 rounded-md p-4 {{ ($waS['status'] ?? 0) === 200 && ($waS['body']['success'] ?? false) ? 'bg-success/5 border-success/20' : 'bg-danger/5 border-danger/20' }}">
                                <h6 class="font-semibold text-sm mb-2">{{ __('WhatsApp') }}</h6>
                                @if(($waS['status'] ?? 0) === 200 && ($waS['body']['success'] ?? false))
                                    <p class="text-success text-sm mb-0">{{ $waS['body']['message'] ?? __('Sent.') }}</p>
                                @else
                                    <p class="text-danger text-sm mb-0">{{ $waS['body']['error'] ?? ($waS['body']['message'] ?? __('Failed.')) }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.api.test-send.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="phone">{{ __('Mobile number') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="form-control bg-light border-0 shadow-none text-[13px] @error('phone') !border-danger @enderror"
                                placeholder="{{ __('e.g. +9198xxxxxx00') }}" autocomplete="tel" required>
                            @error('phone')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="otp">{{ __('4-digit OTP') }}</label>
                            <input type="text" name="otp" id="otp" value="{{ old('otp') }}" maxlength="4" inputmode="numeric" pattern="[0-9]{4}"
                                class="form-control bg-light border-0 shadow-none text-[13px] max-w-[12rem] tracking-widest @error('otp') !border-danger @enderror"
                                placeholder="0000" required>
                            @error('otp')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="text-textmuted text-[12px] mb-4">
                            {{ __('Charges match the API: OTP channel ₹0.50 and WhatsApp ₹1.00 per attempt. WhatsApp uses Super Admin integration settings when configured.') }}
                        </p>
                        <button type="submit" class="ti-btn ti-btn-primary-full text-white">
                            <i class="ri-send-plane-2-line me-1"></i> {{ __('Send test') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
