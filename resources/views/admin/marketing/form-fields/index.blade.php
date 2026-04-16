@extends('gcc.layouts.app')

@section('title', __('Marketing form fields'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Form fields') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Dynamic fields stored on each marketing lead under “extra”.') }}</p>
        </div>
        <a href="{{ route('admin.marketing.form-fields.create') }}" class="btn btn-primary">{{ __('Add field') }}</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>{{ __('Key') }}</th><th>{{ __('Label') }}</th><th>{{ __('Type') }}</th><th>{{ __('Required') }}</th><th>{{ __('Active') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
                <tbody>
                @forelse ($fields as $f)
                    <tr>
                        <td><code>{{ $f->field_key }}</code></td>
                        <td>{{ $f->label }}</td>
                        <td>{{ $f->field_type }}</td>
                        <td>@if ($f->is_required){{ __('Yes') }}@else — @endif</td>
                        <td>@if ($f->is_active)<span class="badge bg-label-success">{{ __('Yes') }}</span>@else<span class="badge bg-label-secondary">{{ __('No') }}</span>@endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.marketing.form-fields.edit', $f) }}" class="btn btn-sm btn-label-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.marketing.form-fields.destroy', $f) }}" method="post" class="d-inline" data-confirm="{{ __('Delete?') }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-label-danger">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-body-secondary">{{ __('No custom fields.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
