@extends('layouts.admin')

@section('title', 'Plans & billing')

@push('vendor-css')
    <link href="{{ asset('front/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/landify-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/landing-custom.css') }}" rel="stylesheet">
@endpush

@section('content')
    @if ($showExpiredOverlay ?? false)
        <div
            class="saas-trial-expired-overlay"
            role="dialog"
            aria-modal="true"
            aria-labelledby="trial-expired-title"
        >
            <div class="saas-trial-expired-overlay__backdrop" aria-hidden="true"></div>
            <div class="saas-trial-expired-overlay__panel card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            <i class="icon-base ri ri-time-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 id="trial-expired-title" class="mb-2">Your Free Trial Has Expired</h4>
                    <p class="text-body-secondary mb-4">
                        To continue using the CRM, please select a plan and complete upgrade.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                        <a href="{{ route('admin.subscription.pricing') }}" class="btn btn-label-secondary">
                            View Plans
                        </a>
                        @if ($plans->isNotEmpty())
                            <a
                                href="{{ route('admin.checkout', $plans->first()->id) }}"
                                class="btn btn-primary"
                            >
                                Upgrade Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="admin-pricing-front-mirror">
        @include('partials.pricing-plans-grid', [
            'plans' => $plans,
            'ctaMode' => 'admin',
            'enableAos' => false,
            'pricingContainerClass' => 'container-fluid px-0 px-lg-2',
        ])
    </div>
@endsection
