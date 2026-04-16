<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'Sign in | ' . config('app.name', 'WhatsAppLeadCRM'))</title>
    <meta name="description" content="@yield('meta_description', 'Sign in to your WhatsApp CRM workspace.')">
    @include('layouts.partials.favicon')

    <!-- Fonts (Landify) -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="{{ asset('front/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <!-- Landify theme CSS -->
    <link href="{{ asset('front/css/landify-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/landing-custom.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/auth-pages.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="auth-page">
    <div class="auth-shell d-flex flex-column flex-lg-row">
        @yield('content')
    </div>

    <script>
        document.querySelectorAll('.auth-password').forEach((wrapper) => {
            const input = wrapper.querySelector('input');
            const button = wrapper.querySelector('button');
            const icon = button ? button.querySelector('i') : null;

            if (!input || !button) {
                return;
            }

            button.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isHidden));
                if (icon) {
                    icon.classList.toggle('bi-eye', !isHidden);
                    icon.classList.toggle('bi-eye-slash', isHidden);
                }
            });
        });
    </script>
    @include('layouts.partials.toaster')
    @stack('scripts')
</body>

</html>
