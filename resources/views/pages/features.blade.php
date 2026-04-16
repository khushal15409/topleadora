@extends('layouts.landing')

@section('body_class', 'inner-page')

@section('meta_title', 'WhatsApp CRM Software India Features (Pipeline, Follow-ups) | WhatsAppLeadCRM')
@section('meta_description', 'Explore WhatsApp CRM software India features: WhatsApp lead management system, pipeline (Kanban), follow-ups, and broadcast messaging. Built for Indian sales teams.')
@section('meta_keywords', 'WhatsApp CRM features, lead management system, sales pipeline, follow ups, CRM for sales teams')
@section('canonical_url', route('features', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">WhatsApp Lead Management System Features for India</h1>
            <p class="mb-0">Outcome-focused CRM features to capture enquiries, improve follow-up discipline, and increase conversions across real estate, insurance, and agencies. See how it works in <a href="{{ route('whatsapp-crm') }}">WhatsApp CRM software India</a>, or add messaging automation via <a href="{{ route('whatsapp-api') }}">WhatsApp API provider India</a>.</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2">Lead Management System</h2>
                            <p class="text-muted mb-0">Capture leads from WhatsApp, Instagram, Facebook and your website. Keep phone, source, notes, stage, and next follow-up date in one place.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2">Pipeline (Kanban)</h2>
                            <p class="text-muted mb-0">Drag and drop leads through stages: New → Contacted → Interested → Follow-up → Closed. Fast visibility for sales teams.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2">Follow-ups</h2>
                            <p class="text-muted mb-0">See today’s and upcoming follow-ups, mark completed in one click, and avoid missed callbacks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2">Broadcast</h2>
                            <p class="text-muted mb-0">Send WhatsApp messages to selected leads or all leads with phone numbers. Track broadcast history.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                {{-- CRO: action-led labels (trial + pricing) for Indian SaaS funnel. --}}
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">{{ __('Get set up in 2 minutes') }}</a>
                <a href="{{ route('pricing') }}" class="btn btn-outline-primary rounded-pill px-4 ms-md-2 mt-2 mt-md-0">{{ __('Get free trial — view pricing') }}</a>
                <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-md-2 mt-2 mt-md-0">{{ __('Read blog') }}</a>
            </div>
        </div>
    </section>
@endsection

