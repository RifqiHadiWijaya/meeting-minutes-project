<x-app-layout>

{{-- Top Bar --}}
<div class="page-topbar">
  <div>
    <div class="page-title">{{ $meeting->judul }}</div>
    <div class="page-subtitle">Detail Rapat</div>
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
      <span class="badge badge-completed">
        <span class="badge-dot"></span> Selesai
      </span>
    @else
      <span class="badge badge-scheduled">
        <span class="badge-dot"></span> Terjadwal
      </span>
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
      <div class="detail-item full">
        <span class="detail-label">Partisipan</span>
        <span class="detail-value">{{ $meeting->partisipan }}</span>
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

{{-- Pertanyaan & Klarifikasi --}}
<div class="card qa-section">
  <div class="card-header">
    <span class="card-header-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      Pertanyaan &amp; Klarifikasi
    </span>
    <span class="qa-counter">
      {{ $meeting->questions->count() }} pertanyaan
    </span>
  </div>
  <div class="card-body">

    {{-- Form Pertanyaan (Viewer) --}}
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

    {{-- List Pertanyaan --}}
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

      {{-- Replies --}}
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

      {{-- Form Jawaban (Notulis & Creator) --}}
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
    </div> {{-- /.qa-list --}}

  </div>
</div>

</x-app-layout>