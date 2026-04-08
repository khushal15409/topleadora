<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical"
    data-vertical-style="overlay" class="light" data-header-styles="light" data-menu-styles="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'Sign in | ' . config('app.name', 'WhatsAppLeadCRM'))</title>
    <meta name="description" content="@yield('meta_description', 'Sign in to your WhatsApp CRM workspace.')">
    @include('layouts.partials.favicon')

    <!-- ICONS CSS -->
    <link href="{{asset('build/assets/iconfonts/icons.css')}}" rel="stylesheet">

    <!-- MAIN APP CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-C1ug_Vkx.css') }}">

    <!-- CUSTOM STYLES -->
    <style>
        .auth-container {
            min-height: 100vh;
        }

        .main-signin-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>
    @stack('styles')
</head>

<body class="error-1">
    <!-- Loader -->
    <div id="loader">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
    </div>
    <!-- Loader -->

    <div class="page main-signin-wrapper">
        @yield('content')
    </div>

    <!-- MAIN APP JS -->
    <script src="{{asset('build/assets/authentication-main-CSD_wYmU.js')}}"></script>
    <script type="module" src="{{ asset('build/assets/app-QCoZG1M9.js') }}"></script>

    <script>
        // Loader
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>