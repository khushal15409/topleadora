@extends('layouts.admin')

@section('title', __('Edit landing page'))

@section('content')
    <h4 class="mb-4">{{ __('Edit landing page') }} <code>{{ $landingPage->slug }}</code></h4>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.marketing.landing-pages.update', $landingPage) }}" method="post" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-6">
                <label class="form-label">{{ __('Service') }} *</label>
                <select name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                    @foreach ($services as $s)
                        <option value="{{ $s->id }}" @selected(old('service_id', $landingPage->service_id) == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('service_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Country') }} *</label>
                <select name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                    @foreach ($countries as $c)
                        <option value="{{ $c->id }}" @selected(old('country_id', $landingPage->country_id) == $c->id)>{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('URL slug') }} *</label>
                <input type="text" name="slug" pattern="[a-z0-9\-]+" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $landingPage->slug) }}" required>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Meta title') }} *</label>
                <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $landingPage->meta_title) }}" required>
                @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Meta description') }}</label>
                <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $landingPage->meta_description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Meta keywords') }}</label>
                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $landingPage->meta_keywords) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Robots') }}</label>
                <input type="text" name="robots_meta" class="form-control" value="{{ old('robots_meta', $landingPage->robots_meta) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Sort') }}</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $landingPage->sort_order) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $landingPage->is_active))>
                    <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Long SEO body') }}</label>
                <textarea name="seo_body" class="form-control font-monospace small" rows="8">{{ old('seo_body', $landingPage->seo_body) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Content JSON') }}</label>
                <textarea name="content_json" class="form-control font-monospace small" rows="12">{{ old('content_json', $landingPage->content_json ? json_encode($landingPage->content_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <a href="{{ route('admin.marketing.landing-pages.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div></div>
@endsection
