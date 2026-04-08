@extends('layouts.auth')

@section('meta_title', 'Create account | ' . config('app.name', 'WhatsAppLeadCRM'))

@section('content')
    <div
        class="grid grid-cols-12 gap-x-6 w-full max-w-[1000px] bg-white rounded-xl shadow-xl overflow-hidden mx-auto my-auto min-h-[700px] dark:bg-gray-800">
        <!-- Left Section (Branding/Illustration) -->
        <div
            class="col-span-12 lg:col-span-12 xl:col-span-5 bg-primary/10 relative overflow-hidden hidden xl:flex items-center justify-center p-12">
            <div class="absolute top-0 start-0 w-full h-full opacity-10">
                <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                    <rect x="10" y="10" width="30" height="30" rx="4" />
                    <rect x="60" y="50" width="20" height="20" rx="4" />
                </svg>
            </div>
            <div class="relative z-10 text-center">
                <div class="mb-8">
                    <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}"
                        class="mx-auto rounded-lg shadow-sm w-48 bg-white p-4">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
                    Start your workspace in minutes
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto leading-relaxed text-sm">
                    Connect your team, organize WhatsApp leads, and keep every follow-up on track.
                </p>
                <div class="space-y-6 text-start max-w-xs mx-auto">
                    <div class="flex items-start">
                        <span class="avatar avatar-md bg-primary text-white rounded-lg me-4 shrink-0 shadow-sm"><i
                                class="ri-flashlight-line text-lg"></i></span>
                        <div>
                            <h6 class="font-bold text-gray-800 dark:text-white mb-1 text-sm">Quick Setup</h6>
                            <p class="text-xs text-gray-500 dark:text-gray-400">A familiar CRM flow built for speed.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="avatar avatar-md bg-primary text-white rounded-lg me-4 shrink-0 shadow-sm"><i
                                class="ri-shield-user-line text-lg"></i></span>
                        <div>
                            <h6 class="font-bold text-gray-800 dark:text-white mb-1 text-sm">Secure Sign-in</h6>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Enterprise-grade security and handling.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="avatar avatar-md bg-primary text-white rounded-lg me-4 shrink-0 shadow-sm"><i
                                class="ri-line-chart-line text-lg"></i></span>
                        <div>
                            <h6 class="font-bold text-gray-800 dark:text-white mb-1 text-sm">Built for Growth</h6>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Designed for conversion-focused teams.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section (Form) -->
        <div class="col-span-12 xl:col-span-7 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <div class="text-center lg:text-start mb-8">
                    <div class="xl:hidden mb-8 flex justify-center">
                        <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10">
                    </div>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Create Account</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Join the platform to manage your leads effectively
                    </p>
                </div>

                <form method="post" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-12 gap-x-4 gap-y-4">
                        <div class="col-span-12">
                            <label for="register-organization-name"
                                class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Organization
                                Name</label>
                            <input type="text" name="organization_name" id="register-organization-name"
                                class="ti-form-input !py-3 w-full @error('organization_name') !border-danger @enderror"
                                placeholder="Your company name" value="{{ old('organization_name') }}" required>
                            @error('organization_name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-12">
                            <label for="register-name"
                                class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Full Name</label>
                            <input type="text" name="name" id="register-name"
                                class="ti-form-input !py-3 w-full @error('name') !border-danger @enderror"
                                placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-12">
                            <label for="register-email"
                                class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Work Email</label>
                            <input type="email" name="email" id="register-email"
                                class="ti-form-input !py-3 w-full @error('email') !border-danger @enderror"
                                placeholder="john@company.com" value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-12 sm:col-span-6">
                            <label for="register-password"
                                class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="register-password"
                                class="ti-form-input !py-3 w-full @error('password') !border-danger @enderror"
                                placeholder="••••••••" required>
                            @error('password')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-12 sm:col-span-6">
                            <label for="register-password-confirmation"
                                class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Confirm
                                Password</label>
                            <input type="password" name="password_confirmation" id="register-password-confirmation"
                                class="ti-form-input !py-3 w-full" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="ti-btn ti-btn-primary w-full !py-3 font-semibold text-sm shadow-md hover:shadow-lg transition-all flex justify-center items-center">
                            {{ __('Create Account') }}
                            <i class="ri-arrow-right-line ms-2"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm">
                    <p class="text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="{{ route('login') }}"
                            class="text-primary font-bold hover:underline">Sign in instead</a>
                    </p>
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ url('/') }}"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 flex items-center justify-center transition-colors">
                            <i class="ri-home-4-line me-2"></i>
                            Back to main website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection