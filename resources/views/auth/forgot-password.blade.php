@extends('layouts.auth')

@section('meta_title', 'Reset password | ' . config('app.name', 'WhatsAppLeadCRM'))

@section('content')
    @php
        $asideTitle = 'Get back into your workspace';
        $asideText = 'We’ll email you a secure link to reset your password.';
        $asideBullets = [
            ['icon' => 'bi bi-shield-lock', 'label' => 'Secure token-based reset link.'],
            ['icon' => 'bi bi-envelope', 'label' => 'Sent only to your account email.'],
            ['icon' => 'bi bi-clock', 'label' => 'Links expire automatically for safety.'],
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
            <h1 class="auth-card__title">Forgot your password?</h1>
            <p class="auth-card__subtitle">Enter your email and we’ll send a reset link.</p>

            @if (session('status'))
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="post" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <div class="mb-3">
                    <label for="reset-email" class="form-label">Email address</label>
                    <input type="email" name="email" id="reset-email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-auth-submit">
                    Send reset link
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

