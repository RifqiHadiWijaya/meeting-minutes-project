<x-app-layout>

{{-- Top Bar --}}
<div class="page-topbar">
  <div style="display:flex; align-items:flex-start; gap:12px;">
    <a href="{{ route('meetings.index') }}" class="btn-back" title="Kembali ke daftar rapat">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
      </svg>
      <span class="btn-back-label">Kembali</span>
    </a>
    <div>
      <div class="page-title">{{ $meeting->judul }}</div>
      <div class="page-subtitle">Detail Rapat</div>
    </div>
  </div>
  <a href="{{ route('meetings.pdf', $meeting->id) }}" class="btn-pdf">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
      <polyline points="7 10 12 15 17 10"/>
      <line x1="12" y1="15" x2="12" y2="3"/>
    </svg>
    Download PDF
  </a>
</div>

{{-- Info Rapat --}}
<div class="card">
  <div class="card-header">
    <span class="card-header-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      Informasi Rapat
    </span>
    @if($meeting->status === 'completed')
      <span class="badge badge-completed"><span class="badge-dot"></span> Selesai</span>
    @else
      <span class="badge badge-scheduled"><span class="badge-dot"></span> Terjadwal</span>
    @endif
  </div>
  <div class="card-body">
    <div class="detail-grid">
      <div class="detail-item">
        <span class="detail-label">Tanggal</span>
        <span class="detail-value">
          {{ \Carbon\Carbon::parse($meeting->tanggal)->translatedFormat('d F Y') }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Waktu</span>
        <span class="detail-value">
          {{ \Carbon\Carbon::parse($meeting->waktu)->format('H:i') }} WIB
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Lokasi</span>
        <span class="detail-value">{{ $meeting->lokasi }}</span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Jenis Rapat</span>
        <span class="detail-value">{{ $meeting->jenis }}</span>
      </div>
      <div class="detail-item full">
        <span class="detail-label">Topik</span>
        <span class="detail-value">{{ $meeting->topik }}</span>
      </div>

      {{-- ── Partisipan Terstruktur ── --}}
      <div class="detail-item full">
        <span class="detail-label">Partisipan</span>

        @php
          /* Parse JSON; fallback ke teks lama (string biasa) */
          $rawPartisipan = $meeting->partisipan;
          $rows = [];
          if ($rawPartisipan) {
            $decoded = json_decode($rawPartisipan, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
              $rows = $decoded;
            }
          }

          /* Kelompokkan berdasarkan peran */
          $grouped = [];
          foreach ($rows as $row) {
            if (empty($row['nama']) && empty($row['peran'])) continue;
            $peran = $row['peran'] ?? 'Peserta';
            $grouped[$peran][] = $row['nama'] ?? '';
          }

          /* Urutan tampilan yang diinginkan */
          $roleOrder = ['Pimpinan Rapat','Sekretaris / Notulis','Narasumber','Peserta','Undangan'];
          $orderedGroups = [];
          foreach ($roleOrder as $r) {
            if (isset($grouped[$r])) $orderedGroups[$r] = $grouped[$r];
          }
          /* Peran lainnya (custom) di akhir */
          foreach ($grouped as $r => $names) {
            if (!in_array($r, $roleOrder)) $orderedGroups[$r] = $names;
          }
        @endphp

        @if(count($orderedGroups))
          <div class="partisipan-display">
            @foreach($orderedGroups as $peran => $names)
              <div class="partisipan-group">
                {{-- Ikon per peran --}}
                @php
                  $roleColors = [
                    'Pimpinan Rapat'      => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'color' => '#1d4ed8', 'dot' => '#3b82f6'],
                    'Sekretaris / Notulis'=> ['bg' => '#ede9fe', 'border' => '#ddd6fe', 'color' => '#7c3aed', 'dot' => '#8b5cf6'],
                    'Narasumber'          => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'color' => '#c2410c', 'dot' => '#f97316'],
                    'Peserta'             => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'color' => '#15803d', 'dot' => '#22c55e'],
                    'Undangan'            => ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'color' => '#475569', 'dot' => '#94a3b8'],
                  ];
                  $style = $roleColors[$peran] ?? ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'color' => '#475569', 'dot' => '#94a3b8'];
                @endphp
                <div class="partisipan-group-header"
                     style="background:{{ $style['bg'] }}; border-bottom-color:{{ $style['border'] }}; color:{{ $style['color'] }};">
                  <span class="partisipan-group-dot" style="background:{{ $style['dot'] }};"></span>
                  {{ $peran }}
                  <span class="partisipan-group-count"
                        style="background:{{ $style['border'] }}; color:{{ $style['color'] }};">
                    {{ count(array_filter($names)) }}
                  </span>
                </div>
                <div class="partisipan-names">
                  @foreach($names as $nama)
                    @if(trim($nama))
                      <span class="partisipan-name-chip">
                        <span class="partisipan-name-avatar" style="background:{{ $style['dot'] }};">
                          {{ mb_strtoupper(mb_substr(trim($nama), 0, 1)) }}
                        </span>
                        {{ trim($nama) }}
                      </span>
                    @endif
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        @elseif($rawPartisipan)
          {{-- Fallback: data lama berupa plain text --}}
          <span class="detail-value" style="white-space:pre-line;">{{ $rawPartisipan }}</span>
        @else
          <span class="detail-value" style="color:#94a3b8;">—</span>
        @endif
      </div>

      <div class="detail-item">
        <span class="detail-label">Dibuat oleh</span>
        <span class="detail-value">{{ $meeting->display_creator_name }}</span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Notulis</span>
        <span class="detail-value">{{ $meeting->display_notulen_name }}</span>
      </div>
    </div>
  </div>
