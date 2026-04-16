@extends('layouts.landing')

@section('body_class', 'inner-page blog-page')

{{-- BlogController passes $meta from SeoMeta::fallbackForBlogPost (unique title + description when fields empty). --}}
@section('meta_title', $meta['title'])
@section('meta_description', $meta['description'])
@section('meta_keywords', 'WhatsApp CRM, Lead Management System, CRM India, Sales CRM, Follow-ups, Pipeline')
@section('meta_og_type', 'article')
@section('canonical_url', \App\Support\SeoMeta::canonical('blog.show', $post->slug))
@section('meta_og_image', \App\Support\SeoMeta::ogImageForBlog($post))

@push('json_ld')
    @php
        $publisherName = config('app.name', 'WhatsAppLeadCRM');
        $logoUrl = asset('front/images/logo.png');
        $articleUrl = \App\Support\SeoMeta::canonical('blog.show', $post->slug);
        $published = $post->published_at ?? $post->created_at;
        $modified = $post->updated_at ?? $published;
        $ogImg = \App\Support\SeoMeta::ogImageForBlog($post);
        $blogPosting = [
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => strip_tags($meta['description']),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $articleUrl,
            ],
            'author' => [
                '@type' => 'Organization',
                'name' => $publisherName,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $publisherName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logoUrl,
                ],
            ],
        ];
        if ($published !== null) {
            $blogPosting['datePublished'] = $published->toIso8601String();
        }
        if ($modified !== null) {
            $blogPosting['dateModified'] = $modified->toIso8601String();
        }
        $blogPosting['image'] = [
            '@type' => 'ImageObject',
            'url' => $ogImg,
        ];
        $breadcrumbList = [
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
                    'name' => 'Blog',
                    'item' => route('blog.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => \Illuminate\Support\Str::limit($post->title, 70),
                    'item' => $articleUrl,
                ],
            ],
        ];
        $blogArticleLd = [
            '@context' => 'https://schema.org',
            '@graph' => [$blogPosting, $breadcrumbList],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($blogArticleLd, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>
@endpush

@section('content')
    <article class="blog-article">
        <header class="section light-background blog-article-header pt-5 mt-5">
            <div class="container">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($post->title, 48) }}</li>
                    </ol>
                </nav>
                <h1 class="display-6 fw-bold mb-3">{{ $post->title }}</h1>
                @if ($post->published_at)
                    <p class="text-muted small mb-0">
                        <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->format('F j, Y') }}</time>
                    </p>
                @endif
            </div>
        </header>

        @if ($post->image)
            {{-- Featured image: not lazy-loaded (typical LCP for article layout). --}}
            <div class="container blog-article-featured py-4">
                <div class="ratio ratio-21x9 rounded-3 overflow-hidden shadow-sm blog-featured-ratio">
                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="object-fit-cover" width="1200" height="514" fetchpriority="high">
                </div>
            </div>
        @endif

        <div class="section pt-0 pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 blog-article-body">
                        @if ($post->excerpt)
                            <p class="lead text-muted">{{ $post->excerpt }}</p>
                        @endif
                        <div class="blog-prose">
                            {!! $post->body !!}
                        </div>

                        {{-- Optional: faq_json on blog_posts — outputs H2 + FAQPage schema. --}}
                        <x-blog.article-faq :items="$post->faq_json ?? []" />

                        @if (isset($relatedServiceLeadLinks) && $relatedServiceLeadLinks !== [])
                            <section class="mt-5 pt-4 border-top" aria-labelledby="blog-related-services-title">
                                <h2 id="blog-related-services-title" class="h5 fw-bold mb-3">{{ __('Related services') }}</h2>
                                <p class="small text-muted mb-3">{{ __('Explore India lead pages by service.') }}</p>
                                <ul class="list-unstyled row row-cols-1 row-cols-sm-2 g-2 mb-0">
                                    @foreach ($relatedServiceLeadLinks as $link)
                                        <li class="col">
                                            <a href="{{ $link['url'] }}" class="d-block p-3 rounded-3 border bg-light text-decoration-none">
                                                <span class="fw-semibold text-body d-block">{{ $link['label'] }}</span>
                                                @if (! empty($link['hint']))
                                                    <span class="small text-muted d-block">{{ $link['hint'] }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        <div class="mt-5 pt-4 border-top">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ url('/') }}" class="btn btn-label-secondary rounded-pill">Home</a>
                                <a href="{{ route('features') }}" class="btn btn-label-secondary rounded-pill">{{ __('Features') }}</a>
                                <a href="{{ route('pricing') }}" class="btn btn-label-secondary rounded-pill">{{ __('Pricing') }}</a>
                                <a href="{{ route('blog.index') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="bi bi-arrow-left me-1"></i> Back to blog
                                </a>
                            </div>
                        </div>

                        @if (isset($relatedPosts) && $relatedPosts->isNotEmpty())
                            <section class="mt-5 pt-4 border-top" aria-labelledby="blog-related-title">
                                <h2 id="blog-related-title" class="h5 fw-bold mb-3">{{ __('Related articles') }}</h2>
                                <ul class="list-unstyled row row-cols-1 g-3 mb-0">
                                    @foreach ($relatedPosts as $rel)
                                        <li class="col">
                                            <a href="{{ route('blog.show', $rel->slug) }}" class="d-block p-3 rounded-3 border bg-white text-decoration-none shadow-sm">
                                                <span class="fw-semibold text-body d-block">{{ $rel->title }}</span>
                                                @if ($rel->published_at)
                                                    <time class="small text-muted d-block mt-1" datetime="{{ $rel->published_at->toIso8601String() }}">{{ $rel->published_at->format('M j, Y') }}</time>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        @if (isset($marketingLandings) && $marketingLandings->isNotEmpty())
                            <section class="mt-5 pt-4 border-top" aria-labelledby="blog-leads-title">
                                <h2 id="blog-leads-title" class="h5 fw-bold mb-3">{{ __('Apply online in India') }}</h2>
                                <p class="small text-muted mb-3">{{ __('Fast, secure lead forms — choose a service to get started.') }}</p>
                                <ul class="list-unstyled row row-cols-1 row-cols-md-2 g-3 mb-0">
                                    @php
                                        // De-duplicate by service so "Loan" doesn't repeat across cities/pages.
                                        $uniqueMarketingLandings = $marketingLandings
                                            ->filter(fn ($lp) => $lp->service !== null)
                                            ->unique(fn ($lp) => (int) ($lp->service->id ?? 0))
                                            ->values()
                                            ->take(8);

                                        // If the API/data only contains 1–2 services (e.g., only Loan),
                                        // show a diverse fallback mix so the section looks complete.
                                        $needsFallbackMix = $uniqueMarketingLandings->count() < 3;
                                        $fallbackOffers = [
                                            ['label' => 'Loan', 'slug' => 'loan'],
                                            ['label' => 'Insurance', 'slug' => 'insurance'],
                                            ['label' => 'Real Estate', 'slug' => 'real-estate'],
                                            ['label' => 'Education', 'slug' => 'education'],
                                            ['label' => 'Solar', 'slug' => 'solar'],
                                            ['label' => 'Business', 'slug' => 'business'],
                                            ['label' => 'Legal', 'slug' => 'legal'],
                                            ['label' => 'Auto', 'slug' => 'auto'],
                                        ];
                                    @endphp

                                    @if (! $needsFallbackMix)
                                        @foreach ($uniqueMarketingLandings as $lp)
                                            <li class="col">
                                                <a href="{{ route('leads.landing', $lp->slug) }}" class="d-block h-100 p-3 rounded-3 border bg-white text-decoration-none shadow-sm">
                                                    <span class="fw-semibold text-body d-block">{{ $lp->service->name }}</span>
                                                    <span class="small text-muted d-block mt-1">{{ __('Get free consultation') }} →</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        @foreach (array_slice($fallbackOffers, 0, 8) as $item)
                                            <li class="col">
                                                <a href="{{ url('/leads/' . $item['slug']) }}" class="d-block h-100 p-3 rounded-3 border bg-white text-decoration-none shadow-sm">
                                                    <span class="fw-semibold text-body d-block">{{ $item['label'] }}</span>
                                                    <span class="small text-muted d-block mt-1">{{ __('Get free consultation') }} →</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </article>
@endsection
