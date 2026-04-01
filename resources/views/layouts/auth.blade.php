<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'Sign in | '.config('app.name', 'WhatsAppLeadCRM'))</title>
    <meta name="description" content="@yield('meta_description', 'Sign in to your WhatsApp CRM workspace.')">
    @include('layouts.partials.favicon')

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="{{ asset('front/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/landify-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/auth-pages.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="auth-page">
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
