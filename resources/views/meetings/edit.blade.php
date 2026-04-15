<x-app-layout>

{{-- Top Bar --}}
<div class="page-topbar">
  <div>
    <div class="page-title">Edit Rapat</div>
    <div class="page-subtitle">{{ $meeting->judul }}</div>
  </div>
  @if($meeting->status === 'completed')
    <span class="badge badge-completed"><span class="badge-dot"></span> Selesai</span>
  @else
    <span class="badge badge-scheduled"><span class="badge-dot"></span> Terjadwal</span>
  @endif
</div>

{{-- Session success --}}
@if(session('success'))
<div class="alert-success">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="20 6 9 17 4 12"/>
  </svg>
  {{ session('success') }}
</div>
@endif

{{-- Validation errors --}}
@if($errors->any())
<div class="alert-error">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;">
    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
  </svg>
  <div>
    <strong style="font-weight:700;">Terdapat kesalahan pada form:</strong>
    <ul style="margin:4px 0 0 16px; list-style:disc;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
</div>
@endif

<form action="{{ route('meetings.update', $meeting->id) }}" method="POST" enctype="multipart/form-data" id="form-edit">
@csrf
@method('PUT')

  {{-- Card 1: Info Rapat --}}
  <div class="card">
    <div class="card-header">
      <span class="card-header-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        Informasi Rapat
      </span>
    </div>
    <div class="card-body">
      <div class="form-grid">

        {{-- Judul --}}
        <div class="form-group full">
          <label class="form-label">Judul Rapat <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </span>
            <input type="text" name="judul"
              class="form-input {{ $errors->has('judul') ? 'is-error' : '' }}"
              value="{{ old('judul', $meeting->judul) }}" required>
          </div>
          @error('judul') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Tanggal --}}
        <div class="form-group">
          <label class="form-label">Tanggal <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </span>
            <input type="date" name="tanggal"
              class="form-input {{ $errors->has('tanggal') ? 'is-error' : '' }}"
              value="{{ old('tanggal', $meeting->tanggal) }}" required>
          </div>
          @error('tanggal') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Waktu --}}
        <div class="form-group">
          <label class="form-label">Waktu <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
              </svg>
            </span>
            <input type="time" name="waktu"
              class="form-input {{ $errors->has('waktu') ? 'is-error' : '' }}"
              value="{{ old('waktu', $meeting->waktu) }}" required>
          </div>
          @error('waktu') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Lokasi --}}
        <div class="form-group">
          <label class="form-label">Lokasi <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
              </svg>
            </span>
            <input type="text" name="lokasi"
              class="form-input {{ $errors->has('lokasi') ? 'is-error' : '' }}"
              value="{{ old('lokasi', $meeting->lokasi) }}" required>
          </div>
          @error('lokasi') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Jenis --}}
        <div class="form-group">
          <label class="form-label">Jenis Rapat <span class="required">*</span></label>
          <select name="jenis" class="form-select {{ $errors->has('jenis') ? 'is-error' : '' }}">
            <option value="" disabled {{ old('jenis', $meeting->jenis ?? '') == '' ? 'selected' : '' }}>Pilih jenis rapat...</option>
            @foreach(['Internal DISKOMINFO', 'Eksternal DISKOMINFO'] as $jenis)
              <option value="{{ $jenis }}" {{ old('jenis', $meeting->jenis ?? '') === $jenis ? 'selected' : '' }}>
                {{ $jenis }}
              </option>
            @endforeach
          </select>
          @error('jenis') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Divider --}}
        <div class="form-section-label">Konten Rapat</div>

        {{-- Topik --}}
        <div class="form-group full">
          <label class="form-label">Topik / Agenda</label>
          <textarea name="topik" class="form-textarea {{ $errors->has('topik') ? 'is-error' : '' }}"
            placeholder="Uraikan topik atau agenda yang akan dibahas...">{{ old('topik', $meeting->topik) }}</textarea>
          @error('topik') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Partisipan Dinamis --}}
        <div class="form-section-label">Daftar Partisipan</div>

        <div class="form-group full">
          {{-- Nilai awal di-isi langsung dari Blade agar tidak kosong sebelum JS jalan --}}
          <input type="hidden" name="partisipan" id="partisipan-json"
                 value="{{ old('partisipan', $meeting->partisipan) }}">

          <div class="partisipan-wrap">
            <table class="partisipan-table">
              <thead>
                <tr>
                  <th style="width:36px;">#</th>
                  <th>Nama</th>
                  <th style="width:220px;">Peran / Jabatan</th>
                  <th style="width:44px;"></th>
                </tr>
              </thead>
              <tbody id="partisipan-tbody"></tbody>
            </table>
            <div class="partisipan-actions">
              <button type="button" class="partisipan-add-btn" id="btn-add-row">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Peserta
              </button>
              <span class="partisipan-count-label" id="partisipan-count">0 peserta</span>
            </div>
          </div>

          @error('partisipan') <span class="form-error">{{ $message }}</span> @enderror
          <span class="form-hint">Isi nama dan peran/jabatan setiap peserta. Baris kosong akan diabaikan.</span>
        </div>

      </div>
    </div>
  </div>

  {{-- Card 2: Notulensi --}}
  <div class="card">
    <div class="card-header">
      <span class="card-header-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
        Notulensi Rapat
      </span>
      <span style="font-size:11px;color:#94a3b8;">Editor teks kaya (TinyMCE)</span>
    </div>
    <div class="card-body">
      {{-- ID eksplisit supaya selector TinyMCE tidak ambigu --}}
      <textarea name="notulensi" id="notulensi-editor"
                class="{{ $errors->has('notulensi') ? 'is-error' : '' }}">{{ old('notulensi', $meeting->notulensi) }}</textarea>
      @error('notulensi') <span class="form-error" style="margin-top:6px;display:block;">{{ $message }}</span> @enderror
    </div>
  </div>

  {{-- Card 3: Dokumentasi Foto --}}
  <div class="card">
    <div class="card-header">
      <span class="card-header-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
        Dokumentasi Foto
      </span>
      <span style="font-size:11px;color:#94a3b8;">Maks. 3MB per foto (JPG, PNG, WEBP)</span>
    </div>
    <div class="card-body">

      @if($meeting->dokumentasi->count())
      <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
        @foreach($meeting->dokumentasi as $foto)
        <div style="position:relative; width:130px;">
          <img src="{{ asset('storage/' . $foto->path_file) }}"
              alt="{{ $foto->nama_file }}"
              style="width:130px; height:100px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;">
          {{-- Tombol hapus — trigger form tersembunyi di LUAR form utama --}}
          <button type="button"
            onclick="hapusFoto({{ $foto->id }}, '{{ $foto->nama_file }}')"
            style="position:absolute; top:4px; right:4px; background:rgba(239,68,68,0.85); border:none;
                   border-radius:50%; width:24px; height:24px; color:#fff; cursor:pointer;
                   font-size:14px; line-height:1; display:flex; align-items:center; justify-content:center;">
            &times;
          </button>
          <div style="font-size:10px; color:#94a3b8; margin-top:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
            {{ $foto->nama_file }}
          </div>
        </div>
        @endforeach
      </div>
      @endif

      <label class="form-label">Tambah Foto Baru</label>
      <input type="file" name="dokumentasi[]" multiple accept="image/*" class="form-input">
      <span class="form-hint">Pilih lebih dari satu file untuk upload sekaligus.</span>
      @error('dokumentasi.*') <span class="form-error">{{ $message }}</span> @enderror
    </div>
  </div>

  {{-- Card 4: Status --}}
  <div class="card">
    <div class="card-header">
      <span class="card-header-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 11 12 14 22 4"/>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
        Status Rapat
      </span>
    </div>
    <div class="card-body">
      <div class="status-pills">
        <label class="status-pill scheduled">
          <input type="radio" name="status" value="scheduled"
            {{ old('status', $meeting->status) === 'scheduled' ? 'checked' : '' }}>
          <span class="status-pill-dot"></span>
          Terjadwal
        </label>
        <label class="status-pill completed">
          <input type="radio" name="status" value="completed"
            {{ old('status', $meeting->status) === 'completed' ? 'checked' : '' }}>
          <span class="status-pill-dot"></span>
          Selesai
        </label>
      </div>
      <span class="form-hint" style="margin-top:8px;display:block;">
        Ubah status menjadi <strong>Selesai</strong> jika rapat telah selesai dilaksanakan dan notulensi sudah lengkap.
      </span>
    </div>
  </div>

  {{-- Footer --}}
  <div class="card" style="overflow:visible;">
    <div class="form-footer" style="border-radius:0 0 12px 12px;">
      <a href="{{ route('meetings.index') }}" class="btn-secondary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Batal
      </a>
      <button type="submit" class="btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/>
          <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
        </svg>
        Simpan Perubahan
      </button>
    </div>
  </div>

