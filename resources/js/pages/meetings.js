/**
 * pages/meetings.js
 * Logika khusus halaman Meetings Index.
 * Hanya berjalan jika elemen #meetingTbody ada di halaman.
 */

import { initTableFilter } from '../modules/table-filter.js';

export function initMeetings() {
    if (!document.getElementById('meetingTbody')) return;

    initTableFilter({
        tbodyId      : 'meetingTbody',
        searchId     : 'searchInput',
        searchClearId: 'searchClear',
        resultCountId: 'resultCount',
        noResultId   : 'noResult',
        noResultSubId: 'noResultSub',
        resetAllId   : 'resetAll',
        sortBtnId    : 'sortDate',
        sortDataKey  : 'tanggal',      // sort berdasarkan tanggal rapat
        searchDataKey: 'judul',        // search berdasarkan judul
        highlightSel : '.judul-searchable',
        filterGroups : ['status', 'jenis'],
        entityLabel  : 'rapat',
    });
}