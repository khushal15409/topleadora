@extends('layouts.landing')

@section('body_class', 'inner-page')

@section('meta_title', 'About | WhatsAppLeadCRM')
@section('meta_description', 'About WhatsAppLeadCRM — a WhatsApp-first CRM and messaging platform built for Indian businesses to capture leads, follow up, and convert faster.')
@section('canonical_url', route('about', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">{{ __('About WhatsAppLeadCRM') }}</h1>
            <p class="mb-0">{{ __('We build WhatsApp-first tools that help teams respond faster, stay organized, and convert more leads.') }}</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="h5 fw-bold mb-2">{{ __('What we do') }}</h2>
                            <p class="text-muted mb-4">
                                {{ __('WhatsAppLeadCRM combines lead capture, conversation tracking, follow-ups, and reporting so your team can move from chats to a measurable pipeline.') }}
                            </p>

                            <h2 class="h6 fw-bold mb-2">{{ __('Who it’s for') }}</h2>
                            <ul class="text-muted mb-4">
                                <li>{{ __('Real estate teams managing enquiries and site visits') }}</li>
                                <li>{{ __('Insurance teams handling renewals and new policy leads') }}</li>
                                <li>{{ __('Agencies running campaigns and coordinating multiple clients') }}</li>
                                <li>{{ __('Small businesses that need simple, fast lead follow-up') }}</li>
                            </ul>

                            <h2 class="h6 fw-bold mb-2">{{ __('How we build trust') }}</h2>
                            <ul class="text-muted mb-0">
                                <li>{{ __('Clear ownership of each lead and follow-up so nothing is missed') }}</li>
                                <li>{{ __('Practical defaults designed for Indian sales workflows') }}</li>
                                <li>{{ __('Support available via email and the contact form') }}</li>
                            </ul>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                @auth
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary rounded-pill px-4">{{ __('Go to Dashboard') }}</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">{{ __('Start Free Trial') }}</a>
                                @endauth
                                <a href="{{ route('contact') }}" class="btn btn-outline-primary rounded-pill px-4">{{ __('Contact us') }}</a>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-muted small mt-3 mb-0">
                        {{ __('Looking for details?') }}
                        <a href="{{ route('whatsapp-crm') }}">{{ __('Explore WhatsApp CRM') }}</a>
                        {{ __('or') }}
                        <a href="{{ route('whatsapp-api') }}">{{ __('learn about the API') }}</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

