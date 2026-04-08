@extends('layouts.admin')

@section('title', __('My Profile'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('My Profile') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('User') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Profile') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('status'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center"
            role="alert">
            {{ session('status') }}
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <!-- Profile Banner -->
        <div class="col-span-12 mb-6">
            <div class="box !bg-primary/10 border border-primary/20 shadow-none overflow-hidden">
                <div class="box-body !p-0">
                    <div class="p-6 flex flex-wrap items-center gap-6">
                        <div class="avatar avatar-xxl bg-primary text-white rounded-full text-2xl font-bold shadow-lg">
                            {{ strtoupper(\Illuminate\Support\Str::substr($user->name, 0, 2)) }}
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-xl font-bold text-defaulttextcolor mb-1">{{ $user->name }}</h4>
                            <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-textmuted">
                                <span class="flex items-center"><i class="ri-mail-line me-1"></i> {{ $user->email }}</span>
                                @if($user->phone)
                                    <span class="flex items-center"><i class="ri-phone-line me-1"></i> {{ $user->phone }}</span>
                                @endif
                                @if ($user->roles->isNotEmpty())
                                    <span class="flex items-center"><i class="ri-shield-user-line me-1"></i>
                                        {{ $user->roles->pluck('name')->join(' · ') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="col-span-12 lg:col-span-6">
            <div class="box h-full">
                <div class="box-header border-b">
                    <h5 class="box-title font-semibold">{{ __('Account Details') }}</h5>
                    <p class="text-textmuted text-xs mt-1">{{ __('Update your personal information and contact details.') }}
                    </p>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="profile_name"
                                    class="block text-sm font-medium mb-2">{{ __('Full Name') }}</label>
                                <input type="text" class="ti-form-input @error('name') !border-danger @enderror"
                                    id="profile_name" name="name" value="{{ old('name', $user->name) }}" required
                                    autocomplete="name">
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="profile_email"
                                    class="block text-sm font-medium mb-2">{{ __('Email Address') }}</label>
                                <input type="email" class="ti-form-input @error('email') !border-danger @enderror"
                                    id="profile_email" name="email" value="{{ old('email', $user->email) }}" required
                                    autocomplete="email">
                                @error('email')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="profile_phone" class="block text-sm font-medium mb-2">{{ __('Phone Number') }}
                                    <span class="text-textmuted text-xs font-normal">({{ __('Optional') }})</span></label>
                                <input type="text" class="ti-form-input @error('phone') !border-danger @enderror"
                                    id="profile_phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    autocomplete="tel" placeholder="+1 555 0000">
                                @error('phone')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="ti-btn ti-btn-primary px-6">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="col-span-12 lg:col-span-6">
            <div class="box h-full">
                <div class="box-header border-b">
                    <h5 class="box-title font-semibold">{{ __('Security Settings') }}</h5>
                    <p class="text-textmuted text-xs mt-1">
                        {{ __('Update your password regularly to keep your account secure.') }}</p>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ route('admin.profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="current_password"
                                    class="block text-sm font-medium mb-2">{{ __('Current Password') }}</label>
                                <input type="password"
                                    class="ti-form-input @error('current_password') !border-danger @enderror"
                                    id="current_password" name="current_password" required autocomplete="current-password">
                                @error('current_password')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password"
                                    class="block text-sm font-medium mb-2">{{ __('New Password') }}</label>
                                <input type="password" class="ti-form-input @error('password') !border-danger @enderror"
                                    id="password" name="password" required autocomplete="new-password">
                                @error('password')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium mb-2">{{ __('Confirm New Password') }}</label>
                                <input type="password" class="ti-form-input" id="password_confirmation"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="ti-btn ti-btn-primary px-6">
                                {{ __('Update Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection