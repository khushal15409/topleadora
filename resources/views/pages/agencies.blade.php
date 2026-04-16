@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp Solutions for Marketing Agencies (India)')
@section('meta_description', 'Manage client leads, WhatsApp campaigns, and performance tracking from one platform. Built for agencies handling daily inbound leads in India.')

@section('content')
    <section class="section pt-5 mt-5 light-background">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">{{ __('Solutions') }}</span>
            <h1>{{ __('WhatsApp Solutions for Marketing Agencies') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage client leads and campaigns from one platform.') }}</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Handle multiple clients') }}</h2>
                        <p class="text-muted mb-0">{{ __('Keep pipelines, follow-ups, and ownership clear across accounts and campaign sources.') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Run WhatsApp campaigns') }}</h2>
                        <p class="text-muted mb-0">{{ __('Reply faster with templates, automation, and a single view of every conversation.') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Track performance') }}</h2>
                        <p class="text-muted mb-0">{{ __('Measure response time, stage movement, and outcomes so you can report confidently.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('register') }}" class="btn btn-cta">{{ __('Start Using for Agencies') }}</a>
                <a href="{{ route('contact') }}" class="btn btn-label-secondary">{{ __('Talk to our team') }}</a>
            </div>
        </div>
    </section>
@endsection

