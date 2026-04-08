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

  <form action="{{ route('meetings.store') }}" method="POST" id="form-create">
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
            <option value="Internal DISKOMINFO"  {{ old('jenis') === 'Internal DISKOMINFO'  ? 'selected' : '' }}>Internal DISKOMINFO</option>
            <option value="Eksternal DISKOMINFO" {{ old('jenis') === 'Eksternal DISKOMINFO' ? 'selected' : '' }}>Eksternal DISKOMINFO</option>
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

        {{-- ── Partisipan Dinamis ── --}}
        <div class="form-section-label">Daftar Partisipan</div>

        <div class="form-group full">
          {{-- Hidden field — dikirim ke server sebagai JSON --}}
          <input type="hidden" name="partisipan" id="partisipan-json">

          {{-- Tabel --}}
          <div class="partisipan-wrap">
            <table class="partisipan-table" id="partisipan-table">
              <thead>
                <tr>
                  <th style="width:36px;">#</th>
                  <th>Nama</th>
                  <th style="width:220px;">Peran / Jabatan</th>
                  <th style="width:44px;"></th>
                </tr>
              </thead>
              <tbody id="partisipan-tbody">
                {{-- baris awal via JS --}}
              </tbody>
            </table>

            {{-- Tombol tambah baris --}}
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

@push('scripts')
<script>
/* ── Partisipan Roles ── */
const ROLES = [
  'Pimpinan Rapat',
  'Sekretaris / Notulis',
  'Narasumber',
  'Peserta',
  'Undangan',
];

const tbody   = document.getElementById('partisipan-tbody');
const jsonIn  = document.getElementById('partisipan-json');
const countEl = document.getElementById('partisipan-count');

/* Nilai lama saat validasi gagal */
let initialRows = [];
try {
  const old = @json(old('partisipan'));
  if (old) initialRows = JSON.parse(old);
} catch(e) {}

if (!initialRows.length) {
  initialRows = [
    { nama: '', peran: 'Pimpinan Rapat' },
    { nama: '', peran: 'Sekretaris / Notulis' },
    { nama: '', peran: 'Peserta' },
  ];
}

function buildRoleOptions(selected) {
  return ROLES.map(r =>
    `<option value="${r}" ${r === selected ? 'selected' : ''}>${r}</option>`
  ).join('') +
  `<option value="Lainnya" ${ !ROLES.includes(selected) && selected ? 'selected' : '' }>Lainnya…</option>`;
}

function addRow(data = { nama: '', peran: 'Peserta' }) {
  const tr = document.createElement('tr');
  tr.className = 'partisipan-row';
  tr.style.animation = 'rowIn .2s ease both';

  const isOther = data.peran && !ROLES.includes(data.peran);

  tr.innerHTML = `
    <td class="td-num"></td>
    <td>
      <input type="text" class="form-input p-nama" placeholder="Nama peserta…" value="${escHtml(data.nama || '')}">
    </td>
    <td>
      <div style="display:flex;gap:6px;align-items:center;">
        <select class="form-select p-peran" style="flex:1;">
          ${buildRoleOptions(isOther ? 'Lainnya' : (data.peran || 'Peserta'))}
        </select>
        <input type="text" class="form-input p-peran-custom"
          placeholder="Tulis peran…"
          value="${isOther ? escHtml(data.peran) : ''}"
          style="flex:1; display:${isOther ? 'block' : 'none'};">
      </div>
    </td>
    <td>
      <button type="button" class="partisipan-del-btn" title="Hapus baris">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </td>`;

  /* Toggle custom input */
  tr.querySelector('.p-peran').addEventListener('change', function() {
    const custom = tr.querySelector('.p-peran-custom');
    if (this.value === 'Lainnya') {
      custom.style.display = 'block';
      custom.focus();
    } else {
      custom.style.display = 'none';
      custom.value = '';
    }
    syncJson();
  });

  /* Delete row */
  tr.querySelector('.partisipan-del-btn').addEventListener('click', function() {
    if (tbody.querySelectorAll('.partisipan-row').length <= 1) return; // keep min 1
    tr.style.animation = 'none';
    tr.style.opacity = '0';
    tr.style.transform = 'translateX(12px)';
    tr.style.transition = 'opacity .18s ease, transform .18s ease';
    setTimeout(() => { tr.remove(); syncJson(); renumber(); }, 180);
  });

  tr.querySelectorAll('input, select').forEach(el => el.addEventListener('input', syncJson));
  tbody.appendChild(tr);
  renumber();
  syncJson();
}

function renumber() {
  tbody.querySelectorAll('.partisipan-row').forEach((tr, i) => {
    tr.querySelector('.td-num').textContent = i + 1;
  });
}

function syncJson() {
  const rows = [];
  tbody.querySelectorAll('.partisipan-row').forEach(tr => {
    const nama  = tr.querySelector('.p-nama').value.trim();
    const sel   = tr.querySelector('.p-peran').value;
    const peran = sel === 'Lainnya'
      ? tr.querySelector('.p-peran-custom').value.trim()
      : sel;
    if (nama || peran) rows.push({ nama, peran });
  });
  jsonIn.value = JSON.stringify(rows);

  const total = rows.filter(r => r.nama).length;
  countEl.textContent = total + ' peserta';
}

function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* Init */
initialRows.forEach(r => addRow(r));

document.getElementById('btn-add-row').addEventListener('click', () => addRow());

/* Serialize sebelum submit */
document.getElementById('form-create').addEventListener('submit', syncJson);
</script>
@endpush

</x-app-layout>