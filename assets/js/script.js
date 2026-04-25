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

    // ── Custom Smooth Scroll for Anchor Links ──
    document.querySelectorAll('a[href^="#"], a[href*="#lokasi"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const url = new URL(this.href, window.location.origin);
            if (url.pathname === window.location.pathname) {
                const targetId = url.hash;
                if (!targetId) return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const startPosition = window.pageYOffset;
                    const distance = targetPosition - startPosition;
                    const duration = 1200; // Slower duration for a more luxurious feel
                    let start = null;
                    
                    // Easing function: easeInOutQuart for beautiful smooth transition
                    const easeInOutQuart = (t) => {
                        return t < 0.5 ? 8 * t * t * t * t : 1 - Math.pow(-2 * t + 2, 4) / 2;
                    };

                    const step = (timestamp) => {
                        if (!start) start = timestamp;
                        const progress = timestamp - start;
                        const percentage = Math.min(progress / duration, 1);
                        
                        window.scrollTo(0, startPosition + distance * easeInOutQuart(percentage));
                        
                        if (progress < duration) {
                            window.requestAnimationFrame(step);
                        } else {
                            history.pushState(null, null, targetId);
                        }
                    };
                    
                    window.requestAnimationFrame(step);
                }
            }
        });
    });
});