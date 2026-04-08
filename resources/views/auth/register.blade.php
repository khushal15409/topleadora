@extends('layouts.auth')

@section('meta_title', 'Create account | ' . config('app.name', 'WhatsAppLeadCRM'))

@section('content')
    @php
        $asideTitle = 'Start your workspace in minutes';
        $asideText = 'Connect your team, organize WhatsApp leads, and keep every follow-up on track.';
        $asideBullets = [
            ['icon' => 'bi bi-lightning-charge', 'label' => 'Quick setup with a familiar CRM flow.'],
            ['icon' => 'bi bi-shield-check', 'label' => 'Secure sign-in and reliable data handling.'],
            ['icon' => 'bi bi-graph-up-arrow', 'label' => 'Built for teams focused on conversion.'],
        ];
        $asideImage = 'front/images/landify/sections-images/registration.png';
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
            <div class="auth-card__eyebrow">Get started</div>
            <h1 class="auth-card__title">Create your account</h1>
            <p class="auth-card__subtitle">Set up your team and start managing leads today.</p>

            <form method="post" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="mb-3">
                    <label for="register-organization-name" class="form-label">Organization name</label>
                    <input type="text" name="organization_name" id="register-organization-name"
                        class="form-control @error('organization_name') is-invalid @enderror"
                        placeholder="Your company name" value="{{ old('organization_name') }}" required>
                    @error('organization_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="register-name" class="form-label">Full name</label>
                    <input type="text" name="name" id="register-name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="register-email" class="form-label">Work email</label>
                    <input type="email" name="email" id="register-email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="john@company.com" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="register-password" class="form-label">Password</label>
                        <div class="auth-password">
                            <input type="password" name="password" id="register-password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••" required>
                            <button type="button" class="auth-password__toggle" aria-label="Show password" aria-pressed="false">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="register-password-confirmation" class="form-label">Confirm password</label>
                        <div class="auth-password">
                            <input type="password" name="password_confirmation" id="register-password-confirmation"
                                class="form-control" placeholder="••••••••" required>
                            <button type="button" class="auth-password__toggle" aria-label="Show password" aria-pressed="false">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit mt-4">
                    {{ __('Create Account') }}
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer-links text-center mt-4">
                <p class="mb-2">
                    Already have an account? <a href="{{ route('login') }}">Sign in instead</a>
                </p>
                <a href="{{ url('/') }}" class="auth-back-home">
                    <i class="bi bi-arrow-left"></i>
                    Back to main website
                </a>
            </div>
        </div>
    </main>
@endsection
