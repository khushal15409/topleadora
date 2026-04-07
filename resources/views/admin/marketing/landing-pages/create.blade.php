@extends('layouts.admin')

@section('title', __('Add landing page'))

@section('content')
    <h4 class="mb-4">{{ __('Add landing page') }}</h4>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.marketing.landing-pages.store') }}" method="post" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">{{ __('Service') }} *</label>
                <select name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                    <option value="">{{ __('Select…') }}</option>
                    @foreach ($services as $s)
                        <option value="{{ $s->id }}" @selected(old('service_id') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('service_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Country') }} *</label>
                <select name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                    <option value="">{{ __('Select…') }}</option>
                    @foreach ($countries as $c)
                        <option value="{{ $c->id }}" @selected(old('country_id') == $c->id)>{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('URL slug') }} *</label>
                <input type="text" name="slug" pattern="[a-z0-9\-]+" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="loan-ahmedabad" required>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">{{ __('Programmatic SEO: one row per URL. Same service + country with different slugs (e.g. loan-mumbai, loan-ahmedabad).') }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('City slug (optional)') }}</label>
                <input type="text" name="city_slug" pattern="[a-z0-9\-]*" class="form-control @error('city_slug') is-invalid @enderror" value="{{ old('city_slug') }}" placeholder="ahmedabad">
                @error('city_slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('City label (optional)') }}</label>
                <input type="text" name="city_label" maxlength="128" class="form-control @error('city_label') is-invalid @enderror" value="{{ old('city_label') }}" placeholder="Ahmedabad">
                @error('city_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">{{ __('Shown when building meta fallbacks and location copy.') }}</div>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Meta title') }} *</label>
                <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title') }}" required>
                @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Meta description') }}</label>
                <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Meta keywords') }}</label>
                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Robots') }}</label>
                <input type="text" name="robots_meta" class="form-control" value="{{ old('robots_meta', 'index,follow') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Sort') }}</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))>
                    <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Long SEO body (HTML allowed)') }}</label>
                <textarea name="seo_body" class="form-control font-monospace small" rows="8">{{ old('seo_body') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Content JSON (hero, benefits, faqs)') }}</label>
                <textarea name="content_json" class="form-control font-monospace small" rows="10" placeholder="{}">{{ old('content_json') }}</textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('admin.marketing.landing-pages.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div></div>
@endsection
