/**
 * pages/show.js
 * Logika khusus halaman Meetings Show.
 * Hanya berjalan jika .qa-section ada di halaman.
 */

import { initQA } from '../modules/qa.js';

export function initShow() {
    if (!document.querySelector('.qa-section')) return;
    initQA();
}