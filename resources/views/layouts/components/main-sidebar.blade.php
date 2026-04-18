@php
    $__crmBrandLogo = asset('front/images/logo.png');
    $__crmBrandFavicon = asset(config('branding.favicon', 'front/images/landify/favicon.png'));
@endphp
<aside class="app-sidebar" id="sidebar">

	<!-- Start::main-sidebar-header -->
	<div class="main-sidebar-header">
		<a href="{{ route('admin.dashboard') }}" class="header-logo" data-favicon-url="{{ $__crmBrandFavicon }}">
			<img src="{{ $__crmBrandLogo }}" alt="{{ config('app.name', 'TopLeadOra') }}" data-brand-src="{{ $__crmBrandLogo }}" class="crm-sidebar-logo desktop-logo w-auto max-w-full object-contain">
			<img src="{{ $__crmBrandLogo }}" alt="{{ config('app.name', 'TopLeadOra') }}" data-brand-src="{{ $__crmBrandLogo }}" class="crm-sidebar-logo toggle-logo w-auto max-w-full object-contain">
			<img src="{{ $__crmBrandLogo }}" alt="{{ config('app.name', 'TopLeadOra') }}" data-brand-src="{{ $__crmBrandLogo }}" class="crm-sidebar-logo desktop-dark w-auto max-w-full object-contain">
			<img src="{{ $__crmBrandLogo }}" alt="{{ config('app.name', 'TopLeadOra') }}" data-brand-src="{{ $__crmBrandLogo }}" class="crm-sidebar-logo toggle-dark w-auto max-w-full object-contain">
			<img src="{{ $__crmBrandLogo }}" alt="{{ config('app.name', 'TopLeadOra') }}" data-brand-src="{{ $__crmBrandLogo }}" class="crm-sidebar-logo desktop-white w-auto max-w-full object-contain">
		</a>
	</div>
	<!-- End::main-sidebar-header -->

	<!-- Start::main-sidebar -->
	<div class="main-sidebar" id="sidebar-scroll">

		<!-- Start::nav -->
		<nav class="main-menu-container nav nav-pills flex-column sub-open">
			<div class="slide-left" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
					height="24" viewBox="0 0 24 24">
					<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
				</svg></div>
			<ul class="main-menu">

				@php
					// Use the same role signal as layouts that set $saasIsSuper.
					$saasIsSuper = $saasIsSuper ?? (auth()->user()?->hasRole(\App\Support\Roles::SUPER_ADMIN) ?? false);
				@endphp

				@if(isApiClient() && !$saasIsSuper)
					{{-- API CLIENT MENU --}}
					<li class="slide__category"><span class="category-name">{{ __('API Gateway') }}</span></li>
					<li class="slide">
						<a href="{{ route('dashboard.api.overview') }}"
							class="side-menu__item {{ request()->routeIs('dashboard.api.overview') ? 'active' : '' }}">
							<i class="ri-pulse-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('Usage Overview') }}</span>
						</a>
					</li>
					<li class="slide">
						<a href="{{ route('dashboard.api.keys.index') }}"
							class="side-menu__item {{ request()->routeIs('dashboard.api.keys.*') ? 'active' : '' }}">
							<i class="ri-key-2-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('API Keys') }}</span>
						</a>
					</li>
					<li class="slide">
						<a href="{{ route('dashboard.api.wallet') }}"
							class="side-menu__item {{ request()->routeIs('dashboard.api.wallet') ? 'active' : '' }}">
							<i class="ri-wallet-3-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('Wallet') }}</span>
						</a>
					</li>
					<li class="slide">
						<a href="{{ route('dashboard.api.logs') }}"
							class="side-menu__item {{ request()->routeIs('dashboard.api.logs') ? 'active' : '' }}">
							<i class="ri-file-list-3-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('Logs') }}</span>
						</a>
					</li>
					<li class="slide">
						<a href="{{ route('dashboard.api.docs') }}"
							class="side-menu__item {{ request()->routeIs('dashboard.api.docs') ? 'active' : '' }}">
							<i class="ri-book-read-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('Documentation') }}</span>
						</a>
					</li>
					<li class="slide {{ isSuperAdmin() ? '' : 'border-b border-defaultborder/10 pb-2 mb-2' }}">
						<a href="{{ route('dashboard.api.settings') }}"
							class="side-menu__item {{ request()->routeIs('dashboard.api.settings') ? 'active' : '' }}">
							<i class="ri-settings-4-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('Settings') }}</span>
						</a>
					</li>
				@endif

				@if(!isApiClient() || $saasIsSuper)
					{{-- CRM MENU (Organization / Admin / Sales) --}}
					<li class="slide__category"><span class="category-name">{{ __('Main') }}</span></li>
					<li class="slide">
						<a href="{{ route('admin.dashboard') }}"
							class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
							<i class="ri-dashboard-line side-menu__icon"></i>
							<span class="side-menu__label">{{ __('Overview') }}</span>
						</a>
					</li>

					@if ($saasIsSuper)
						<li class="slide__category"><span class="category-name">{{ __('Administration') }}</span></li>
						<li class="slide">
							<a href="{{ route('admin.organizations.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
								<i class="ri-building-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Organizations') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.roles.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
								<i class="ri-shield-user-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Roles & Permissions') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.contacts.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
								<i class="ri-message-2-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Inquiries') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.subscriptions.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
								<i class="ri-repeat-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Subscriptions') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.revenue.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}">
								<i class="ri-line-chart-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Revenue Analytics') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.broadcast-usage.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.broadcast-usage.*') ? 'active' : '' }}">
								<i class="ri-megaphone-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Broadcast Usage') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.analytics.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
								<i class="ri-bar-chart-box-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Analytics') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.site-traffic.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.site-traffic.*') ? 'active' : '' }}">
								<i class="ri-global-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Website traffic') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.integrations.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.integrations.*') ? 'active' : '' }}">
								<i class="ri-plug-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Integrations') }}</span>
							</a>
						</li>
					@endif

					@if ($saasIsSuper)
						<li class="slide__category"><span class="category-name">{{ __('Marketing') }}</span></li>
						<li class="slide">
							<a href="{{ route('admin.marketing.leads.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.marketing.leads.*') ? 'active' : '' }}">
								<i class="ri-user-follow-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Capture Leads') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('admin.marketing.landing-pages.index') }}"
								class="side-menu__item {{ request()->routeIs('admin.marketing.landing-pages.*') ? 'active' : '' }}">
								<i class="ri-pages-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Landing Pages') }}</span>
							</a>
						</li>
					@endif

					@unless ($saasIsSuper)
						<li class="slide__category"><span class="category-name">{{ __('Sales CRM') }}</span></li>
						<li class="slide">
							<a href="{{ route('dashboard.leads.index') }}"
								class="side-menu__item {{ request()->routeIs('dashboard.leads.index') ? 'active' : '' }}">
								<i class="ri-team-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Leads List') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('dashboard.pipeline.index') }}"
								class="side-menu__item {{ request()->routeIs('dashboard.pipeline.*') ? 'active' : '' }}">
								<i class="ri-kanban-view side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Sales Pipeline') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('dashboard.followups.index') }}"
								class="side-menu__item {{ request()->routeIs('dashboard.followups.*') ? 'active' : '' }}">
								<i class="ri-calendar-check-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Follow-ups') }}</span>
							</a>
						</li>
						<li class="slide border-b border-defaultborder/10 pb-2 mb-2">
							<a href="{{ route('dashboard.broadcast.index') }}"
								class="side-menu__item {{ request()->routeIs('dashboard.broadcast.*') ? 'active' : '' }}">
								<i class="ri-whatsapp-line side-menu__icon text-[#25D366]"></i>
								<span class="side-menu__label">{{ __('Broadcast') }}</span>
							</a>
						</li>
						<li class="slide">
							<a href="{{ route('dashboard.reports.index') }}"
								class="side-menu__item {{ request()->routeIs('dashboard.reports.*') ? 'active' : '' }}">
								<i class="ri-bar-chart-2-line side-menu__icon"></i>
								<span class="side-menu__label">{{ __('Analytics') }}</span>
							</a>
						</li>
					@endunless
				@endif

				<!-- Start::slide__category -->
				<li class="slide__category"><span class="category-name">{{ __('Account') }}</span></li>
				<!-- End::slide__category -->

				<li class="slide border-b border-defaultborder/10 pb-2 mb-2">
					<a href="{{ route('admin.profile.edit') }}"
						class="side-menu__item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
						<i class="ri-user-settings-line side-menu__icon"></i>
						<span class="side-menu__label">{{ __('Settings') }}</span>
					</a>
				</li>

				<li class="slide">
					<a href="javascript:void(0);"
						onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
						class="side-menu__item">
						<i class="ri-logout-box-r-line side-menu__icon text-danger"></i>
						<span class="side-menu__label text-danger">{{ __('Logout') }}</span>
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</li>

			</ul>
			<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
					height="24" viewBox="0 0 24 24">
					<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
				</svg></div>
		</nav>
		<!-- End::nav -->

	</div>
	<!-- End::main-sidebar -->

</aside>