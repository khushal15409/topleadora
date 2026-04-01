@extends('layouts.admin')

@section('title', 'Add organization')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.organizations.index') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line align-middle"></i>
            Back to organizations
        </a>
        <h4 class="mt-2 mb-1">Add organization</h4>
        <p class="mb-0 text-body-secondary">Create a tenant workspace. Assign a plan to skip trial, or leave empty for a 7-day trial.</p>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-0">Details</h5>
        </div>
        <form method="post" action="{{ route('admin.organizations.store') }}" class="card-body">
            @csrf
            @include('admin.organizations._form', ['organization' => null, 'plans' => $plans])

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.organizations.index') }}" class="btn btn-label-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
