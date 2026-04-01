@extends('layouts.admin')

@section('title', 'Edit organization')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.organizations.index') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line align-middle"></i>
            Back to organizations
        </a>
        <h4 class="mt-2 mb-1">Edit organization</h4>
        <p class="mb-0 text-body-secondary">{{ $organization->slug }}</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Workspace</h5>
            <form
                action="{{ route('admin.organizations.destroy', $organization) }}"
                method="post"
                class="d-inline"
                onsubmit="return confirm('Delete this organization? Users must be detached first.');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-label-danger">
                    <i class="icon-base ri ri-delete-bin-6-line me-1"></i>
                    Delete
                </button>
            </form>
        </div>
        <form method="post" action="{{ route('admin.organizations.update', $organization) }}" class="card-body">
            @csrf
            @method('PUT')
            @include('admin.organizations._form', ['organization' => $organization, 'plans' => $plans])

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="{{ route('admin.organizations.index') }}" class="btn btn-label-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
