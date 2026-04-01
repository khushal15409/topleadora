@extends('layouts.admin')

@section('title', 'My profile')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">My profile</h4>
        <p class="mb-0 text-body-secondary">
            Update how you appear in {{ config('app.name', 'WP-CRM') }} and keep your sign-in secure.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Account details</h5>
                    <small class="text-body-secondary">Name, email, and optional phone for your workspace.</small>
                </div>
                <form method="post" action="{{ route('admin.profile.update') }}" class="card-body">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="profile_name" class="form-label">Full name</label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="profile_name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            autocomplete="name"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="profile_email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="profile_email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="profile_phone" class="form-label">Phone <span class="text-muted fw-normal">(optional)</span></label>
                        <input
                            type="text"
                            class="form-control @error('phone') is-invalid @enderror"
                            id="profile_phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            autocomplete="tel"
                            placeholder="+1 555 0100"
                        >
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Security</h5>
                    <small class="text-body-secondary">Use a strong password you do not reuse elsewhere.</small>
                </div>
                <form method="post" action="{{ route('admin.profile.password') }}" class="card-body">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current password</label>
                        <input
                            type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            required
                        >
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">Update password</button>
                </form>
            </div>
        </div>

        <div class="col-12">
            <div class="card border border-primary border-opacity-25 bg-label-primary bg-opacity-10">
                <div class="card-body py-3 d-flex flex-wrap align-items-center gap-3">
                    <div class="avatar avatar-md">
                        <span class="avatar-initial rounded-circle bg-primary">{{ strtoupper(\Illuminate\Support\Str::substr($user->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-medium">{{ $user->name }}</div>
                        <small class="text-body-secondary d-block text-truncate">{{ $user->email }}</small>
                        @if ($user->roles->isNotEmpty())
                            <small class="text-muted">{{ $user->roles->pluck('name')->join(' · ') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
