@php
    /** @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $plans */
    $ctaMode = $ctaMode ?? 'landing';
    $enableAos = $enableAos ?? false;
    $landingCta = $landingCta ?? null;
    $pricingContainerClass = $pricingContainerClass ?? 'container';
    $display = config('pricing_plans', []);

    // Ensure $plans is a collection to avoid "Member function isEmpty() on array" error
    if (!isset($plans) || is_array($plans)) {
        $plans = collect($plans ?? []);
    }

    $landingPlanQuery = static function (\App\Models\Plan $plan): string {
        return match ($plan->slug) {
            'starter' => 'starter',
            'professional' => 'pro',
            'enterprise' => 'business',
            default => $plan->slug,
        };
    };
@endphp

@if (!paymentEnabled())
    {{-- Free mode: hide pricing section completely. --}}
    @php return; @endphp
@endif

<section id="pricing" class="pricing section pricing-section-enhanced">
    <div class="{{ $pricingContainerClass }} section-title" @if ($enableAos) data-aos="fade-up" @endif>
        <span class="description-title">Pricing</span>
        <h2>Simple &amp; Affordable Pricing</h2>
    </div>
    <div class="{{ $pricingContainerClass }}" @if ($enableAos) data-aos="fade-up" data-aos-delay="80" @endif>
        <div class="text-center mb-4" @if ($enableAos) data-aos="zoom-in" @endif>
            <span class="pricing-trial-badge"><i class="bi bi-gift me-1"></i> 7-Day Free Trial — No credit card
                required</span>
        </div>
        @if ($plans->isEmpty())
            <p class="text-center text-muted mb-0">Plans will appear here once they are configured.</p>
        @else
            <div id="plans" class="row gy-4 justify-content-center paid-plans-row">
                @foreach ($plans as $plan)
                    @php
                        $meta = array_merge(
                            $display['_default'] ?? [],
                            $display[$plan->slug] ?? []
                        );
                        $badgeTitle = $meta['badge_title'] ?? $plan->name;
                        $featured = !empty($meta['featured']);
                        $delay = 100 + ($loop->index * 80);
                    @endphp
                    <div class="col-lg-4" @if ($enableAos) data-aos="fade-up" data-aos-delay="{{ $delay }}" @endif>
                        <article @class([
                            'price-card',
                            'price-card-paid',
                            'pricing-card-animate',
                            'h-100',
                            'position-relative',
                            'featured' => $featured,
                            'pricing-card-popular' => $featured,
                        ])>
                            @if ($featured)
                                <div class="ribbon"><i class="bi bi-star-fill"></i> Most Popular</div>
                            @endif
                            <div class="card-head">
                                <span class="badge-title">{{ $badgeTitle }}</span>
                                <div class="price-wrap price-wrap-paid">
                                    <span class="price price-monthly">
                                        <span class="price-value">{{ money_local((float) $plan->price_monthly, 0) }}</span><span class="period">/month</span>
                                    </span>
                                </div>
                                @php($ctx = currency_context())
                                @if (($ctx['currency_code'] ?? 'INR') !== ($ctx['base_currency'] ?? 'INR'))
                                    <p class="text-muted small mb-0">
                                        {{ __('Charged in') }} {{ $ctx['base_currency'] }} ({{ money_inr((float) $plan->price_monthly, 0) }})
                                    </p>
                                @endif
                                @if ($meta['plan_tagline'] !== '')
                                    <p class="plan-tagline">{{ $meta['plan_tagline'] }}</p>
                                @endif
                                <h3 class="title">{{ $meta['title'] }}</h3>
                                <p class="subtitle">{{ $meta['subtitle'] }}</p>
                            </div>
                            <ul class="feature-list list-unstyled mb-4">
                                @foreach ($meta['features'] as $line)
                                    <li><i class="bi bi-check-circle"></i> {{ $line }}</li>
                                @endforeach
                            </ul>
                            <div class="cta">
                                @if ($ctaMode === 'admin')
                                    <a href="{{ route('admin.checkout', $plan->id) }}"
                                        class="btn btn-choose btn-choose-paid w-100">Choose {{ $badgeTitle }}</a>
                                @else
                                    <a href="{{ $landingCta(['plan' => $landingPlanQuery($plan)]) }}"
                                        class="btn btn-choose btn-choose-paid w-100">Choose {{ $badgeTitle }}</a>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <p class="text-center small text-muted mt-4 mb-0">Cancel anytime · Secure payments · WhatsApp charges per Meta
                pricing</p>
        @endif
    </div>
</section>