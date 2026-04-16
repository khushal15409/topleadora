@extends('layouts.landing')

@section('body_class', 'inner-page blog-page')

@section('meta_title', 'Blog | WhatsApp CRM Tips & Guides | WhatsAppLeadCRM')
@section('meta_description', 'Articles on WhatsApp sales CRM, real estate lead follow-ups, pipeline management, and team collaboration.')
@section('meta_keywords', 'WhatsApp CRM, lead management system, sales CRM, pipeline, follow ups, CRM India')
@section('canonical_url', $posts->url($posts->currentPage()))

@section('content')
    <section class="section light-background blog-list-hero pt-5 mt-5">
        <div class="container section-title text-center" data-aos="fade-up">
            <h1 class="mb-2">Blog</h1>
            <p class="mb-0">Practical guides for WhatsApp-first sales and CRM workflows.</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            @if ($posts->isEmpty())
                <p class="text-center text-muted mb-0">No articles yet. Check back soon.</p>
            @else
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 200) }}">
                            <article class="blog-card h-100 d-flex flex-column">
                                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-image ratio ratio-16x9">
                                    @php
                                        $blogFallback = asset('front/images/leads-placeholder.svg');
                                        $blogImg = ! empty($post->image) ? asset($post->image) : $blogFallback;
                                    @endphp
                                    <img
                                        src="{{ $blogImg }}"
                                        alt="{{ $post->title }}"
                                        loading="lazy"
                                        width="640"
                                        height="360"
                                        style="width:100%;height:100%;object-fit:cover;"
                                        onerror="this.onerror=null;this.src='{{ $blogFallback }}';"
                                    >
                                </a>
                                <div class="blog-card-body d-flex flex-column flex-grow-1 p-3 p-lg-4">
                                    <h3 class="blog-card-title h5 mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    @if ($post->published_at)
                                        <time class="blog-card-date small text-muted d-block mb-2" datetime="{{ $post->published_at->toIso8601String() }}">
                                            {{ $post->published_at->format('M j, Y') }}
                                        </time>
                                    @endif
                                    <p class="blog-card-excerpt small text-muted flex-grow-1 mb-3">
                                        {{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 140) }}
                                    </p>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm align-self-start rounded-pill blog-read-more">
                                        Read more
                                    </a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {{ $posts->links() }}
                </div>
            @endif

            @if (isset($relatedServiceLeadLinks) && $relatedServiceLeadLinks !== [])
                <section class="mt-5 pt-5 border-top" aria-labelledby="blog-index-services-title">
                    <h2 id="blog-index-services-title" class="h5 fw-bold text-center mb-3">{{ __('Related services') }}</h2>
                    <ul class="list-unstyled row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 justify-content-center mb-0">
                        @foreach ($relatedServiceLeadLinks as $link)
                            <li class="col">
                                <a href="{{ $link['url'] }}" class="d-block h-100 p-3 rounded-3 border bg-light text-decoration-none text-center">
                                    <span class="fw-semibold text-body d-block">{{ $link['label'] }}</span>
                                    @if (! empty($link['hint']))
                                        <span class="small text-muted d-block mt-1">{{ $link['hint'] }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if (isset($marketingLandings) && $marketingLandings->isNotEmpty())
                <section class="mt-5 pt-5 border-top" aria-labelledby="blog-index-leads-title">
                    <h2 id="blog-index-leads-title" class="h5 fw-bold text-center mb-3">{{ __('Popular offer pages') }}</h2>
                    <p class="small text-muted text-center mb-4">{{ __('Explore India lead pages by service — same CRM also powers your inbound.') }}</p>
                    <ul class="list-unstyled row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 justify-content-center mb-0">
                        @foreach ($marketingLandings as $lp)
                            @continue($lp->service === null)
                            <li class="col">
                                <a href="{{ route('leads.landing', $lp->slug) }}" class="d-block h-100 p-3 rounded-3 border bg-white text-decoration-none shadow-sm text-center">
                                    <span class="fw-semibold text-body d-block">{{ $lp->service->name }}</span>
                                    <span class="small text-primary d-block mt-2">{{ __('Apply now') }} →</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </section>
@endsection
