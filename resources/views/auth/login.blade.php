@extends('layouts.auth')

@section('meta_title', 'Sign in | ' . config('app.name', 'WhatsAppLeadCRM'))

@section('content')
    <div
        class="grid grid-cols-12 gap-x-6 w-full max-w-[1000px] bg-white rounded-xl shadow-xl overflow-hidden mx-auto my-auto min-h-[600px] dark:bg-gray-800">
        <!-- Left Section (Branding/Illustration) -->
        <div
            class="col-span-12 lg:col-span-6 bg-primary/10 relative overflow-hidden hidden lg:flex items-center justify-center p-12">
            <div class="absolute top-0 start-0 w-full h-full opacity-10">
                <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                    <circle cx="0" cy="0" r="40" />
                    <circle cx="100" cy="100" r="30" />
                </svg>
            </div>
            <div class="relative z-10 text-center">
                <div class="mb-8">
                    <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}"
                        class="mx-auto rounded-lg shadow-sm w-48 bg-white p-4">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
                    Pipeline clarity for every WhatsApp conversation
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto leading-relaxed">
                    One workspace for leads, stages, and follow-ups—built for teams that close on chat.
                </p>
                <ul class="space-y-4 text-start max-w-xs mx-auto">
                    <li class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="avatar avatar-sm bg-primary/20 text-primary rounded-full me-3"><i
                                class="ri-check-line"></i></span>
                        Shared inbox & clear ownership
                    </li>
                    <li class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="avatar avatar-sm bg-primary/20 text-primary rounded-full me-3"><i
                                class="ri-check-line"></i></span>
                        Stages that match how you sell
                    </li>
                    <li class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="avatar avatar-sm bg-primary/20 text-primary rounded-full me-3"><i
                                class="ri-check-line"></i></span>
                        Fewer dropped leads, faster handoffs
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Section (Form) -->
        <div class="col-span-12 lg:col-span-6 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-sm">
                <div class="text-center lg:text-start mb-10">
                    <div class="lg:hidden mb-12 flex justify-center">
                        <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12">
                    </div>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Welcome Back</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Please sign in to your accounts</p>
                </div>

                <form method="post" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="login-email"
                            class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" id="login-email"
                                class="ti-form-input !py-3 !ps-11 w-full @error('email') !border-danger @enderror"
                                placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                            <div
                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-400">
                                <i class="ri-mail-line text-lg"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="login-password"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Password</label>
                            <a href="javascript:void(0);" class="text-xs text-primary hover:underline font-medium">Forgot
                                password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="login-password"
                                class="ti-form-input !py-3 !ps-11 w-full @error('password') !border-danger @enderror"
                                placeholder="••••••••" required>
                            <div
                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-400">
                                <i class="ri-lock-2-line text-lg"></i>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input class="ti-form-checkbox h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                            type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
                        <label class="ms-2 block text-sm text-gray-600 dark:text-gray-400" for="remember">
                            Remember me for 30 days
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="ti-btn ti-btn-primary w-full !py-3 font-semibold text-sm shadow-md hover:shadow-lg transition-all flex justify-center items-center">
                            {{ __('Sign In') }}
                            <i class="ri-arrow-right-line ms-2"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-12 text-center text-sm">
                    <p class="text-gray-500 dark:text-gray-400">
                        New here? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Create an
                            account</a>
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