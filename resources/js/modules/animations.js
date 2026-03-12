/**
 * modules/animations.js
 * Animasi & feedback visual global untuk semua halaman.
 *
 * Fitur:
 *  1. Page entrance  — card/section fade+slide saat halaman load
 *  2. Button ripple  — efek ripple saat klik .btn-primary, .btn-secondary, .btn-action
 *  3. Row hover lift — efek lift di baris tabel
 *  4. Form field     — animated focus ring + label float
 *  5. Q&A entrance   — animasi saat .question-card muncul (show.blade)
 *  6. Stat counter   — angka naik perlahan di elemen [data-count]
 */

export function initAnimations() {
    initPageEntrance();
    initButtonRipple();
    initFormFieldAnimations();
    initQAEntrance();
    initStatCounter();
    initTableRowHover();
    initCardHover();
}

// ─────────────────────────────────────────────
// 1. PAGE ENTRANCE
// Semua .card, .page-header, .page-topbar, .toolbar, .result-info
// masuk dengan fade + slide dari bawah, staggered.
// ─────────────────────────────────────────────
function initPageEntrance() {
    const targets = document.querySelectorAll(
        '.card, .page-header, .page-topbar, .toolbar, .result-info, .alert-success, .alert-error'
    );

    targets.forEach((el, i) => {
        // Skip jika sudah punya animasi sendiri (misal row-visible)
        if (el.dataset.entranceDone) return;

        el.style.opacity    = '0';
        el.style.transform  = 'translateY(18px)';
        el.style.transition = `opacity 0.45s cubic-bezier(.22,.68,0,1.2) ${i * 0.07}s,
                               transform 0.45s cubic-bezier(.22,.68,0,1.2) ${i * 0.07}s`;

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                el.style.opacity   = '1';
                el.style.transform = 'translateY(0)';
                el.dataset.entranceDone = '1';
            });
        });
    });
}

// ─────────────────────────────────────────────
// 2. BUTTON RIPPLE
// ─────────────────────────────────────────────
function initButtonRipple() {
    const rippleSel = '.btn-primary, .btn-secondary, .btn-action, .btn-pdf, .btn-login, .chip, .sort-btn';

    document.addEventListener('click', function (e) {
        const btn = e.target.closest(rippleSel);
        if (!btn) return;

        // Buat ripple element
        const ripple   = document.createElement('span');
        const rect     = btn.getBoundingClientRect();
        const size     = Math.max(rect.width, rect.height) * 1.8;
        const x        = e.clientX - rect.left - size / 2;
        const y        = e.clientY - rect.top  - size / 2;

        ripple.style.cssText = `
            position:absolute;
            width:${size}px; height:${size}px;
            left:${x}px; top:${y}px;
            border-radius:50%;
            background:rgba(255,255,255,0.28);
            pointer-events:none;
            transform:scale(0);
            animation:rippleAnim 0.55s cubic-bezier(.22,.68,0,1) forwards;
        `;

        // Pastikan btn punya overflow hidden & position relative
        const pos = getComputedStyle(btn).position;
        if (pos === 'static') btn.style.position = 'relative';
        btn.style.overflow = 'hidden';

        btn.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
    });
}

// ─────────────────────────────────────────────
// 3. TABLE ROW HOVER LIFT
// ─────────────────────────────────────────────
function initTableRowHover() {
    // CSS handles hover via main.css additions, tapi kita tambah JS untuk
    // efek "active row" highlight saat navigasi keyboard
    const tbody = document.querySelector('tbody');
    if (!tbody) return;

    tbody.addEventListener('keydown', e => {
        const row = e.target.closest('tr');
        if (!row) return;
        row.classList.add('row-focus');
        row.addEventListener('blur', () => row.classList.remove('row-focus'), { once: true });
    });
}

// ─────────────────────────────────────────────
// 4. FORM FIELD ANIMATIONS
// Tambah kelas 'field-filled' saat ada value,
// dan 'field-focused' saat fokus — untuk CSS styling lanjutan.
// ─────────────────────────────────────────────
function initFormFieldAnimations() {
    const fields = document.querySelectorAll('.form-input, .form-select, .form-textarea');
    if (!fields.length) return;

    fields.forEach(field => {
        // Cek initial state
        checkFilled(field);

        field.addEventListener('focus', () => {
            field.closest('.form-group')?.classList.add('field-focused');
            // Animasikan label naik
            const label = field.closest('.form-group')?.querySelector('.form-label');
            if (label) label.classList.add('label-active');
        });

        field.addEventListener('blur', () => {
            field.closest('.form-group')?.classList.remove('field-focused');
            checkFilled(field);
        });

        field.addEventListener('input', () => checkFilled(field));
        field.addEventListener('change', () => checkFilled(field));
    });

    function checkFilled(field) {
        const group = field.closest('.form-group');
        if (!group) return;
        const filled = field.value && field.value.trim() !== '';
        group.classList.toggle('field-filled', !!filled);
        // Animasikan label
        const label = group.querySelector('.form-label');
        if (label) label.classList.toggle('label-active', !!filled);
    }

    // Shake animation untuk field yang invalid saat submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', () => {
            fields.forEach(field => {
                if (!field.validity.valid) {
                    field.classList.add('field-shake');
                    field.addEventListener('animationend', () => {
                        field.classList.remove('field-shake');
                    }, { once: true });
                }
            });
        });
    });
}

// ─────────────────────────────────────────────
// 5. Q&A CARD ENTRANCE (show.blade)
// Observer: animasikan .question-card saat masuk viewport
// ─────────────────────────────────────────────
function initQAEntrance() {
    const cards = document.querySelectorAll('.question-card');
    if (!cards.length) return;

    // Jika IntersectionObserver tersedia
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('qa-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

        cards.forEach((card, i) => {
            card.style.animationDelay = `${i * 0.08}s`;
            observer.observe(card);
        });
    } else {
        // Fallback: langsung visible
        cards.forEach(card => card.classList.add('qa-visible'));
    }
}

// ─────────────────────────────────────────────
// 6. STAT COUNTER
// Elemen dengan [data-count="angka"] akan menghitung naik.
// Contoh di dashboard: <span data-count="42">0</span>
// ─────────────────────────────────────────────
function initStatCounter() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el     = entry.target;
            const target = parseInt(el.dataset.count, 10);
            const dur    = 900; // ms
            const steps  = 40;
            const step   = Math.ceil(target / steps);
            let current  = 0;

            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = current.toLocaleString('id-ID');
                if (current >= target) clearInterval(timer);
            }, dur / steps);

            observer.unobserve(el);
        });
    }, { threshold: 0.5 });

    counters.forEach(el => observer.observe(el));
}

// ─────────────────────────────────────────────
// 7. CARD HOVER — subtle depth effect
// ─────────────────────────────────────────────
function initCardHover() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transition = 'box-shadow 0.25s ease, transform 0.25s ease';
            card.style.boxShadow  = '0 8px 32px rgba(0,0,0,0.09)';
            card.style.transform  = 'translateY(-1px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.boxShadow = '';
            card.style.transform = '';
        });
    });
}