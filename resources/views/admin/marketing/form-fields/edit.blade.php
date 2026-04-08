@extends('gcc.layouts.app')

@section('title', __('Edit form field'))

@section('content')
    <h4 class="mb-4">{{ __('Edit form field') }}</h4>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.marketing.form-fields.update', $field) }}" method="post" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-6">
                <label class="form-label">{{ __('Field key') }} *</label>
                <input type="text" name="field_key" class="form-control @error('field_key') is-invalid @enderror" value="{{ old('field_key', $field->field_key) }}" required>
                @error('field_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Label') }} *</label>
                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $field->label) }}" required>
                @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Type') }} *</label>
                <select name="field_type" class="form-select" required>
                    @foreach (['text', 'textarea', 'email'] as $t)
                        <option value="{{ $t }}" @selected(old('field_type', $field->field_type) === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Sort') }}</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $field->sort_order) }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_required" value="1" id="req" @checked(old('is_required', $field->is_required))>
                    <label class="form-check-label" for="req">{{ __('Required') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="act" @checked(old('is_active', $field->is_active))>
                    <label class="form-check-label" for="act">{{ __('Active') }}</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <a href="{{ route('admin.marketing.form-fields.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div></div>
@endsection
