/**
 * pages/dashboard.js
 * Enhancement untuk halaman Dashboard.
 * Hanya berjalan jika .stat-grid ada di halaman.
 *
 * Fitur:
 *  1. Stat counter — angka di .stat-value naik perlahan saat halaman load
 *  2. Meeting item — lift + transition warna saat hover (tambahan dari CSS)
 *  3. Stat card    — shimmer pulse sesaat saat pertama load (attention effect)
 *  4. Calendar     — highlight "hari ini" lebih mencolok setelah FullCalendar render
 */

export function initDashboard() {
    if (!document.querySelector('.stat-grid')) return;

    initStatCounter();
    initStatCardEntrance();
    initMeetingItemHover();
    initCalendarEnhance();
}

// ─────────────────────────────────────────────────────────────
// 1. STAT COUNTER
// Angka di .stat-value naik dari 0 ke nilai aslinya.
// ─────────────────────────────────────────────────────────────
function initStatCounter() {
    const statValues = document.querySelectorAll('.stat-value');

    statValues.forEach(el => {
        const target = parseInt(el.textContent.trim(), 10);
        if (isNaN(target) || target === 0) return;

        el.textContent = '0';

        // Mulai setelah entrance animation selesai (delay 300ms)
        setTimeout(() => {
            countUp(el, target, 900);
        }, 350);
    });
}

function countUp(el, target, duration) {
    const steps    = Math.min(target, 50); // max 50 langkah
    const interval = duration / steps;
    let current    = 0;

    const timer = setInterval(() => {
        // Easing: melambat di akhir
        const progress = current / steps;
        const eased    = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        const displayed = Math.round(eased * target);

        el.textContent = displayed.toLocaleString('id-ID');
        current++;

        if (current > steps) {
            clearInterval(timer);
            el.textContent = target.toLocaleString('id-ID'); // pastikan nilai akhir exact
        }
    }, interval);
}

// ─────────────────────────────────────────────────────────────
// 2. STAT CARD ENTRANCE
// Stagger entrance + subtle pulse pada .stat-icon saat load.
// ─────────────────────────────────────────────────────────────
function initStatCardEntrance() {
    const cards = document.querySelectorAll('.stat-card');

    cards.forEach((card, i) => {
        const icon = card.querySelector('.stat-icon');
        if (!icon) return;

        // Pulse icon sekali saat masuk
        setTimeout(() => {
            icon.style.transition = 'transform .3s cubic-bezier(.22,.68,0,1.4)';
            icon.style.transform  = 'scale(1.15)';
            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 300);
        }, 400 + i * 100);
    });
}

// ─────────────────────────────────────────────────────────────
// 3. MEETING ITEM HOVER LIFT
// main.css: .meeting-item:hover { background: orange-50 } saja.
// Tambah lift & transition lebih smooth via JS.
// ─────────────────────────────────────────────────────────────
function initMeetingItemHover() {
    const items = document.querySelectorAll('.meeting-item');

    items.forEach(item => {
        // Extend transition (main.css hanya .12s background)
        item.style.transition = 'background .15s ease, transform .2s ease, box-shadow .2s ease';

        item.addEventListener('mouseenter', () => {
            item.style.transform  = 'translateX(3px)';
            item.style.boxShadow  = '2px 0 0 0 var(--orange-400) inset';
        });
        item.addEventListener('mouseleave', () => {
            item.style.transform  = '';
            item.style.boxShadow  = '';
        });
    });
}

// ─────────────────────────────────────────────────────────────
// 4. CALENDAR ENHANCE
// Tambah tooltip sederhana dan pastikan "today" highlight
// lebih menonjol setelah FullCalendar selesai render.
// ─────────────────────────────────────────────────────────────
function initCalendarEnhance() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // Poll sampai FullCalendar selesai render (max 3 detik)
    let attempts = 0;
    const poll = setInterval(() => {
        attempts++;
        const today = calendarEl.querySelector('.fc-day-today');
        if (today) {
            enhanceTodayCell(today);
            clearInterval(poll);
        }
        if (attempts > 30) clearInterval(poll);
    }, 100);
}

function enhanceTodayCell(cell) {
    // Tambah subtle glow di sekitar sel "hari ini"
    cell.style.transition = 'box-shadow .3s ease';
    cell.style.boxShadow  = 'inset 0 0 0 2px rgba(37,99,235,.35)';
    cell.style.borderRadius = '4px';
}