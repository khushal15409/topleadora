		<header class="app-header">

			<!-- Start::main-header-container -->
			<div class="main-header-container container-fluid">

				<!-- Start::header-content-left -->
				<div class="header-content-left">

					<!-- Start::header-element -->
					<div class="header-element">
						<div class="horizontal-logo">
							<a href="{{url('index')}}" class="header-logo">
								<img src="{{asset('build/assets/images/brand-logos/toggle-logo.png')}}" alt="logo" class="toggle-logo">
								<img src="{{asset('build/assets/images/brand-logos/toggle-dark.png')}}" alt="logo" class="toggle-dark">
							</a>
						</div>
					</div>
					<!-- End::header-element -->

					<!-- Start::header-element -->
					<div class="header-element">
						<!-- Start::header-link -->
						<a aria-label="anchor" href="javascript:void(0);" class="sidemenu-toggle header-link">
							<span class="open-toggle">
								<i class="ri-menu-3-line text-xl"></i>
							</span>
						</a>
						<!-- End::header-link -->
					</div>
					<!-- End::header-element -->

				</div>
				<!-- End::header-content-left -->

				<!-- Start::header-content-right -->
				<div class="header-content-right">

					<!-- Start::header-element -->
					<div class="header-element !items-center">
						<!-- Start::header-link|switcher-icon -->
						<div class="lg:hidden block">
							<a href="{{url('sign-up-basic')}}" class="ti-btn ti-btn-primary !m-0">
								Sign Up
							</a>
							<a aria-label="anchor" href="javascript:void(0);" class="ti-btn m-0 p-2 px-3 ti-btn-success"
							data-hs-overlay="#hs-overlay-switcher"><i class="ri-settings-3-line animate-spin-slow"></i></a>
						</div>
						<!-- End::header-link|switcher-icon -->
					</div>
					<!-- End::header-element -->

				</div>
				<!-- End::header-content-right -->

			</div>
			<!-- End::main-header-container -->

		</header>