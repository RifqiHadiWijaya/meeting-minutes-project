/**
 * table-filter.js
 * Reusable modul untuk Search, Filter Chip, dan Sort pada tabel.
 *
 * Cara pakai:
 *   import { initTableFilter } from '../modules/table-filter.js';
 *
 *   initTableFilter({
 *     tbodyId      : 'meetingTbody',   // id <tbody>
 *     searchId     : 'searchInput',    // id <input> search
 *     searchClearId: 'searchClear',    // id tombol clear search
 *     resultCountId: 'resultCount',    // id span jumlah hasil
 *     noResultId   : 'noResult',       // id blok "tidak ditemukan"
 *     noResultSubId: 'noResultSub',    // id subtitle no-result
 *     resetAllId   : 'resetAll',       // id tombol reset semua filter
 *     sortBtnId    : 'sortDate',       // id tombol sort (opsional)
 *     sortDataKey  : 'tanggal',        // dataset key untuk sort (opsional)
 *     searchDataKey: 'judul',          // dataset key untuk search
 *     highlightSel : '.judul-searchable', // selector elemen yang di-highlight
 *     filterGroups : ['status', 'jenis'], // daftar data-filter group
 *     entityLabel  : 'rapat',          // label untuk result count ("3 rapat")
 *   });
 */

export function initTableFilter(opts) {
    const {
        tbodyId,
        searchId,
        searchClearId,
        resultCountId,
        noResultId,
        noResultSubId,
        resetAllId,
        sortBtnId     = null,
        sortDataKey   = 'created',
        searchDataKey = 'search',
        highlightSel  = null,
        filterGroups  = [],
        entityLabel   = 'data',
    } = opts;

    // ── DOM refs ──
    const tbody       = document.getElementById(tbodyId);
    const searchInput = document.getElementById(searchId);
    const searchClear = document.getElementById(searchClearId);
    const resultCount = document.getElementById(resultCountId);
    const noResult    = document.getElementById(noResultId);
    const noResultSub = document.getElementById(noResultSubId);
    const resetAllBtn = document.getElementById(resetAllId);
    const sortBtn     = sortBtnId ? document.getElementById(sortBtnId) : null;

    if (!tbody || !searchInput) return;

    // ── State ──
    const state = { search: '', sort: 'desc' };
    filterGroups.forEach(g => state[g] = '');

    // ── Search ──
    searchInput.addEventListener('input', function () {
        state.search = this.value.trim().toLowerCase();
        if (searchClear) searchClear.classList.toggle('visible', state.search.length > 0);
        applyAll();
    });

    if (searchClear) {
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            state.search = '';
            searchClear.classList.remove('visible');
            applyAll();
            searchInput.focus();
        });
    }

    // ── Chip filters ──
    filterGroups.forEach(group => {
        document.querySelectorAll(`.chip[data-filter="${group}"]`).forEach(chip => {
            chip.addEventListener('click', function () {
                document.querySelectorAll(`.chip[data-filter="${group}"]`).forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                state[group] = this.dataset.value;
                applyAll();
            });
        });
    });

    // ── Sort ──
    if (sortBtn) {
        sortBtn.addEventListener('click', function () {
            state.sort = state.sort === 'desc' ? 'asc' : 'desc';
            this.classList.toggle('asc', state.sort === 'asc');
            const label = this.querySelector('span');
            if (label) label.textContent = state.sort === 'asc' ? 'Terlama' : 'Terbaru';
            applyAll();
        });
    }

    // ── Reset all ──
    if (resetAllBtn) {
        resetAllBtn.addEventListener('click', () => {
            state.search = '';
            state.sort   = 'desc';
            filterGroups.forEach(g => state[g] = '');
            searchInput.value = '';
            if (searchClear) searchClear.classList.remove('visible');
            if (sortBtn) {
                sortBtn.classList.remove('asc');
                const label = sortBtn.querySelector('span');
                if (label) label.textContent = 'Terbaru';
            }
            document.querySelectorAll('.chip[data-filter]').forEach(c => {
                c.classList.toggle('active', c.dataset.value === '');
            });
            applyAll();
        });
    }

    // ── Core: apply filter + sort + highlight ──
    function applyAll() {
        const rows = Array.from(tbody.querySelectorAll('tr[data-' + searchDataKey + '], tr[data-search]'));
        let visible = 0;

        // Sort
        if (sortBtn) {
            rows.sort((a, b) => {
                const va = a.dataset[sortDataKey] || '';
                const vb = b.dataset[sortDataKey] || '';
                // Coba numeric dulu, fallback ke string compare
                const na = Number(va), nb = Number(vb);
                if (!isNaN(na) && !isNaN(nb)) {
                    return state.sort === 'asc' ? na - nb : nb - na;
                }
                return state.sort === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
            });
            rows.forEach(r => tbody.appendChild(r));
        }

        rows.forEach((row, idx) => {
            // Search: cek dataset[searchDataKey] atau dataset.search
            const haystack = (row.dataset[searchDataKey] || row.dataset.search || '').toLowerCase();
            const matchSearch = !state.search || haystack.includes(state.search);

            // Filter chips
            const matchFilters = filterGroups.every(g => {
                return !state[g] || row.dataset[g] === state[g];
            });

            const show = matchSearch && matchFilters;
            row.style.display = show ? '' : 'none';

            if (!show) return;
            visible++;

            // Re-animate
            row.style.animationDelay = (idx * 0.025) + 's';
            row.classList.remove('row-visible');
            void row.offsetWidth;
            row.classList.add('row-visible');

            // Highlight
            if (highlightSel) {
                const el = row.querySelector(highlightSel);
                if (el) {
                    const plain = el.textContent;
                    el.innerHTML = state.search
                        ? plain.replace(new RegExp(`(${escRe(state.search)})`, 'gi'), '<mark class="hl">$1</mark>')
                        : plain;
                }
            }
        });

        // Renumber
        let n = 1;
        rows.forEach(r => {
            if (r.style.display !== 'none') {
                const td = r.querySelector('.td-number');
                if (td) td.textContent = n++;
            }
        });

        // No result state
        if (noResult) noResult.style.display = (visible === 0 && rows.length > 0) ? 'block' : 'none';

        // Result count
        const isFiltered = state.search || filterGroups.some(g => state[g]);
        if (resultCount) {
            resultCount.innerHTML = isFiltered
                ? `Menampilkan <strong>${visible}</strong> dari <strong>${rows.length}</strong> ${entityLabel}`
                : `Total <strong>${rows.length}</strong> ${entityLabel}`;
        }

        // No result subtitle
        if (noResultSub) {
            noResultSub.textContent = state.search
                ? `Tidak ada ${entityLabel} dengan kata kunci "${searchInput.value}"`
                : `Coba ubah filter yang digunakan`;
        }

        // Reset btn visibility
        if (resetAllBtn) {
            resetAllBtn.classList.toggle('visible', !!isFiltered);
        }
    }

    // Init
    applyAll();
}

// ── Helper ──
function escRe(s) {
    return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}