/**
 * modules/server-search.js
 * Search, filter chip, dan sort yang bekerja server-side.
 *
 * Cara kerja:
 *  - Semua state (search, filter, sort) disimpan di URL sebagai query params
 *  - Setiap perubahan → update URL → reload halaman
 *  - Search pakai debounce 400ms agar tidak reload tiap ketikan
 *  - Pagination Laravel tetap bekerja karena withQueryString() sudah ada
 *
 * Cara pakai:
 *   import { initServerSearch } from '../modules/server-search.js';
 *   initServerSearch({
 *     searchId     : 'searchInput',
 *     searchClearId: 'searchClear',
 *     resultCountId: 'resultCount',
 *     resetAllId   : 'resetAll',
 *     sortBtnId    : 'sortDate',
 *     filterGroups : ['status', 'jenis'],
 *     entityLabel  : 'rapat',
 *     paramMap     : {
 *         search : 'search',   // nama query param di URL
 *         sort   : 'sort',
 *         status : 'status',
 *         jenis  : 'jenis',
 *     },
 *   });
 */

export function initServerSearch(opts) {
    const {
        searchId,
        searchClearId,
        resultCountId,
        resetAllId,
        sortBtnId    = null,
        filterGroups = [],
        entityLabel  = 'data',
        paramMap     = {},
        debounceMs   = 400,
    } = opts;

    const searchInput = document.getElementById(searchId);
    const searchClear = document.getElementById(searchClearId);
    const resultCount = document.getElementById(resultCountId);
    const resetAllBtn = document.getElementById(resetAllId);
    const sortBtn     = sortBtnId ? document.getElementById(sortBtnId) : null;

    if (!searchInput) return;

    // ── Baca state awal dari URL ──────────────────────────────
    const params = new URLSearchParams(window.location.search);

    const getParam  = key => params.get(paramMap[key] || key) || '';
    const sortInit  = getParam('sort') || 'desc';

    // Restore UI dari URL params saat halaman load
    restoreUI();

    // ── Search ───────────────────────────────────────────────
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        const val = this.value.trim();
        if (searchClear) searchClear.classList.toggle('visible', val.length > 0);

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            setParam(paramMap.search || 'search', val);
            setParam('page', ''); // reset ke halaman 1
            navigate();
        }, debounceMs);
    });

    if (searchClear) {
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            searchClear.classList.remove('visible');
            setParam(paramMap.search || 'search', '');
            setParam('page', '');
            navigate();
        });
    }

    // ── Chip filters ─────────────────────────────────────────
    filterGroups.forEach(group => {
        document.querySelectorAll(`.chip[data-filter="${group}"]`).forEach(chip => {
            chip.addEventListener('click', function () {
                document.querySelectorAll(`.chip[data-filter="${group}"]`)
                    .forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                setParam(paramMap[group] || group, this.dataset.value);
                setParam('page', '');
                navigate();
            });
        });
    });

    // ── Sort ─────────────────────────────────────────────────
    if (sortBtn) {
        sortBtn.addEventListener('click', function () {
            const current = getURLParam(paramMap.sort || 'sort') || 'desc';
            const next    = current === 'desc' ? 'asc' : 'desc';
            setParam(paramMap.sort || 'sort', next);
            setParam('page', '');
            navigate();
        });
    }

    // ── Reset all ────────────────────────────────────────────
    if (resetAllBtn) {
        resetAllBtn.addEventListener('click', () => {
            const freshParams = new URLSearchParams();
            window.location.href = window.location.pathname + (freshParams.toString() ? '?' + freshParams : '');
        });
    }

    // ── Restore UI state dari URL params ─────────────────────
    function restoreUI() {
        // Search input
        const searchVal = getParam('search');
        if (searchVal) {
            searchInput.value = searchVal;
            if (searchClear) searchClear.classList.add('visible');
        }

        // Chips
        filterGroups.forEach(group => {
            const val = getParam(group);
            document.querySelectorAll(`.chip[data-filter="${group}"]`).forEach(chip => {
                chip.classList.toggle('active', chip.dataset.value === val);
            });
        });

        // Sort button label & state
        if (sortBtn) {
            const sortVal = getParam('sort') || 'desc';
            sortBtn.classList.toggle('asc', sortVal === 'asc');
            const label = sortBtn.querySelector('span');
            if (label) label.textContent = sortVal === 'asc' ? 'Terlama' : 'Terbaru';
        }

        // Reset btn visibility
        const isFiltered = getParam('search') || filterGroups.some(g => getParam(g));
        if (resetAllBtn) resetAllBtn.classList.toggle('visible', !!isFiltered);

        // Result count (data dari server, update label saja)
        updateResultCount();
    }

    // ── Result count label ────────────────────────────────────
    function updateResultCount() {
        if (!resultCount) return;
        const isFiltered = getParam('search') || filterGroups.some(g => getParam(g));

        // Baca total & showing dari data attribute di elemen resultCount
        const total   = resultCount.dataset.total   || '';
        const showing = resultCount.dataset.showing || '';

        if (total && showing) {
            resultCount.innerHTML = isFiltered
                ? `Menampilkan <strong>${showing}</strong> dari <strong>${total}</strong> ${entityLabel}`
                : `Total <strong>${total}</strong> ${entityLabel}`;
        }
    }

    // ── Helpers ───────────────────────────────────────────────
    function setParam(key, value) {
        if (value) {
            params.set(key, value);
        } else {
            params.delete(key);
        }
    }

    function getURLParam(key) {
        return params.get(key) || '';
    }

    function navigate() {
        const qs = params.toString();
        window.location.href = window.location.pathname + (qs ? '?' + qs : '');
    }
}