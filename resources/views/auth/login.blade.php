@extends('layouts.auth')

@section('meta_title', 'Sign in | ' . config('app.name', 'WhatsAppLeadCRM'))

@section('content')
    @php
        $asideTitle = 'Pipeline clarity for every WhatsApp conversation';
        $asideText = 'One workspace for leads, stages, and follow-ups—built for teams that close on chat.';
        $asideBullets = [
            ['icon' => 'bi bi-people', 'label' => 'Shared inbox with clear lead ownership.'],
            ['icon' => 'bi bi-diagram-3', 'label' => 'Stages that mirror how your team sells.'],
            ['icon' => 'bi bi-speedometer2', 'label' => 'Faster handoffs and fewer missed follow-ups.'],
        ];
        $asideImage = 'front/images/landify/sections-images/login.png';
    @endphp

    @include('auth.partials.aside-panel', [
        'asideTitle' => $asideTitle,
        'asideText' => $asideText,
        'asideBullets' => $asideBullets,
        'asideImage' => $asideImage,
    ])

    <main class="auth-main">
        <div class="auth-card">
            <div class="auth-mobile-brand d-lg-none text-center mb-4">
                <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}">
            </div>
            <div class="auth-card__eyebrow">Welcome back</div>
            <h1 class="auth-card__title">Sign in to your workspace</h1>
            <p class="auth-card__subtitle">Pick up where you left off and keep your pipeline moving.</p>

            <form method="post" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="mb-3">
                    <label for="login-email" class="form-label">Email address</label>
                    <input type="email" name="email" id="login-email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="login-password" class="form-label mb-0">Password</label>
                    <a href="javascript:void(0);" class="small fw-semibold text-decoration-none">Forgot password?</a>
                </div>
                <div class="auth-password mb-3">
                    <input type="password" name="password" id="login-password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                    <button type="button" class="auth-password__toggle" aria-label="Show password" aria-pressed="false">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check auth-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1"
                        @checked(old('remember'))>
                    <label class="form-check-label" for="remember">
                        Remember me for 30 days
                    </label>
                </div>

                <button type="submit" class="btn-auth-submit">
                    {{ __('Sign In') }}
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer-links text-center mt-4">
                <p class="mb-2">
                    New here? <a href="{{ route('register') }}">Create an account</a>
                </p>
                <a href="{{ url('/') }}" class="auth-back-home">
                    <i class="bi bi-arrow-left"></i>
                    Back to main website
                </a>
            </div>
        </div>
    </main>
@endsection
