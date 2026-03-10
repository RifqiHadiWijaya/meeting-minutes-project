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
 *  1. Flash   → berlaku di semua halaman
 *  2. Pages   → masing-masing cek sendiri apakah elemennya ada di DOM
 */

import { initFlash }    from './modules/flash.js';
import { initMeetings } from './pages/meetings.js';
import { initUsers }    from './pages/users.js';

document.addEventListener('DOMContentLoaded', () => {
    // ── Global ──
    initFlash();

    // ── Per-page (auto-skip jika elemen tidak ada) ──
    initMeetings();
    initUsers();
});