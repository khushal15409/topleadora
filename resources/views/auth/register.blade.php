@extends('layouts.auth')

@section('meta_title', 'Create account | '.config('app.name', 'WhatsAppLeadCRM'))
@section('meta_description', 'Register to start managing WhatsApp leads and your sales pipeline.')

@section('content')
<div class="auth-shell d-flex flex-column flex-lg-row">
    @include('auth.partials.aside-panel', [
        'asideTitle' => 'Start your workspace in minutes',
        'asideText' => 'Connect your team, organize WhatsApp leads, and keep every follow-up on track.',
        'asideBullets' => [
            ['icon' => 'bi bi-lightning-charge', 'label' => 'Quick setup with a familiar CRM flow'],
            ['icon' => 'bi bi-shield-check', 'label' => 'Secure sign-in & session handling'],
            ['icon' => 'bi bi-graph-up-arrow', 'label' => 'Built for conversion-focused teams'],
        ],
        'asideImage' => 'front/images/landify/features/features-3.webp',
    ])

    <main class="auth-main flex-grow-1">
        <div class="auth-card">
            <div class="auth-mobile-brand d-lg-none text-center mb-4">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}">
                </a>
            </div>

            <p class="auth-card__eyebrow">Get started</p>
            <h1 class="auth-card__title">Create your account</h1>
            <p class="auth-card__subtitle">Fill in your details below. You’ll be signed in after registration.</p>

            <form method="post" action="{{ route('register') }}" class="auth-form" novalidate>
                @csrf

                <div class="form-floating mb-3">
                    <input
                        type="text"
                        name="organization_name"
                        id="register-organization-name"
                        class="form-control @error('organization_name') is-invalid @enderror"
                        placeholder="Your company"
                        value="{{ old('organization_name') }}"
                        required
                        autocomplete="organization"
                    >
                    <label for="register-organization-name">Organization name</label>
                    @error('organization_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input
                        type="text"
                        name="name"
                        id="register-name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Your name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        autofocus
                    >
                    <label for="register-name">Full name</label>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input
                        type="email"
                        name="email"
                        id="register-email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@company.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                    <label for="register-email">Work email</label>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input
                        type="password"
                        name="password"
                        id="register-password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password"
                        required
                        autocomplete="new-password"
                    >
                    <label for="register-password">Password</label>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="register-password-confirmation"
                        class="form-control"
                        placeholder="Confirm password"
                        required
                        autocomplete="new-password"
                    >
                    <label for="register-password-confirmation">Confirm password</label>
                </div>

                <button type="submit" class="btn btn-auth-submit">
                    <span>Create account</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <p class="auth-footer-links text-center mt-4 mb-0">
                Already have an account?
                <a href="{{ route('login') }}">Sign in</a>
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
