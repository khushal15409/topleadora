@extends('layouts.landing')

@section('body_class', 'inner-page')

@section('meta_title', 'WhatsApp CRM Pricing India | WhatsAppLeadCRM')
@section('meta_description', 'Simple pricing for WhatsApp CRM software India, API workflows, and lead management. Start free and upgrade as your team grows.')
@section('meta_keywords', 'WhatsApp CRM pricing, CRM India, lead management software, sales CRM pricing, real estate CRM')
@section('canonical_url', route('pricing', absolute: true))

@section('content')
    @if (! paymentEnabled())
        <section class="section light-background pt-5 mt-5">
            <div class="container section-title text-center">
                <h1 class="mb-2">Free access enabled</h1>
                <p class="mb-0">Payments are currently disabled by the admin. All CRM features are unlocked.</p>
            </div>
        </section>
        @php return; @endphp
    @endif

    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">Pricing</h1>
            <p class="mb-0">Start with a free trial and upgrade when you’re ready.</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="h5 fw-bold mb-2">Free trial included</h2>
                            <p class="text-muted mb-4">Create an organization and get instant access to the CRM dashboard. Plans are managed inside your dashboard.</p>

                            <div class="d-flex flex-wrap gap-2">
                                @auth
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary rounded-pill px-4">Open dashboard</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">Start Free Trial</a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Login</a>
                                @endauth
                                <a href="{{ route('blog.index') }}" class="btn btn-label-secondary rounded-pill px-4">Read guides</a>
                            </div>

                            <hr class="my-4">

                            <h3 class="h6 fw-bold mb-2">What you get</h3>
                            <ul class="text-muted mb-0">
                                <li>WhatsApp CRM lead management system</li>
                                <li>Pipeline (Kanban)</li>
                                <li>Follow-ups system</li>
                                <li>Broadcast + reports</li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-center text-muted small mt-3 mb-0">
                        Want feature details? <a href="{{ route('features') }}">See features</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

