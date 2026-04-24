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

    // ── Custom Modern Select ──
    document.querySelectorAll('select.modern-select').forEach(select => {
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.style.display = 'none';

        const customSelect = document.createElement('div');
        customSelect.className = 'custom-select';
        
        const selectedOption = select.options[select.selectedIndex];
        customSelect.innerHTML = `<span>${selectedOption.text}</span> <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>`;
        
        const optionsList = document.createElement('div');
        optionsList.className = 'custom-options';

        Array.from(select.options).forEach(option => {
            const customOption = document.createElement('div');
            customOption.className = `custom-option ${option.selected ? 'selected' : ''}`;
            customOption.textContent = option.text;
            customOption.dataset.value = option.value;
            
            customOption.addEventListener('click', () => {
                select.value = option.value;
                customSelect.querySelector('span').textContent = option.text;
                optionsList.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                customOption.classList.add('selected');
                customSelect.classList.remove('open');
                
                // Trigger change event
                select.dispatchEvent(new Event('change'));
            });
            optionsList.appendChild(customOption);
        });

        wrapper.appendChild(customSelect);
        wrapper.appendChild(optionsList);

        customSelect.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = customSelect.classList.contains('open');
            document.querySelectorAll('.custom-select').forEach(s => s.classList.remove('open'));
            if (!isOpen) customSelect.classList.add('open');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select').forEach(s => s.classList.remove('open'));
    });
});