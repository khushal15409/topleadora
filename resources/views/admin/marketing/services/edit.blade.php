@extends('gcc.layouts.app')

@section('title', __('Edit service'))

@section('content')
    <h4 class="mb-4">{{ __('Edit service') }}</h4>

    <div class="card"><div class="card-body">
        <form action="{{ route('admin.marketing.services.update', $service) }}" method="post" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-6">
                <label class="form-label">{{ __('Name') }} *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $service->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Slug') }} *</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $service->slug) }}" required>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Sort order') }}</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order) }}" min="0">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $service->is_active))>
                    <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Default page JSON') }}</label>
                <textarea name="default_content_json" class="form-control font-monospace small" rows="10">{{ old('default_content_json', $service->default_content_json ? json_encode($service->default_content_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                @error('default_content_json')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <a href="{{ route('admin.marketing.services.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div></div>
@endsection
