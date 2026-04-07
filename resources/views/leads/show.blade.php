@extends('layouts.leads')

@section('meta_title', $page['meta_title'])
@section('meta_description', $page['meta_description'])
@section('meta_keywords', $page['meta_keywords'])
@section('meta_robots', $page['robots_meta'] ?? config('leads.default_meta_robots', 'index,follow'))
@section('canonical_url', $canonicalUrl ?? url()->current())
@section('meta_og_image', \App\Support\SeoMeta::ogImageForLeadPage($page))

@php
    $heroPreload = ! empty($page['hero_image']) ? leadPublicImageUrl((string) $page['hero_image']) : '';
    if ($heroPreload === '') {
        $heroPreload = (string) config('leads.hero_default_image_url');
    }
    if ($heroPreload === '') {
        $heroPreload = leadImageFallbackUrl();
    }
    $heroSrcset = leadResponsiveSrcset($heroPreload);
    $defaultServiceName = null;
    if (isset($defaultServiceId) && ($servicesForForm ?? null) !== null) {
        $defaultServiceName = $servicesForForm->firstWhere('id', (int) $defaultServiceId)?->name;
    }
    $showShareRisk = ($servicesForForm ?? collect())->contains(function ($s) {
        $n = strtolower((string) ($s->name ?? ''));

        return str_contains($n, 'share') || str_contains($n, 'stock') || str_contains($n, 'trading') || str_contains($n, 'equity');
    });
@endphp

