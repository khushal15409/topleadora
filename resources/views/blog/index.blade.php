@extends('layouts.landing')

@section('body_class', 'inner-page blog-page')

@section('meta_title', 'Blog | WhatsApp CRM Tips & Guides | WhatsAppLeadCRM')
@section('meta_description', 'Articles on WhatsApp sales CRM, real estate lead follow-ups, pipeline management, and team collaboration.')
@section('meta_keywords', 'WhatsApp CRM, lead management system, sales CRM, pipeline, follow ups, CRM India')

@section('content')
    <section class="section light-background blog-list-hero pt-5 mt-5">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>Blog</h2>
            <p>Practical guides for WhatsApp-first sales and CRM workflows.</p>
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
                                    @if ($post->image)
                                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" loading="lazy" width="640" height="360">
                                    @else
                                        <div class="blog-card-image-placeholder d-flex align-items-center justify-content-center">
                                            <i class="bi bi-journal-text fs-1 text-muted"></i>
                                        </div>
                                    @endif
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
        </div>
    </section>
@endsection
