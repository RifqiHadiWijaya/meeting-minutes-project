/**
 * pages/meetings.js
 * Logika khusus halaman Meetings Index.
 * Hanya berjalan jika elemen #meetingTbody ada di halaman.
 *
 * Sebelumnya: initTableFilter (client-side, hanya filter baris di DOM)
 * Sekarang  : initServerSearch (server-side, search/filter/sort via URL params)
 */

import { initServerSearch } from '../modules/server-search.js';

export function initMeetings() {
    if (!document.getElementById('meetingTbody')) return;

    initServerSearch({
        searchId     : 'searchInput',
        searchClearId: 'searchClear',
        resultCountId: 'resultCount',
        resetAllId   : 'resetAll',
        sortBtnId    : 'sortDate',
        filterGroups : ['status', 'jenis'],
        entityLabel  : 'rapat',
        debounceMs   : 400,
        paramMap     : {
            search : 'search',
            sort   : 'sort',
            status : 'status',
            jenis  : 'jenis',
        },
    });
}