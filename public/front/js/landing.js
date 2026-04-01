/**
 * Landify theme – front page only.
 * Runs only when body has .landify-theme. Not loaded in backend (app.blade.php).
 */
(function () {
    'use strict';

    function init() {
        if (!document.body.classList.contains('landify-theme')) return;

        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                offset: 50,
                once: true,
                easing: 'ease-out-cubic'
            });
        }

        var nav = document.getElementById('lfNavbar');
        if (nav) {
            function onScroll() {
                if (window.scrollY > 20) nav.classList.add('scrolled');
                else nav.classList.remove('scrolled');
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
