/**
 * flash.js
 * Auto-dismiss flash message alert.
 * Cari elemen dengan id="flashSuccess" atau id="flashError",
 * lalu hilangkan otomatis setelah 4 detik.
 */

export function initFlash() {
    const els = document.querySelectorAll('#flashSuccess, #flashError, .alert-success, .alert-error[id]');
    els.forEach(el => {
        el.style.transition = 'opacity .5s ease, transform .5s ease';
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
}