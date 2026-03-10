/**
 * pages/users.js
 * Logika khusus halaman Users Index.
 * Hanya berjalan jika elemen #userTbody ada di halaman.
 */

import { initTableFilter } from '../modules/table-filter.js';
import { initDeleteModal }  from '../modules/modal.js';

export function initUsers() {
    if (!document.getElementById('userTbody')) return;

    initTableFilter({
        tbodyId      : 'userTbody',
        searchId     : 'searchInput',
        searchClearId: 'searchClear',
        resultCountId: 'resultCount',
        noResultId   : 'noResult',
        noResultSubId: 'noResultSub',
        resetAllId   : 'resetAll',
        sortBtnId    : null,           // halaman user tidak perlu sort
        searchDataKey: 'search',       // search berdasarkan data-search (nama+username)
        highlightSel : '.user-searchable',
        filterGroups : ['role'],
        entityLabel  : 'pengguna',
    });

    initDeleteModal();
}