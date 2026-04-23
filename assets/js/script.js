// assets/js/script.js

document.addEventListener('DOMContentLoaded', () => {

    // ── Page Loader ──
    const loader = document.getElementById('pageLoader');
    if (loader) {
        window.addEventListener('load', () => {
            setTimeout(() => loader.classList.add('hidden'), 300);
        });
        // Fallback
        setTimeout(() => loader.classList.add('hidden'), 1800);
    }

    // ── Mobile Sidebar Toggle ──
    const sidebar   = document.querySelector('.sidebar');
    const backdrop  = document.querySelector('.sidebar-backdrop');
    const toggleBtn = document.querySelector('[data-menu-toggle]');
    const closeBtn  = document.querySelector('[data-menu-close]');

    function openMenu()  { sidebar?.classList.add('open'); backdrop?.classList.add('open'); }
    function closeMenu() { sidebar?.classList.remove('open'); backdrop?.classList.remove('open'); }

    toggleBtn?.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click',  closeMenu);

    // Close on nav link click (mobile)
    sidebar?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

    // ── Page-link fade (optional smooth nav) ──
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript')) return;
            e.preventDefault();
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity .2s ease';
            setTimeout(() => { window.location.href = href; }, 200);
        });
    });
});