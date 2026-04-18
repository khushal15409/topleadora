/**
 * Swap sidebar brand between full logo (expanded / labels visible) and favicon (icon rail).
 * Vyzor: #sidebar width ~5rem → favicon. Materio: #layout-menu narrow rail → favicon.
 */
const CRM_SIDEBAR_RAIL_MAX_PX = 104;

function crmVyzorSidebarEl() {
    return document.querySelector('#sidebar.app-sidebar');
}

function crmMaterioMenuEl() {
    return document.querySelector('#layout-menu.layout-menu');
}

function crmIsNarrowRail(el) {
    if (!el) {
        return false;
    }

    return el.getBoundingClientRect().width <= CRM_SIDEBAR_RAIL_MAX_PX;
}

function crmApplyVyzorBrand() {
    const header = document.querySelector('.main-sidebar-header a.header-logo');
    if (!header?.dataset.faviconUrl) {
        return;
    }

    const fav = header.dataset.faviconUrl;
    const rail = crmIsNarrowRail(crmVyzorSidebarEl());
    header.querySelectorAll('img.crm-sidebar-logo[data-brand-src]').forEach((img) => {
        const full = img.dataset.brandSrc;
        if (!full) {
            return;
        }
        img.src = rail ? fav : full;
        img.classList.toggle('crm-sidebar-favicon-mode', rail);
    });
}

function crmApplyMaterioBrand() {
    const img = document.querySelector('img.gcc-sidebar-brand-img[data-brand-src][data-favicon-src]');
    if (!img) {
        return;
    }

    const full = img.dataset.brandSrc;
    const fav = img.dataset.faviconSrc;
    if (!full || !fav) {
        return;
    }

    const rail = crmIsNarrowRail(crmMaterioMenuEl());
    img.src = rail ? fav : full;
    img.classList.toggle('gcc-sidebar-favicon-mode', rail);
}

function crmApplySidebarBrands() {
    crmApplyVyzorBrand();
    crmApplyMaterioBrand();
}

function crmBindSidebarBrandWatchers() {
    crmApplySidebarBrands();

    window.addEventListener('resize', crmApplySidebarBrands, { passive: true });

    const vz = crmVyzorSidebarEl();
    if (vz && typeof ResizeObserver !== 'undefined') {
        new ResizeObserver(crmApplySidebarBrands).observe(vz);
    }

    const menu = crmMaterioMenuEl();
    if (menu && typeof ResizeObserver !== 'undefined') {
        new ResizeObserver(crmApplySidebarBrands).observe(menu);
    }

    document.documentElement.addEventListener(
        'click',
        (e) => {
            if (e.target.closest('.sidemenu-toggle, .layout-menu-toggle, .menu-link.menu-toggle')) {
                window.setTimeout(crmApplySidebarBrands, 250);
            }
        },
        true
    );

    new MutationObserver(crmApplySidebarBrands).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class', 'data-toggled', 'data-vertical-style', 'data-nav-style', 'data-icon-text'],
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', crmBindSidebarBrandWatchers);
} else {
    crmBindSidebarBrandWatchers();
}
