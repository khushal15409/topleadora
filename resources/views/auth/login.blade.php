@extends('layouts.auth')

@section('meta_title', 'Sign in | '.config('app.name', 'WhatsAppLeadCRM'))
@section('meta_description', 'Sign in to manage WhatsApp leads, pipeline, and follow-ups.')

@section('content')
<div class="auth-shell d-flex flex-column flex-lg-row">
    @include('auth.partials.aside-panel', [
        'asideTitle' => 'Pipeline clarity for every WhatsApp conversation',
        'asideText' => 'One workspace for leads, stages, and follow-ups—built for teams that close on chat.',
        'asideBullets' => [
            ['icon' => 'bi bi-check-lg', 'label' => 'Shared inbox & clear ownership'],
            ['icon' => 'bi bi-check-lg', 'label' => 'Stages that match how you sell'],
            ['icon' => 'bi bi-check-lg', 'label' => 'Fewer dropped leads, faster handoffs'],
        ],
        'asideImage' => 'front/images/landify/illustration/illustration-15.webp',
    ])

    <main class="auth-main flex-grow-1">
        <div class="auth-card">
            <div class="auth-mobile-brand d-lg-none text-center mb-4">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}">
                </a>
            </div>

            <p class="auth-card__eyebrow">Welcome back</p>
            <h1 class="auth-card__title">Sign in</h1>
            <p class="auth-card__subtitle">Enter your email and password to open your dashboard.</p>

            <form method="post" action="{{ route('login') }}" class="auth-form" novalidate>
                @csrf

                <div class="form-floating mb-3">
                    <input
                        type="email"
                        name="email"
                        id="login-email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@company.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    <label for="login-email">Email address</label>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input
                        type="password"
                        name="password"
                        id="login-password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                    >
                    <label for="login-password">Password</label>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check auth-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-auth-submit">
                    <span>Sign in</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <p class="auth-footer-links text-center mt-4 mb-0">
                New here?
                <a href="{{ route('register') }}">Create an account</a>
            </p>

            <div class="text-center">
                <a href="{{ url('/') }}" class="auth-back-home">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Back to website
                </a>
            </div>
        </div>
    </main>
</div>
@endsection
