<header class="app-header">
	<nav class="main-header" aria-label="Global">
		<div class="main-header-container !px-[0.85rem]">

			<div class="header-content-left flex items-center">
				<!-- Sidebar Toggle -->
				<div class="header-element !items-center">
					<a aria-label="Hide Sidebar"
						class="sidemenu-toggle animated-arrow header-link hor-toggle horizontal-navtoggle inline-flex items-center"
						href="javascript:void(0);"><i class="header-icon fe fe-align-left text-xl"></i></a>
				</div>

				<!-- Global Search -->
				<div class="main-header-center hidden lg:block ms-4">
					<div class="relative">
						<input
							class="ti-form-input !rounded-full !ps-11 !pe-4 !py-2 !text-sm border-defaultborder/10 focus:border-primary/50 !bg-gray-100/50 dark:!bg-black/10 w-64"
							placeholder="{{ __('Search leads...') }}" type="search">
						<div
							class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-textmuted">
							<i class="ri-search-line"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="header-content-right flex items-center gap-1">
				<!-- Theme Mode Toggle -->
				<div class="header-element header-theme-mode hidden sm:block">
					<a aria-label="Toggle Theme"
						class="hs-dark-mode-active:hidden flex hs-dark-mode group items-center justify-center rounded-full p-2 hover:bg-gray-100 dark:hover:bg-black/20 text-textmuted transition-colors"
						href="javascript:void(0);" data-hs-theme-click-value="dark">
						<i class="ri-moon-line text-xl"></i>
					</a>
					<a aria-label="Toggle Theme"
						class="hs-dark-mode-active:flex hidden hs-dark-mode group items-center justify-center rounded-full p-2 hover:bg-gray-100 dark:hover:bg-black/20 text-textmuted transition-colors"
						href="javascript:void(0);" data-hs-theme-click-value="light">
						<i class="ri-sun-line text-xl"></i>
					</a>
				</div>

				<!-- Profile Dropdown -->
				<div class="header-element hs-dropdown profile-dropdown ti-dropdown [--placement:bottom-right]">
					<button id="dropdown-profile" type="button"
						class="hs-dropdown-toggle ti-dropdown-toggle !gap-2 !p-1 flex-shrink-0 !rounded-full !border-0 !bg-transparent !shadow-none">
						<div class="flex items-center gap-2">
							<div class="avatar avatar-sm bg-primary/10 text-primary rounded-full font-bold">
								{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
							</div>
							<div class="hidden md:block text-start">
								<p class="text-xs font-bold leading-none mb-0.5 text-defaulttextcolor">
									{{ auth()->user()->name ?? 'User' }}
								</p>
								<p class="text-[10px] leading-none text-textmuted">
									{{ auth()->user()->roles->first()->name ?? 'Member' }}
								</p>
							</div>
							<i class="ri-arrow-down-s-line text-textmuted text-sm hidden md:block"></i>
						</div>
					</button>
					<div class="hs-dropdown-menu ti-dropdown-menu hidden !p-0 !border-0 shadow-lg min-w-[12rem] bg-white dark:bg-gray-800"
						aria-labelledby="dropdown-profile">
						<div class="p-4 border-b dark:border-white/10">
							<div class="flex items-center gap-3">
								<div class="avatar avatar-md bg-primary text-white rounded-full">
									{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
								</div>
								<div>
									<h6 class="text-sm font-bold mb-0.5 dark:text-white">
										{{ auth()->user()->name ?? 'User' }}
									</h6>
									<p class="text-[11px] text-textmuted mb-0">{{ auth()->user()->email }}</p>
								</div>
							</div>
						</div>
						<ul class="py-2">
							<li>
								<a class="flex items-center gap-2 px-4 py-2 text-sm text-defaulttextcolor hover:bg-gray-100 dark:hover:bg-black/20 transition-colors"
									href="{{ route('admin.profile.edit') }}">
									<i class="ri-user-settings-line text-lg opacity-60"></i>
									{{ __('Account Settings') }}
								</a>
							</li>
							@if($saasIsSuper)
								<li>
									<a class="flex items-center gap-2 px-4 py-2 text-sm text-defaulttextcolor hover:bg-gray-100 dark:hover:bg-black/20 transition-colors"
										href="{{ route('admin.organizations.index') }}">
										<i class="ri-building-line text-lg opacity-60"></i>
										{{ __('Manage Organizations') }}
									</a>
								</li>
							@endif
							<li class="border-t mt-2 pt-2 dark:border-white/10">
								<a class="flex items-center gap-2 px-4 py-2 text-sm text-danger hover:bg-danger/5 transition-colors"
									href="javascript:void(0);"
									onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
									<i class="ri-logout-box-r-line text-lg"></i>
									{{ __('Sign Out') }}
								</a>
								<form id="logout-form-header" action="{{ route('logout') }}" method="POST"
									style="display: none;">
									@csrf
								</form>
							</li>
						</ul>
					</div>
				</div>

				<!-- Switcher Icon (Optional) -->
				<div class="header-element md:px-2">
					<button aria-label="Theme Settings" type="button"
						class="hs-dropdown-toggle inline-flex items-center justify-center p-2 rounded-full hover:bg-gray-100 dark:hover:bg-black/20 text-textmuted transition-colors"
						data-hs-overlay="#hs-overlay-switcher">
						<i class="ri-settings-3-line text-xl animate-spin-slow"></i>
					</button>
				</div>

				{{-- Dummy elements to prevent SimpleBar TypeError in GCC build JS --}}
				<div id="header-cart-items-scroll" class="hidden"></div>
				<div id="header-notification-scroll" class="hidden"></div>

			</div>
		</div>
	</nav>
</header>