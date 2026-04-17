@extends('layouts.auth')

@section('meta_title', 'Set new password | ' . config('app.name', 'WhatsAppLeadCRM'))

@section('content')
    @php
        $asideTitle = 'Set a new password';
        $asideText = 'Choose a strong password to keep your workspace secure.';
        $asideBullets = [
            ['icon' => 'bi bi-key', 'label' => 'Use a unique password for this account.'],
            ['icon' => 'bi bi-lock', 'label' => 'Your reset link is validated securely.'],
            ['icon' => 'bi bi-check2-circle', 'label' => 'You’ll be able to sign in right after.'],
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
            <div class="auth-card__eyebrow">Password reset</div>
            <h1 class="auth-card__title">Create a new password</h1>
            <p class="auth-card__subtitle">This will update your account password.</p>

            <form method="post" action="{{ route('password.update') }}" class="auth-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="reset-email" class="form-label">Email address</label>
                    <input type="email" name="email" id="reset-email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@company.com" value="{{ old('email', $email) }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new-password" class="form-label mb-0">New password</label>
                    <input type="password" name="password" id="new-password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new-password-confirm" class="form-label mb-0">Confirm password</label>
                    <input type="password" name="password_confirmation" id="new-password-confirm"
                        class="form-control"
                        placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-auth-submit">
                    Update password
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer-links text-center mt-4">
                <a href="{{ route('login') }}" class="auth-back-home">
                    <i class="bi bi-arrow-left"></i>
                    Back to sign in
                </a>
            </div>
        </div>
    </main>
@endsection

