
        <!-- Back To Top -->
        <div class="scrollToTop">
            <span class="arrow"><i class="ri-arrow-up-s-fill  text-[1.25rem]"></i></span>
        </div>

        <div id="responsive-overlay"></div>
        
        <!-- popperjs -->
        <script src="{{asset('build/assets/libs/@popperjs/core/umd/popper.min.js')}}"></script>

        <!-- Color Picker JS -->
        <script src="{{asset('build/assets/libs/@simonwep/pickr/pickr.es5.min.js')}}"></script>

        <!-- Swiper JS -->
        <script src="{{asset('build/assets/libs/swiper/swiper-bundle.min.js')}}"></script>

        <!-- sidebar JS -->
        @vite('resources/assets/js/defaultmenu.js')

        <!-- Internal Landing JS -->
        @vite('resources/assets/js/landing.js')

        <!-- Switch JS -->
        <script src="{{asset('build/assets/switch.js')}}"></script>

        <!-- Preline JS -->
        <script src="{{asset('build/assets/libs/preline/preline.js')}}"></script>

        <!-- Simplebar JS -->
        <script src="{{asset('build/assets/libs/simplebar/simplebar.min.js')}}"></script>

        <script src="{{asset('build/assets/sticky.js')}}"></script>