</form>

{{-- ── Form hapus foto — DI LUAR form utama agar tidak nested ── --}}
<form id="form-hapus-foto"
      action="{{ route('meetings.dokumentasi.delete', $meeting->id) }}"
      method="POST" style="display:none;">
  @csrf
  @method('DELETE')
  <input type="hidden" name="dok_id" id="hapus-dok-id">
</form>

{{-- ── Modal konfirmasi hapus foto ── --}}
<div id="modal-hapus-foto" class="modal-backdrop" style="display:none;">
  <div class="modal-box">
    <div class="modal-icon-wrap modal-icon-danger">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        <path d="M10 11v6"/><path d="M14 11v6"/>
        <path d="M9 6V4h6v2"/>
      </svg>
    </div>
    <div class="modal-title">Hapus Foto?</div>
    <div class="modal-body">
      Foto <strong id="modal-foto-nama" style="color:var(--slate-900);"></strong> akan dihapus permanen dan tidak bisa dikembalikan.
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-secondary" id="modal-foto-batal">Batal</button>
      <button type="button" class="btn-primary" id="modal-foto-konfirm"
              style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 4px 14px rgba(239,68,68,.3);">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        </svg>
        Ya, Hapus
      </button>
    </div>
  </div>
</div>

{{-- ╔══════════════════════════════════════════════════════════╗
     ║  @push HARUS di dalam <x-app-layout>, bukan di luarnya  ║
     ║  <script> logic dipindah ke resources/js/pages/edit.js  ║
     ╚══════════════════════════════════════════════════════════╝ --}}
@push('scripts')
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
@endpush

</x-app-layout>