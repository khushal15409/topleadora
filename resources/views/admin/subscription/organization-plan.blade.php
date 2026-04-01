@extends('layouts.admin')

@section('title', __('My plan'))

@php
    $currencySymbol = static function (\App\Models\Plan $plan): string {
        return match (strtoupper((string) $plan->currency)) {
            'INR' => '₹',
            default => $plan->currency . ' ',
        };
    };

    $metaFor = static function (\App\Models\Plan $plan): array {
        return \App\Http\Controllers\Admin\SubscriptionController::planDisplayMeta($plan);
    };

    $trialFeatures = [
        __('Full product tour and CRM preview'),
        __('Team invites limited during trial'),
        __('Upgrade anytime to unlock production limits'),
    ];
@endphp

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <h4 class="mb-1">{{ __('Plan & subscription') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Manage your workspace billing, trial, and upgrades in one place.') }}</p>
        </div>
        <a href="{{ route('admin.subscription.pricing') }}" class="btn btn-label-secondary btn-sm">
            <i class="icon-base ri ri-external-link-line me-1"></i>{{ __('Public pricing layout') }}
        </a>
    </div>

    {{-- Current plan summary --}}
    <div class="card wp-crm-plan-current shadow-sm mb-4 border-0 position-relative overflow-hidden">
        <div class="card-body p-4 p-lg-5 position-relative">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    @if ($uiStatus === 'active')
                        <span class="badge bg-label-success mb-2">{{ __('Current plan') }}</span>
                    @elseif ($uiStatus === 'trial')
                        <span class="badge bg-label-info mb-2">{{ __('Trial') }}</span>
                    @else
                        <span class="badge bg-label-danger mb-2">{{ __('Expired') }}</span>
                    @endif
                    <h5 class="mb-1 text-heading">
                        @if ($uiStatus === 'trial')
                            {{ __('Free trial') }}
                        @elseif ($currentPlanModel)
                            {{ $currentPlanModel->name }}
                        @else
                            {{ __('No active plan') }}
                        @endif
                    </h5>
                    <p class="mb-0 text-body-secondary small">
                        @if ($uiStatus === 'active')
                            {{ __('Subscription is active. CRM access follows your current billing period.') }}
                        @elseif ($uiStatus === 'trial')
                            {{ __('Explore the CRM on trial. Pick a plan below when you are ready to Go Live.') }}
                        @else
                            {{ __('Your last billing period has ended. Renew or choose a new plan to restore CRM access.') }}
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    @if ($uiStatus === 'trial' && $trialDaysLeft !== null)
                        <div class="h3 mb-0 text-heading">{{ $trialDaysLeft }}</div>
                        <small class="text-body-secondary">{{ __('days left on trial') }}</small>
                    @elseif ($uiStatus === 'active' && $activeSubscription)
                        <div class="small text-muted text-uppercase">{{ __('Renews on') }}</div>
                        <div class="fw-semibold">{{ $activeSubscription->end_date->translatedFormat('M j, Y') }}</div>
                    @endif
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="small text-muted text-uppercase">{{ __('Price') }}</div>
                    @if ($uiStatus === 'trial')
                        <div class="h5 mb-0">—</div>
                        <small class="text-body-secondary">{{ __('Included with trial') }}</small>
                    @elseif ($currentPlanModel)
                        <div class="h5 mb-0">
                            {{ $currencySymbol($currentPlanModel) }}{{ number_format((float) $currentPlanModel->price_monthly, 0) }}
                            <span class="text-body-secondary fs-6 fw-normal">/ {{ __('month') }}</span>
                        </div>
                    @else
                        <div class="h5 mb-0">—</div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="small text-muted text-uppercase">{{ __('Status') }}</div>
                    @if ($uiStatus === 'active')
                        <span class="badge bg-label-success rounded-pill">{{ __('Active') }}</span>
                    @elseif ($uiStatus === 'trial')
                        <span class="badge bg-label-warning rounded-pill">{{ __('Trial') }}
                            @if ($trialDaysLeft !== null)
                                ({{ trans_choice(':count day left|:count days left', $trialDaysLeft, ['count' => $trialDaysLeft]) }})
                            @endif
                        </span>
                    @else
                        <span class="badge bg-label-danger rounded-pill">{{ __('Expired') }}</span>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="small text-muted text-uppercase mb-2">{{ __('Plan features') }}</div>
                    <ul class="list-unstyled mb-0 small text-body-secondary">
                        @if ($uiStatus === 'trial')
                            @foreach ($trialFeatures as $line)
                                <li class="d-flex gap-2 mb-1">
                                    <i class="icon-base ri ri-check-line text-success"></i><span>{{ $line }}</span>
                                </li>
                            @endforeach
                        @elseif ($currentPlanModel)
                            @foreach ($metaFor($currentPlanModel)['features'] ?? [] as $line)
                                <li class="d-flex gap-2 mb-1">
                                    <i class="icon-base ri ri-check-line text-success"></i><span>{{ $line }}</span>
                                </li>
                            @endforeach
                        @else
                            <li>{{ __('Select a plan below to see included features.') }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                @if ($uiStatus === 'trial' && $suggestedUpgrade)
                    <a href="{{ route('admin.checkout', $suggestedUpgrade->id) }}" class="btn btn-primary">
                        <i class="icon-base ri ri-rocket-line me-1"></i>{{ __('Upgrade Now') }}
                    </a>
                @elseif ($uiStatus === 'active' && $renewTarget)
                    <a href="{{ route('admin.checkout', $renewTarget->id) }}" class="btn btn-primary">
                        <i class="icon-base ri ri-refresh-line me-1"></i>{{ __('Renew Plan') }}
                    </a>
                    <a href="#available-plans" class="btn btn-label-primary">{{ __('Upgrade Plan') }}</a>
                @elseif ($uiStatus === 'expired' && $activateTarget)
                    <a href="{{ route('admin.checkout', $activateTarget->id) }}" class="btn btn-primary">
                        <i class="icon-base ri ri-shield-check-line me-1"></i>{{ __('Activate Plan') }}
                    </a>
                    <a href="#available-plans" class="btn btn-label-secondary">{{ __('Compare plans') }}</a>
                @endif
            </div>
        </div>
    </div>

    <h5 class="mb-3" id="available-plans">{{ __('Available plans') }}</h5>
    <p class="text-body-secondary small mb-4">{{ __('Starter, Pro, and Business tiers — pick the capacity that matches your team.') }}</p>

    <div class="row g-4">
        @foreach ($plans as $plan)
            @php
                $meta = $metaFor($plan);
                $isPaidCurrent = $activeSubscription && $activeSubscription->plan_id === $plan->id;
                $wasTier = $organization->plan_id === $plan->id;
                $highlight = $isPaidCurrent || ($uiStatus === 'expired' && $wasTier);
            @endphp
            <div class="col-lg-4">
                <div @class([
                    'card h-100 shadow-sm wp-crm-plan-tier-card position-relative',
                    'wp-crm-plan-tier-card--highlight' => $highlight,
                ])>
                    @if ($isPaidCurrent)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-primary">{{ __('Current plan') }}</span>
                    @elseif ($uiStatus === 'expired' && $wasTier)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-label-warning">{{ __('Previous plan') }}</span>
                    @endif
                    <div class="card-body d-flex flex-column p-4">
                        <div class="mb-2">
                            <span class="text-uppercase small text-muted fw-semibold">{{ $meta['badge_title'] ?? $plan->name }}</span>
                            <h5 class="mb-1 mt-1">{{ $plan->name }}</h5>
                            <div class="d-flex align-items-baseline gap-1">
                                <span class="h3 mb-0">{{ $currencySymbol($plan) }}{{ number_format((float) $plan->price_monthly, 0) }}</span>
                                <span class="text-body-secondary">/ {{ __('month') }}</span>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-body-secondary flex-grow-1 mb-4">
                            @foreach ($meta['features'] ?? [] as $line)
                                <li class="d-flex gap-2 mb-2">
                                    <i class="icon-base ri ri-check-line text-primary flex-shrink-0"></i>
                                    <span>{{ $line }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a
                            href="{{ route('admin.checkout', $plan->id) }}"
                            @class([
                                'btn w-100',
                                'btn-primary' => ! $isPaidCurrent || $uiStatus !== 'active',
                                'btn-label-primary' => $isPaidCurrent && $uiStatus === 'active',
                            ])
                        >
                            @if ($isPaidCurrent && $uiStatus === 'active')
                                {{ __('Renew / extend') }}
                            @else
                                {{ __('Choose :plan', ['plan' => $plan->name]) }}
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