@push('json_ld')
    <script type="application/ld+json">
        {!! json_encode($schemaGraph ?? [], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>
@endpush

@push('json_ld')
    @php
        $crumbLabel = trim((string) ($page['hero_headline'] ?? $page['meta_title'] ?? 'Service'));
        $crumbLabel = \Illuminate\Support\Str::limit($crumbLabel !== '' ? $crumbLabel : 'Leads', 80);
        $crumbPageUrl = $canonicalUrl ?? url()->current();
        $leadBreadcrumbLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $crumbLabel,
                    'item' => $crumbPageUrl,
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($leadBreadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>
@endpush

@push('styles')
    <link rel="preload" as="image" href="{{ $heroPreload }}" @if ($heroSrcset !== '') imagesrcset="{{ $heroSrcset }}" imagesizes="100vw" @endif fetchpriority="high">
@endpush

@section('body_class')
    leads-page-conversion
@endsection

@section('footer_niche_disclaimer')
    @if (! empty($page['footer_disclaimer'] ?? null))
        <p class="mb-0 lh-lg mt-2 border-start border-3 border-warning ps-3">{{ $page['footer_disclaimer'] }}</p>
    @endif
@endsection

@section('content')
    <x-leads.hero :page="$page" :slug="$slug">
        <div id="lead-form" class="leads-card leads-card--form leads-form-sticky">
            <div class="p-4">
                <h2 class="h5 fw-bold text-dark mb-1">{{ __('Get your free consultation') }}</h2>
                <p class="small text-muted mb-3">{{ __('Applications are open for India — we’ll route your request to the right team. Takes under a minute; no spam.') }}</p>
                <div id="lead-capture-success" class="alert alert-success d-none py-3 border-0" role="status">
                    {{ __('Thank you! We have received your details. Our team will contact you shortly.') }}
                </div>
                <div id="lead-capture-error" class="alert alert-danger d-none py-3 border-0" role="alert"></div>
                <div id="lead-capture-form-mount">
                    <x-leads.lead-form
                        :slug="$slug"
                        :submit-label="$page['hero_cta'] ?? __('Get free consultation')"
                        :services-for-form="$servicesForForm ?? collect()"
                        :countries-for-form="$countriesForForm ?? collect()"
                        :default-service-id="$defaultServiceId ?? null"
                        :dynamic-form-fields="$dynamicFormFields ?? collect()"
                        :selected-service-name="$defaultServiceName"
                    />
                </div>
            </div>
        </div>
    </x-leads.hero>

    <x-leads.trust-section :page="$page" />

    @if (isset($relatedBlogPosts) && $relatedBlogPosts->isNotEmpty())
        <section class="leads-section ls-animate" aria-labelledby="leads-related-blog-title">
            <div class="container">
                <h2 id="leads-related-blog-title" class="h3 leads-section-title mb-4">{{ __('Related guides') }}</h2>
                <ul class="row row-cols-1 row-cols-md-2 g-3 list-unstyled mb-0">
                    @foreach ($relatedBlogPosts as $b)
                        <li class="col">
                            <a href="{{ route('blog.show', $b->slug) }}" class="d-block h-100 p-3 rounded-4 border bg-white text-decoration-none shadow-sm leads-internal-link">
                                <span class="fw-semibold text-body d-block">{{ $b->title }}</span>
                                @if ($b->published_at)
                                    <time class="small text-muted d-block mt-1" datetime="{{ $b->published_at->toIso8601String() }}">{{ $b->published_at->format('M j, Y') }}</time>
                                @endif
                                <span class="small text-primary mt-2 d-inline-block">{{ __('Read on blog') }} →</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <x-leads.guide-accordion
        :slug="$slug"
        :show-share-risk="$showShareRisk"
        :seo-body="$page['seo_body'] ?? null"
    />

    @if (! empty($relatedLandingCards))
        <section class="leads-section ls-animate" aria-labelledby="leads-internal-title">
            <div class="container">
                <h2 id="leads-internal-title" class="h3 leads-section-title mb-4">{{ __('Related services in India') }}</h2>
                <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 list-unstyled mb-0">
                    @foreach ($relatedLandingCards as $card)
                        <li class="col">
                            <a
                                href="{{ $card['url'] }}"
                                class="d-block h-100 p-3 rounded-4 border bg-white text-decoration-none shadow-sm leads-internal-link"
                            >
                                <span class="fw-semibold text-body d-block">{{ $card['title'] }}</span>
                                <span class="small text-muted d-block mt-1">{{ $card['subtitle'] }}</span>
                                <span class="small text-primary mt-2 d-inline-block">{{ __('View page') }} →</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <x-leads.benefits :page="$page" />
    <x-leads.how-it-works :page="$page" :cta-label="$page['hero_cta'] ?? __('Get free consultation')" />
    <x-leads.faq :page="$page" :slug="$slug" />
    <x-leads.final-cta :page="$page" />

    {{-- Mobile sticky CTA: matches hero CTA for conversion consistency. --}}
    <div class="leads-mobile-sticky-cta d-lg-none" role="region" aria-label="{{ __('Quick apply') }}">
        <a href="#lead-form" class="btn btn-leads-submit w-100 py-3">{{ $page['hero_cta'] ?? __('Apply now in 2 minutes') }}</a>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var form = document.getElementById('lead-capture-form');
            if (!form) return;

            var serviceHidden = document.getElementById('lc-service-id');
            var phoneInput = document.getElementById('lc-phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 10);
                });
            }

            var submitBtn = document.getElementById('lead-capture-submit');
            var spinner = submitBtn ? submitBtn.querySelector('.lead-submit-loading') : null;
            var successEl = document.getElementById('lead-capture-success');
            var errorBox = document.getElementById('lead-capture-error');
            var mount = document.getElementById('lead-capture-form-mount');
            var token = document.querySelector('meta[name="csrf-token"]');

            function setLoading(on) {
                if (!submitBtn) return;
                submitBtn.disabled = on;
                if (spinner) spinner.classList.toggle('d-none', !on);
                submitBtn.setAttribute('aria-busy', on ? 'true' : 'false');
            }

            function clearFieldErrors() {
                form.querySelectorAll('.is-invalid').forEach(function (el) {
                    el.classList.remove('is-invalid');
                });
                form.querySelectorAll('[data-error-for]').forEach(function (el) {
                    el.classList.add('d-none');
                    el.textContent = '';
                });
            }

            function fieldSelector(field) {
                if (field.indexOf('extra.') === 0) {
                    return 'extra[' + field.slice(6) + ']';
                }
                return field;
            }

            function showFieldErrors(errors) {
                Object.keys(errors).forEach(function (field) {
                    var name = fieldSelector(field);
                    var input = form.querySelector('[name="' + name + '"]');
                    var msg = errors[field] && errors[field][0] ? errors[field][0] : '';
                    if (input) {
                        input.classList.add('is-invalid');
                    }
                    var holder = form.querySelector('[data-error-for="' + field + '"]');
                    if (holder && msg) {
                        holder.textContent = msg;
                        holder.classList.remove('d-none');
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (serviceHidden && !serviceHidden.value) {
                    clearFieldErrors();
                    serviceHidden.classList.add('is-invalid');
                    var h = form.querySelector('[data-error-for="service_id"]');
                    if (h) {
                        h.textContent = '{{ __('This page does not have a service selected. Open a service landing from the menu.') }}';
                        h.classList.remove('d-none');
                    }
                    return;
                }
                if (errorBox) {
                    errorBox.classList.add('d-none');
                    errorBox.textContent = '';
                }
                if (successEl) successEl.classList.add('d-none');
                clearFieldErrors();
                setLoading(true);

                var fd = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token ? token.getAttribute('content') : '',
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                    body: fd,
                })
                    .then(function (r) {
                        return r
                            .json()
                            .then(function (data) {
                                return { ok: r.ok, status: r.status, data: data };
                            })
                            .catch(function () {
                                return { ok: r.ok, status: r.status, data: {} };
                            });
                    })
                    .then(function (result) {
                        setLoading(false);
                        var payload = result.data || {};
                        var ok =
                            result.ok &&
                            payload &&
                            (payload.status === true || payload.ok === true);
                        if (ok) {
                            var okMsg =
                                payload.message || '{{ __('Lead submitted successfully!') }}';
                            if (typeof window.leadsShowToast === 'function') {
                                window.leadsShowToast(okMsg, 'success');
                            }
                            if (mount) mount.classList.add('d-none');
                            if (successEl) {
                                successEl.classList.remove('d-none');
                                successEl.textContent = okMsg;
                            }
                            form.reset();
                            var defS = form.getAttribute('data-default-service-id');
                            if (defS && serviceHidden) serviceHidden.value = defS;
                            var ps = form.getAttribute('data-page-slug');
                            var sout = form.querySelector('input[name="source_page"]');
                            var cin = form.querySelector('input[name="utm_campaign"]');
                            var u1 = form.querySelector('input[name="utm_source"]');
                            var u2 = form.querySelector('input[name="utm_medium"]');
                            if (sout && ps) sout.value = ps;
                            if (cin || u1 || u2) {
                                var params = new URLSearchParams(window.location.search);
                                if (cin)
                                    cin.value =
                                        params.get('utm_campaign') || params.get('campaign') || '';
                                if (u1) u1.value = params.get('utm_source') || '';
                                if (u2) u2.value = params.get('utm_medium') || '';
                            }
                            if (typeof trackGoogleConversion === 'function') trackGoogleConversion();
                            if (typeof trackLeadGenerateEvent === 'function') trackLeadGenerateEvent();
                            return;
                        }
                        if (result.status === 422 && payload.errors) {
                            showFieldErrors(payload.errors);
                            var vMsg =
                                payload.message ||
                                '{{ __('Please check the form and try again.') }}';
                            if (typeof window.leadsShowToast === 'function') {
                                window.leadsShowToast(vMsg, 'warning');
                            }
                            return;
                        }
                        var msg =
                            payload.message ||
                            '{{ __('Something went wrong. Please try again.') }}';
                        if (typeof window.leadsShowToast === 'function') {
                            window.leadsShowToast(msg, 'error');
                        }
                        if (errorBox) {
                            errorBox.textContent = msg;
                            errorBox.classList.remove('d-none');
                        }
                    })
                    .catch(function () {
                        setLoading(false);
                        var netMsg = '{{ __('Network error. Check your connection and try again.') }}';
                        if (typeof window.leadsShowToast === 'function') {
                            window.leadsShowToast(netMsg, 'error');
                        }
                        if (errorBox) {
                            errorBox.textContent = netMsg;
                            errorBox.classList.remove('d-none');
                        }
                    });
            });
        })();
    </script>
@endpush