</div>

{{-- Notulensi --}}
<div class="card">
  <div class="card-header">
    <span class="card-header-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/>
        <line x1="16" y1="17" x2="8" y2="17"/>
        <polyline points="10 9 9 9 8 9"/>
      </svg>
      Notulensi Rapat
    </span>
  </div>
  <div class="card-body">
    @if($meeting->notulensi)
      <div class="prose-area">{!! $meeting->notulensi !!}</div>
    @else
      <p style="font-size:13px; color:#94a3b8; text-align:center; padding: 24px 0;">
        Notulensi belum tersedia.
      </p>
    @endif
  </div>
</div>

{{-- Dokumentasi Foto --}}
@if($meeting->dokumentasi->count())
<div class="card">
  <div class="card-header">
    <span class="card-header-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
        <polyline points="21 15 16 10 5 21"/>
      </svg>
      Dokumentasi Foto
    </span>
    <span style="font-size:12px; color:#94a3b8;">{{ $meeting->dokumentasi->count() }} foto</span>
  </div>
  <div class="card-body">
    <div style="display:flex; flex-wrap:wrap; gap:12px;">
      @foreach($meeting->dokumentasi as $foto)
      <a href="{{ asset('storage/' . $foto->path_file) }}" target="_blank" title="{{ $foto->nama_file }}">
        <img src="{{ asset('storage/' . $foto->path_file) }}"
             alt="{{ $foto->nama_file }}"
             style="width:150px; height:110px; object-fit:cover; border-radius:8px;
                    border:1px solid #e2e8f0; transition: opacity .2s;"
             onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
      </a>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- Pertanyaan & Klarifikasi --}}
<div class="card qa-section">
  <div class="card-header">
    <span class="card-header-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      Pertanyaan &amp; Klarifikasi
    </span>
    <span class="qa-counter">{{ $meeting->questions->count() }} pertanyaan</span>
  </div>
  <div class="card-body">

    @if(auth()->user()->role === 'viewer')
    <form action="{{ route('questions.store', $meeting->id) }}" method="POST" class="qa-form qa-question-form" style="margin-bottom: 24px;">
      @csrf
      <textarea name="isi" placeholder="Tulis pertanyaan atau klarifikasi Anda..." required></textarea>
      <div class="qa-form-footer">
        <button type="submit" class="btn-primary">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"/>
            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
          </svg>
          Kirim Pertanyaan
        </button>
      </div>
    </form>
    @endif

    <div class="qa-list"></div>
    @forelse($meeting->questions as $question)
    <div class="question-card">
      <div class="question-meta">
        <div class="avatar-sm">{{ $question->user_initials }}</div>
        <span class="question-author">{{ $question->display_user_name }}</span>
        <span style="font-size:11px; color:#94a3b8; margin-left:auto;">
          {{ \Carbon\Carbon::parse($question->created_at)->format('d M Y, H:i') }}
        </span>
      </div>
      <div class="question-isi">{{ $question->isi }}</div>

      @if($question->replies->count())
      <div class="reply-wrap">
        @foreach($question->replies as $reply)
        <div class="reply-card reply-rendered">
          <div class="avatar-sm" style="background: linear-gradient(135deg,#10b981,#0891b2);">
            {{ $reply->user_initials }}
          </div>
          <div class="reply-content">
            <div class="reply-author">{{ $reply->display_user_name }}</div>
            <div class="reply-isi">{{ $reply->isi }}</div>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      @if(auth()->user()->role === 'notulis' && auth()->id() === $meeting->created_by)
      <div class="reply-form-wrap">
        <div class="reply-form-label">Tulis Jawaban</div>
        <form action="{{ route('questions.reply', $question->id) }}" method="POST" class="qa-form qa-reply-form">
          @csrf
          <textarea name="isi" placeholder="Tulis jawaban..." required style="min-height:64px;"></textarea>
          <div class="qa-form-footer">
            <button type="submit" class="btn-primary" style="font-size:12px; padding:6px 14px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 17 4 12 9 7"/>
                <path d="M20 18v-2a4 4 0 0 0-4-4H4"/>
              </svg>
              Balas
            </button>
          </div>
        </form>
      </div>
      @endif

    </div>
    @empty
    <div class="empty-qa">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px;">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      <div>Belum ada pertanyaan untuk rapat ini.</div>
    </div>
    @endforelse
    </div>

  </div>
</div>

</x-app-layout>