<x-app-layout>

{{-- ══ PAGE HEADER ══ --}}
<div class="page-header">
    <div>
        <div class="page-title">Riwayat Rapat</div>
        <div class="page-subtitle">Daftar seluruh rapat yang telah dijadwalkan</div>
    </div>

    @if(session('success'))
        <div id="flashSuccess" class="alert-success" style="margin-bottom:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(auth()->user()->role === 'notulis')
        <a href="{{ route('meetings.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Buat Rapat
        </a>
    @endif
</div>

{{-- ══ TOOLBAR ══ --}}
<div class="toolbar">
    <div class="search-wrap">
        <input type="text" id="searchInput" class="search-input" placeholder="Cari judul rapat..." autocomplete="off">
        <svg class="search-icon" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <button class="search-clear" id="searchClear" type="button">✕</button>
    </div>

    <div class="toolbar-divider"></div>

    <div class="filter-group">
        <span class="filter-group-label">Status</span>
        <button type="button" class="chip active"         data-filter="status" data-value="">Semua</button>
        <button type="button" class="chip chip-scheduled" data-filter="status" data-value="scheduled"><span class="chip-dot"></span>Terjadwal</button>
        <button type="button" class="chip chip-completed" data-filter="status" data-value="completed"><span class="chip-dot"></span>Selesai</button>
    </div>

    <div class="toolbar-divider"></div>

    <div class="filter-group">
        <span class="filter-group-label">Jenis</span>
        <button type="button" class="chip active"          data-filter="jenis" data-value="">Semua</button>
        <button type="button" class="chip chip-internal"   data-filter="jenis" data-value="Internal DISKOMINFO"><span class="chip-dot"></span>Internal</button>
        <button type="button" class="chip chip-eksternal"  data-filter="jenis" data-value="Eksternal DISKOMINFO"><span class="chip-dot"></span>Eksternal</button>
    </div>

    <div class="toolbar-divider"></div>

    <button type="button" class="sort-btn active" id="sortDate">
        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>
        </svg>
        <span>Terbaru</span>
    </button>
</div>

{{-- ══ RESULT INFO ══ --}}
<div class="result-info">
    <span class="result-count" id="resultCount"></span>
    <button type="button" class="reset-all" id="resetAll">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Reset filter
    </button>
</div>

{{-- ══ TABLE ══ --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul Rapat</th>
                    <th>Tanggal & Waktu</th>
                    <th>Dibuat Oleh</th>
                    <th>Pertanyaan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="meetingTbody">
                @forelse($meetings as $i => $meeting)
                    <tr
                        data-status="{{ $meeting->status }}"
                        data-jenis="{{ $meeting->jenis }}"
                        data-judul="{{ strtolower($meeting->judul) }}"
                        data-tanggal="{{ $meeting->tanggal }}"
                        class="row-visible"
                        style="animation-delay: {{ $i * 0.035 }}s"
                    >
                        <td class="td-number">{{ $meetings->firstItem() + $i }}</td>

                        <td class="td-judul">
                            <div class="judul-text judul-searchable">{{ $meeting->judul }}</div>
                            @if($meeting->lokasi)
                                <div class="judul-sub">
                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $meeting->lokasi }}
                                </div>
                            @endif
                            @if($meeting->jenis)
                                <span class="badge-jenis {{ $meeting->jenis === 'Internal DISKOMINFO' ? 'badge-jenis-internal' : 'badge-jenis-eksternal' }}">
                                    {{ $meeting->jenis === 'Internal DISKOMINFO' ? 'Internal' : 'Eksternal' }}
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="date-box">
                                <div class="date-main">{{ \Carbon\Carbon::parse($meeting->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="date-time">{{ \Carbon\Carbon::parse($meeting->waktu)->format('H:i') }} WIB</div>
                            </div>
                        </td>

                        <td>
                            <div class="creator-cell">
                                <div class="avatar">{{ $meeting->creator_initials }}</div>
                                <span class="creator-name">{{ $meeting->display_creator_name }}</span>
                            </div>
                        </td>

                        <td><span class="q-count">{{ $meeting->questions_count }}</span></td>

                        <td>
                            @if($meeting->status === 'completed')
                                <span class="badge badge-completed"><span class="badge-dot"></span>Selesai</span>
                            @else
                                <span class="badge badge-scheduled"><span class="badge-dot"></span>Terjadwal</span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">
                                <a href="{{ route('meetings.show', $meeting->id) }}" class="btn-action btn-detail">
                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                                @can('update', $meeting)
                                    <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn-action btn-edit">
                                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div class="empty-title">Tidak ada data rapat</div>
                                <div class="empty-sub">Belum ada rapat yang dibuat</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="no-result-block" id="noResult">
        <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <div class="empty-title">Tidak ditemukan</div>
        <div class="empty-sub" id="noResultSub">Coba ubah kata kunci atau filter</div>
    </div>

    @if($meetings->hasPages())
        <div class="pagination-wrap">{{ $meetings->links() }}</div>
    @endif
</div>

</x-app-layout>