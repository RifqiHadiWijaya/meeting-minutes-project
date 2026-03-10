<x-app-layout>


{{-- Top Bar --}}
<div class="page-topbar">
  <div>
    <div class="page-title">Buat Rapat Baru</div>
    <div class="page-subtitle">Isi detail rapat yang akan dijadwalkan</div>
  </div>
</div>

{{-- Validation Errors --}}
@if($errors->any())
<div class="alert-error">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;">
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

{{-- Form Card --}}
<div class="card">
  <div class="card-header">
    <span class="card-header-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      Detail Rapat
    </span>
  </div>

  <form action="{{ route('meetings.store') }}" method="POST">
    @csrf
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
            <input type="text" name="judul" class="form-input {{ $errors->has('judul') ? 'is-error' : '' }}"
              placeholder="Contoh: Rapat Koordinasi Bulanan Dinas" value="{{ old('judul') }}" required>
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
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </span>
            <input type="date" name="tanggal" class="form-input {{ $errors->has('tanggal') ? 'is-error' : '' }}"
              value="{{ old('tanggal') }}" required>
          </div>
          @error('tanggal') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Waktu --}}
        <div class="form-group">
          <label class="form-label">Waktu <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
              </svg>
            </span>
            <input type="time" name="waktu" class="form-input {{ $errors->has('waktu') ? 'is-error' : '' }}"
              value="{{ old('waktu') }}" required>
          </div>
          @error('waktu') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Lokasi --}}
        <div class="form-group">
          <label class="form-label">Lokasi <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
              </svg>
            </span>
            <input type="text" name="lokasi" class="form-input {{ $errors->has('lokasi') ? 'is-error' : '' }}"
              placeholder="Contoh: Ruang Rapat Lantai 3" value="{{ old('lokasi') }}" required>
          </div>
          @error('lokasi') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Jenis Rapat --}}
        <div class="form-group">
          <label class="form-label">Jenis Rapat <span class="required">*</span></label>
          <select name="jenis" class="form-select {{ $errors->has('jenis') ? 'is-error' : '' }}" required>
            <option value="" disabled {{ old('jenis') ? '' : 'selected' }}>Pilih jenis rapat...</option>
            <option value="Internal DISKOMINFO"   {{ old('jenis') === 'Internal DISKOMINFO'   ? 'selected' : '' }}>Internal DISKOMINFO</option>
            <option value="Eksternal DISKOMINFO"     {{ old('jenis') === 'Eksternal DISKOMINFO'     ? 'selected' : '' }}>Eksternal DISKOMINFO</option>
          </select>
          @error('jenis') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Divider: Konten --}}
        <div class="form-section-label">Konten Rapat</div>

        {{-- Topik --}}
        <div class="form-group full">
          <label class="form-label">Topik / Agenda <span class="required">*</span></label>
          <textarea name="topik" class="form-textarea {{ $errors->has('topik') ? 'is-error' : '' }}"
            placeholder="Uraikan topik atau agenda yang akan dibahas dalam rapat..." required>{{ old('topik') }}</textarea>
          @error('topik') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Partisipan --}}
        <div class="form-group full">
          <label class="form-label">Partisipan <span class="required">*</span></label>
          <textarea name="partisipan" class="form-textarea {{ $errors->has('partisipan') ? 'is-error' : '' }}"
            placeholder="Daftar nama atau jabatan peserta rapat, pisahkan dengan koma atau baris baru..." required>{{ old('partisipan') }}</textarea>
          <span class="form-hint">Contoh: Kepala Dinas, Sekretaris, Kabid Perencanaan, …</span>
          @error('partisipan') <span class="form-error">{{ $message }}</span> @enderror
        </div>

      </div>
    </div>

    {{-- Footer --}}
    <div class="form-footer">
      <a href="{{ route('meetings.index') }}" class="btn-secondary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Batal
      </a>
      <button type="submit" class="btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/>
          <polyline points="17 21 17 13 7 13 7 21"/>
          <polyline points="7 3 7 8 15 8"/>
        </svg>
        Simpan Rapat
      </button>
    </div>

  </form>
</div>

</x-app-layout>