@extends('gcc.layouts.app')

@section('title', __('Countries'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Countries') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('ISO codes, display names, and URL slugs for landings.') }}</p>
        </div>
        <a href="{{ route('admin.marketing.countries.create') }}" class="btn btn-primary">{{ __('Add country') }}</a>
    </div>

    <form method="get" class="card card-body mb-3 py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small">{{ __('Search') }}</label>
                <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="{{ __('Name or code') }}">
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('admin.marketing.countries.index') }}" class="btn btn-label-secondary">{{ __('Reset') }}</a>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->has('delete'))
        <div class="alert alert-danger">{{ $errors->first('delete') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>{{ __('Code') }}</th><th>{{ __('Name') }}</th><th>{{ __('URL slug') }}</th><th>{{ __('Active') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
                <tbody>
                @forelse ($countries as $c)
                    <tr>
                        <td><code>{{ $c->code }}</code></td>
                        <td>{{ $c->name }}</td>
                        <td><code>{{ $c->url_slug }}</code></td>
                        <td>@if ($c->is_active)<span class="badge bg-label-success">{{ __('Yes') }}</span>@else<span class="badge bg-label-secondary">{{ __('No') }}</span>@endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.marketing.countries.edit', $c) }}" class="btn btn-sm btn-label-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.marketing.countries.destroy', $c) }}" method="post" class="d-inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-label-danger">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-body-secondary">{{ __('No countries.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($countries->hasPages())
            <div class="card-body">{{ $countries->links() }}</div>
        @endif
    </div>
@endsection
