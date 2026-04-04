<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a
            href="{{ route('admin.dashboard') }}"
            class="app-brand-link d-flex align-items-center gap-2 min-w-0 text-decoration-none sidebar-app-brand-link"
            title="{{ config('app.name', 'WP-CRM') }}"
        >
            <span class="sidebar-logo-brand">
                <img
                    src="{{ asset('front/images/logo.png') }}"
                    alt="{{ config('app.name', 'WP-CRM') }}"
                    width="160"
                    height="40"
                    class="sidebar-logo-brand__img"
                    loading="eager"
                >
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @role(\App\Support\Roles::SUPER_ADMIN)
            <li class="menu-header mt-2">
                <span class="menu-header-text">Super Admin</span>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                    <div>Dashboard</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
                <a href="{{ route('admin.organizations.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-building-4-line"></i>
                    <div>Organizations</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <a href="{{ route('admin.contacts.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-mail-line"></i>
                    <div>Contact Messages</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.marketing.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-advertisement-line"></i>
                    <div>Marketing</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('admin.marketing.services.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.marketing.services.index') }}" class="menu-link"><div>Services</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.marketing.countries.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.marketing.countries.index') }}" class="menu-link"><div>Countries</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.marketing.landing-pages.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.marketing.landing-pages.index') }}" class="menu-link"><div>Landing pages</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.marketing.form-fields.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.marketing.form-fields.index') }}" class="menu-link"><div>Form fields</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.marketing.leads.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.marketing.leads.index') }}" class="menu-link"><div>Marketing Leads</div></a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.subscriptions.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-repeat-line"></i>
                    <div>Subscriptions</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}">
                <a href="{{ route('admin.revenue.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-line-chart-line"></i>
                    <div>Revenue Analytics</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-group-line"></i>
                    <div>Organization Users</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <a href="{{ route('admin.roles.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-shield-user-line"></i>
                    <div>Roles</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.broadcast-usage.*') ? 'active' : '' }}">
                <a href="{{ route('admin.broadcast-usage.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-megaphone-line"></i>
                    <div>Broadcast Usage</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <a href="{{ route('admin.analytics.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-bar-chart-box-line"></i>
                    <div>Analytics</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.integrations.*') ? 'active' : '' }}">
                <a href="{{ route('admin.integrations.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-plug-line"></i>
                    <div>Integrations</div>
                </a>
            </li>
        @endrole

        @role(\App\Support\Roles::ORGANIZATION)
            <li class="menu-header mt-2">
                <span class="menu-header-text">Organization</span>
            </li>

            @if (($orgCrmLocked ?? false) && paymentEnabled())
                <li class="menu-item {{ request()->routeIs('admin.organization.plan') ? 'active' : '' }}">
                    <a href="{{ route('admin.organization.plan') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-vip-crown-line"></i>
                        <div>My plan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.subscription.pricing') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscription.pricing') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-price-tag-3-line"></i>
                        <div>Plans &amp; pricing</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.checkout') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscription.pricing') }}#plans" class="menu-link">
                        <i class="menu-icon icon-base ri ri-bank-card-line"></i>
                        <div>Payment / upgrade</div>
                    </a>
                </li>
                <li class="menu-item disabled">
                    <span class="menu-link text-muted" tabindex="-1" aria-disabled="true">
                        <i class="menu-icon icon-base ri ri-lock-line"></i>
                        <div>CRM locked — upgrade to continue</div>
                    </span>
                </li>
            @else
                <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                        <div>Dashboard</div>
                    </a>
                </li>
                @if (paymentEnabled())
                    <li class="menu-item {{ request()->routeIs('admin.organization.plan') ? 'active' : '' }}">
                        <a href="{{ route('admin.organization.plan') }}" class="menu-link">
                            <i class="menu-icon icon-base ri ri-vip-crown-line"></i>
                            <div>My plan</div>
                        </a>
                    </li>
                @endif
                <li class="menu-item {{ request()->routeIs('dashboard.leads.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.leads.index') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-user-search-line"></i>
                        <div>Leads</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.pipeline.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.pipeline.index') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-git-merge-line"></i>
                        <div>Pipeline</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.followups.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.followups.index') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-time-line"></i>
                        <div>Follow-ups</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.broadcast.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.broadcast.index') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-send-plane-line"></i>
                        <div>Broadcast</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.reports.index') }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-file-chart-line"></i>
                        <div>Reports</div>
                    </a>
                </li>
            @endif
        @endrole

        @unless (auth()->user()?->hasAnyRole([\App\Support\Roles::SUPER_ADMIN, \App\Support\Roles::ORGANIZATION]))
            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                    <div>Dashboard</div>
                </a>
            </li>
        @endunless
    </ul>
</aside>
