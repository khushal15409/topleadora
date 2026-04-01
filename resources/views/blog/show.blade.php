@extends('layouts.landing')

@section('body_class', 'inner-page blog-page')

@section('meta_title', $post->meta_title ?? $post->title.' | WhatsAppLeadCRM Blog')
@section('meta_description', $post->meta_description ?? $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 160))
@section('meta_keywords', 'WhatsApp CRM, Lead Management System, CRM India, Sales CRM, Follow-ups, Pipeline')
@section('meta_og_type', 'article')
@if ($post->image)
@section('meta_og_image', asset($post->image))
@endif

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
            <div class="container blog-article-featured py-4">
                <div class="ratio ratio-21x9 rounded-3 overflow-hidden shadow-sm blog-featured-ratio">
                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="object-fit-cover" width="1200" height="514">
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
                        <div class="mt-5 pt-4 border-top">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ url('/') }}" class="btn btn-label-secondary rounded-pill">Home</a>
                                <a href="{{ url('/#features') }}" class="btn btn-label-secondary rounded-pill">Features</a>
                                <a href="{{ route('blog.index') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="bi bi-arrow-left me-1"></i> Back to blog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    @push('scripts')
        @php
            $blogPostingLd = [
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => $post->title,
                'description' => $post->meta_description ?? $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 200),
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified' => $post->updated_at->toIso8601String(),
                'author' => [
                    '@type' => 'Organization',
                    'name' => 'WhatsAppLeadCRM',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'WhatsAppLeadCRM',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('front/images/logo.png'),
                    ],
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => url()->current(),
                ],
            ];
            if ($post->image) {
                $blogPostingLd['image'] = [asset($post->image)];
            }
        @endphp
        <script type="application/ld+json">
        {!! json_encode($blogPostingLd, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
        </script>
    @endpush
@endsection
