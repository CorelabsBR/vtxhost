(function () {
    'use strict';

    /* ── Mobile menu ─────────────────────────────────────── */
    const hamburger = document.getElementById('hamburgerBtn');
    const mobileNav = document.getElementById('mobileNav');
    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            const open = mobileNav.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', String(open));
            hamburger.classList.toggle('is-open', open);
        });
    }

    /* ── Billing cycle switcher ──────────────────────────── */
    const discounts = { monthly: 0, quarterly: 0.05, semiannual: 0.10, annual: 0.15 };
    const billingBtns = document.querySelectorAll('.billing-btn');

    function applyBilling(billing) {
        const discount = discounts[billing] || 0;
        document.querySelectorAll('.plan-card[data-base-price]').forEach(card => {
            const base = parseFloat(card.dataset.basePrice);
            if (isNaN(base)) return;
            const final = base * (1 - discount);
            const el = card.querySelector('.price-amount');
            if (el) el.textContent = 'R$ ' + final.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        });
    }

    billingBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            billingBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyBilling(btn.dataset.billing);
        });
    });

    /* ── Location switcher ───────────────────────────────── */
    const locationBtns = document.querySelectorAll('.location-btn');

    function applyLocation(loc) {
        document.querySelectorAll('.plan-card[data-location]').forEach(card => {
            const show = !loc || card.dataset.location === loc;
            card.style.display = show ? 'flex' : 'none';
        });
        // Fix popular badge: first visible card in middle gets it
        const visible = [...document.querySelectorAll('.plan-card[data-location]')]
            .filter(c => c.style.display !== 'none');
        visible.forEach((c, i) => {
            const existing = c.querySelector('.popular-label');
            if (existing) existing.remove();
            c.classList.remove('popular');
            if (visible.length >= 2 && i === Math.floor(visible.length / 2)) {
                c.classList.add('popular');
                const badge = document.createElement('div');
                badge.className = 'popular-label';
                badge.textContent = 'MAIS ESCOLHIDO';
                c.prepend(badge);
            }
        });
    }

    locationBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            locationBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyLocation(btn.dataset.location);
        });
    });

    // Activate first location on load
    if (locationBtns.length > 0) {
        locationBtns[0].dispatchEvent(new Event('click'));
    }

    /* ── FAQ smooth open ─────────────────────────────────── */
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('toggle', () => {
            if (item.open) {
                // Close siblings
                item.closest('.faq-list')?.querySelectorAll('.faq-item').forEach(sibling => {
                    if (sibling !== item && sibling.open) sibling.removeAttribute('open');
                });
            }
        });
    });

    /* ── Admin: inline edit toggle ───────────────────────── */
    document.querySelectorAll('.row-actions').forEach(det => {
        det.addEventListener('toggle', () => {
            if (det.open) {
                document.querySelectorAll('.row-actions').forEach(other => {
                    if (other !== det && other.open) other.removeAttribute('open');
                });
            }
        });
    });

})();
