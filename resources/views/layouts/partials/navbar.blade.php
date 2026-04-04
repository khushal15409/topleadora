<nav
    class="saas-navbar layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar"
>
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base ri ri-menu-line icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-between w-100" id="navbar-collapse">
        <div class="d-flex flex-column justify-content-center">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0 text-heading">@yield('title', __('Dashboard'))</h5>
            </div>
            <nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb mb-0 small">
                    @stack('breadcrumbs')
                </ol>
            </nav>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <li class="nav-item me-2">
                <a class="nav-link p-0" href="javascript:void(0);" title="{{ __('Notifications') }}">
                    <i class="icon-base ri ri-notification-3-line icon-md"></i>
                </a>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 2)) }}</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                    <small class="text-body-secondary d-block">{{ auth()->user()->email }}</small>
                                    @if (auth()->user()->roles->isNotEmpty())
                                        <small class="text-muted">{{ auth()->user()->roles->pluck('name')->join(' · ') }}</small>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                            <i class="icon-base ri ri-user-line icon-md me-3"></i>
                            <span>My profile</span>
                        </a>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <form action="{{ route('logout') }}" method="post" class="d-grid px-2 pb-1">
                            @csrf
                            <button type="submit" class="dropdown-item text-start rounded-2">
                                <i class="icon-base ri ri-logout-box-r-line icon-md me-3"></i>
                                <span>Log out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
