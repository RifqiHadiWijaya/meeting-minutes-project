import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


/**
 * app.js  –  Entry point utama
 *
 * Semua modul diinisialisasi di sini.
 * Vite akan compile file ini menjadi satu bundle yang di-cache browser.
 *
 * Urutan:
 *  1. Flash      → berlaku di semua halaman
 *  2. Animations → berlaku di semua halaman (entrance, ripple, dll)
 *  3. Pages      → masing-masing cek sendiri apakah elemennya ada di DOM
 */

import { initFlash }      from './modules/flash.js';
import { initAnimations } from './modules/animations.js';
import { initMeetings }   from './pages/meetings.js';
import { initUsers }      from './pages/users.js';
import { initShow }       from './pages/show.js';
import { initDashboard }  from './pages/dashboard.js';

document.addEventListener('DOMContentLoaded', () => {
    // ── Global ──
    initFlash();
    initAnimations();

    // ── Per-page (auto-skip jika elemen tidak ada) ──
    initMeetings();
    initUsers();
    initShow();
    initDashboard();
});