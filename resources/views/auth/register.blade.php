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

                <div class="mb-4 mt-4">
                    <label class="form-label d-block mb-3">What will you use {{ config('app.name') }} for?</label>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <input type="radio" name="account_type" value="crm" id="type-crm" class="btn-check" checked autocomplete="off">
                            <label class="active-type-card h-100" for="type-crm">
                                <div class="type-icon"><i class="bi bi-person-workspace text-primary"></i></div>
                                <div class="type-content">
                                    <h6 class="mb-1">Use CRM</h6>
                                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">Manage leads, pipeline, and team follow-ups.</p>
                                </div>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="radio" name="account_type" value="api" id="type-api" class="btn-check" autocomplete="off">
                            <label class="active-type-card h-100" for="type-api">
                                <div class="type-icon"><i class="bi bi-code-slash text-success"></i></div>
                                <div class="type-content">
                                    <h6 class="mb-1">API Services</h6>
                                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">OTP & WhatsApp API for developer integrations.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit mt-2">
                    {{ __('Create Account') }}
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            @push('styles')
            <style>
                .active-type-card {
                    display: flex;
                    align-items: center;
                    padding: 1rem;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.75rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    background: #fff;
                }
                .btn-check:checked + .active-type-card {
                    border-color: var(--bs-primary);
                    background: rgba(var(--bs-primary-rgb), 0.04);
                    box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.1);
                }
                .type-icon {
                    width: 40px;
                    height: 40px;
                    background: #f3f4f6;
                    border-radius: 0.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 1rem;
                    font-size: 1.25rem;
                    flex-shrink: 0;
                }
                .btn-check:checked + .active-type-card .type-icon {
                    background: #fff;
                }
                .type-content h6 {
                    font-size: 0.9rem;
                    font-weight: 600;
                }
            </style>
            @endpush

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
