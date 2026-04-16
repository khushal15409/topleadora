@extends('gcc.layouts.app')

@section('title', __('Marketing services'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Marketing services') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Public “Service” options on lead landings.') }}</p>
        </div>
        <a href="{{ route('admin.marketing.services.create') }}" class="btn btn-primary">{{ __('Add service') }}</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->has('delete'))
        <div class="alert alert-danger alert-dismissible">{{ $errors->first('delete') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Slug') }}</th><th>{{ __('Active') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
                <tbody>
                @forelse ($services as $s)
                    <tr>
                        <td class="fw-medium">{{ $s->name }}</td>
                        <td><code>{{ $s->slug }}</code></td>
                        <td>@if ($s->is_active)<span class="badge bg-label-success">{{ __('Yes') }}</span>@else<span class="badge bg-label-secondary">{{ __('No') }}</span>@endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.marketing.services.edit', $s) }}" class="btn btn-sm btn-label-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.marketing.services.destroy', $s) }}" method="post" class="d-inline" data-confirm="{{ __('Delete this service?') }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-label-danger">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-body-secondary">{{ __('No services yet.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
