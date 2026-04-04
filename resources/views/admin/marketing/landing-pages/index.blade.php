@extends('layouts.admin')

@section('title', __('Landing pages'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Landing pages') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('SEO, slug, and page content for /leads/{slug}') }}</p>
        </div>
        <a href="{{ route('admin.marketing.landing-pages.create') }}" class="btn btn-primary">{{ __('Add landing page') }}</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>{{ __('Slug') }}</th><th>{{ __('Service') }}</th><th>{{ __('Country') }}</th><th>{{ __('Active') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
                <tbody>
                @forelse ($pages as $p)
                    <tr>
                        <td><code>{{ $p->slug }}</code></td>
                        <td>{{ $p->service?->name }}</td>
                        <td>{{ $p->country?->name }}</td>
                        <td>@if ($p->is_active)<span class="badge bg-label-success">{{ __('Yes') }}</span>@else<span class="badge bg-label-secondary">{{ __('No') }}</span>@endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.marketing.landing-pages.edit', $p) }}" class="btn btn-sm btn-label-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.marketing.landing-pages.destroy', $p) }}" method="post" class="d-inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-label-danger">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-body-secondary">{{ __('No landing pages.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
