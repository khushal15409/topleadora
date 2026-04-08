@extends('gcc.layouts.app')

@section('title', 'User '.$user->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line me-1"></i>
            Back to organization users
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User details</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0 gy-3">
                <dt class="col-sm-3 text-body-secondary">Name</dt>
                <dd class="col-sm-9 mb-0 fw-medium">{{ $user->name }}</dd>

                <dt class="col-sm-3 text-body-secondary">Email</dt>
                <dd class="col-sm-9 mb-0">{{ $user->email }}</dd>

                <dt class="col-sm-3 text-body-secondary">Organization</dt>
                <dd class="col-sm-9 mb-0">{{ $user->organization?->name ?? '—' }}</dd>

                <dt class="col-sm-3 text-body-secondary">Role</dt>
                <dd class="col-sm-9 mb-0">
                    @forelse ($user->roles as $role)
                        <span class="badge rounded-pill bg-label-primary me-1">{{ $role->name }}</span>
                    @empty
                        —
                    @endforelse
                </dd>

                <dt class="col-sm-3 text-body-secondary">Status</dt>
                <dd class="col-sm-9 mb-0">
                    @if ($user->status === \App\Models\User::STATUS_ACTIVE)
                        <span class="badge rounded-pill bg-label-success">Active</span>
                    @else
                        <span class="badge rounded-pill bg-label-secondary">Inactive</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-body-secondary">Created</dt>
                <dd class="col-sm-9 mb-0 text-body-secondary small">{{ $user->created_at?->format('M j, Y H:i') ?? '—' }}</dd>
            </dl>
        </div>
    </div>
@endsection
