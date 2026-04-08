@extends('gcc.layouts.app')

@section('title', __('Edit country'))

@section('content')
    <h4 class="mb-4">{{ __('Edit country') }}</h4>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.marketing.countries.update', $country) }}" method="post" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-4">
                <label class="form-label">{{ __('ISO code') }} *</label>
                <input type="text" name="code" maxlength="2" class="form-control text-uppercase @error('code') is-invalid @enderror" value="{{ old('code', $country->code) }}" required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label">{{ __('Name') }} *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $country->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('URL slug') }} *</label>
                <input type="text" name="url_slug" class="form-control @error('url_slug') is-invalid @enderror" value="{{ old('url_slug', $country->url_slug) }}" required>
                @error('url_slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Sort') }}</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $country->sort_order) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $country->is_active))>
                    <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <a href="{{ route('admin.marketing.countries.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div></div>
@endsection
