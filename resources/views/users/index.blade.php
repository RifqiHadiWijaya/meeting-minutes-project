<x-app-layout>

{{-- ══ PAGE HEADER ══ --}}
<div class="page-header">
    <div>
        <div class="page-title">Kelola User</div>
        <div class="page-subtitle">Daftar seluruh pengguna yang terdaftar</div>
    </div>
    <a href="{{ route('users.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Tambah User
    </a>
</div>

@if(session('success'))
    <div id="flashSuccess" class="alert-success">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

{{-- ══ TOOLBAR ══ --}}
<div class="toolbar">
    <div class="search-wrap">
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama atau username..." autocomplete="off">
        <svg class="search-icon" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <button class="search-clear" id="searchClear" type="button">✕</button>
    </div>

    <div class="toolbar-divider"></div>

    <div class="filter-group">
        <span class="filter-group-label">Role</span>
        <button type="button" class="chip active"       data-filter="role" data-value="">Semua</button>
        <button type="button" class="chip chip-admin"   data-filter="role" data-value="admin"><span class="chip-dot"></span>Admin</button>
        <button type="button" class="chip chip-notulis" data-filter="role" data-value="notulis"><span class="chip-dot"></span>Notulis</button>
        <button type="button" class="chip chip-viewer"  data-filter="role" data-value="viewer"><span class="chip-dot"></span>Viewer</button>
    </div>
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
                    <th>Pengguna</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="userTbody">
                @forelse($users as $i => $user)
                    <tr
                        data-role="{{ $user->role }}"
                        data-search="{{ strtolower($user->name . ' ' . ($user->username ?? '')) }}"
                        class="row-visible"
                        style="animation-delay: {{ $i * 0.035 }}s"
                    >
                        <td class="td-number">{{ $i + 1 }}</td>

                        <td>
                            <div class="creator-cell">
                                <div class="avatar user-avatar-{{ $user->role }}">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="user-fullname user-searchable">{{ $user->name }}</div>
                                    <div class="user-joined">Bergabung {{ $user->created_at->translatedFormat('d M Y') }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="user-username">{{ $user->username ?? '-' }}</span>
                        </td>

                        <td>
                            @if($user->role === 'admin')
                                <span class="role-badge role-admin">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    Admin
                                </span>
                            @elseif($user->role === 'notulis')
                                <span class="role-badge role-notulis">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Notulis
                                </span>
                            @else
                                <span class="role-badge role-viewer">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Viewer
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-edit">
                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-danger btn-delete" data-name="{{ $user->name }}">
                                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                </svg>
                                <div class="empty-title">Belum ada user</div>
                                <div class="empty-sub">Tambahkan user pertama dengan klik tombol di atas</div>
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
</div>

{{-- ══ DELETE MODAL ══ --}}
<div id="deleteModal" class="modal-backdrop" style="display:none;">
    <div class="modal-box">
        <div class="modal-icon-wrap modal-icon-danger">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
            </svg>
        </div>
        <div class="modal-title">Hapus Pengguna</div>
        <div class="modal-body">
            Apakah Anda yakin ingin menghapus <strong id="deleteTargetName"></strong>?
            <br><span style="color:#94a3b8; font-size:12px;">Tindakan ini tidak bisa dibatalkan.</span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="modalCancel">Batal</button>
            <button type="button" class="btn-primary" id="modalConfirm" style="background:#dc2626;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

</x-app-layout>