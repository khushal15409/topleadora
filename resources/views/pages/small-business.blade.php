@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp CRM for Small Businesses in India')
@section('meta_description', 'Capture and convert leads without complex tools. Simple WhatsApp CRM with automation and quick setup for small businesses in India.')

@section('content')
    <section class="section pt-5 mt-5 light-background">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">{{ __('Solutions') }}</span>
            <h1>{{ __('WhatsApp CRM for Small Businesses in India') }}</h1>
            <p class="text-muted mb-0">{{ __('Capture and convert leads without complex tools.') }}</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Simple lead management') }}</h2>
                        <p class="text-muted mb-0">{{ __('See every inquiry, assign owners, and keep follow-ups on time with a clean pipeline.') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('WhatsApp automation') }}</h2>
                        <p class="text-muted mb-0">{{ __('Use templates and workflows to respond faster and keep conversations moving.') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Easy setup') }}</h2>
                        <p class="text-muted mb-0">{{ __('Start quickly with minimal training—built for teams doing daily sales work.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('register') }}" class="btn btn-cta">{{ __('Get Started Today') }}</a>
                <a href="{{ route('pricing') }}" class="btn btn-label-secondary">{{ __('See pricing') }}</a>
            </div>
        </div>
    </section>
@endsection

