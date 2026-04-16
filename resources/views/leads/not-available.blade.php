@extends('layouts.leads')

@section('meta_title', $metaTitle ?? 'Service not available yet')
@section('meta_description', $metaDescription ?? 'This service category is not available yet. Explore other services or contact us.')
@section('meta_keywords', 'services, leads, contact')
@section('meta_robots', 'noindex,follow')

@section('content')
    <section class="leads-hero">
        <div class="container-xl">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="display-6 fw-bold mb-2">{{ __('Service not available yet in this category') }}</h1>
                    <p class="lead text-body-secondary mb-4">
                        {{ __('The page you requested is not live yet. You can explore other services below or contact us and we will guide you to the right option.') }}
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('contact') }}" class="btn btn-primary">{{ __('Contact us') }}</a>
                        <a href="{{ url('/#features') }}" class="btn btn-outline-secondary">{{ __('Explore the platform') }}</a>
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">{{ __('Read guides') }}</a>
                    </div>
                    @if (! empty($slug))
                        <p class="small text-body-secondary mt-3 mb-0">
                            {{ __('Requested slug:') }} <code>{{ $slug }}</code>
                        </p>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">{{ __('Popular service categories') }}</h2>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach (($leadNavItems ?? collect())->take(10) as $item)
                                    <a href="{{ $item['url'] }}" class="btn btn-sm btn-label-secondary">{{ $item['label'] }}</a>
                                @endforeach
                            </div>
                            <div class="mt-3 small text-body-secondary">
                                {{ __('If you don’t see your category here, use the contact form and share what you’re looking for.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

