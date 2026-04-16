@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'Case Studies — WhatsApp CRM, API & Lead Generation (India)')
@section('meta_description', 'Real-world examples of teams using WhatsApp CRM, WhatsApp API, and lead generation to improve response time, follow-ups, and pipeline visibility across India.')

@section('content')
    <section class="section pt-5 mt-5 light-background">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">{{ __('Resources') }}</span>
            <h1>{{ __('Case Studies') }}</h1>
            <p class="text-muted mb-0">
                {{ __('Short stories from teams improving lead response time, follow-ups, and pipeline visibility with WhatsApp CRM and automation.') }}
            </p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Real Estate teams') }}</h2>
                        <p class="text-muted mb-0">{{ __('Centralize inquiries from WhatsApp and ads, assign leads, and track site visits with clear next steps.') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Insurance advisors') }}</h2>
                        <p class="text-muted mb-0">{{ __('Reduce missed follow-ups with reminders, status stages, and a single view of every conversation.') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 border bg-white h-100 shadow-sm">
                        <h2 class="h5 fw-bold mb-2">{{ __('Agencies & small businesses') }}</h2>
                        <p class="text-muted mb-0">{{ __('Keep multiple campaigns organized with lead labels, shared inbox workflows, and simple reporting.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('blog.index') }}" class="btn btn-cta">{{ __('Read the blog') }}</a>
                <a href="{{ route('contact') }}" class="btn btn-label-secondary">{{ __('Talk to our team') }}</a>
            </div>
        </div>
    </section>
@endsection

