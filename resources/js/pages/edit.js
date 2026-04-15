/**
 * pages/edit.js
 * Semua JS yang sebelumnya ada di @push('scripts') dalam edit.blade.php.
 * Dipindah ke sini agar blade bersih dari inline script.
 *
 * Dijalankan otomatis oleh app.js saat DOMContentLoaded.
 * Auto-skip jika elemen #form-edit tidak ada di halaman.
 */

export function initEdit() {
    if (!document.getElementById('form-edit')) return;

    // TinyMCE di-load via @push di blade, tunggu sampai tersedia
    if (typeof tinymce !== 'undefined') {
        initTinyMCE();
    }

    initPartisipan();
    initHapusFoto();
    initFormSubmit();
}

/* ─── Hapus foto ─────────────────────────────────────────── */
function initHapusFoto() {
    const modal      = document.getElementById('modal-hapus-foto');
    const modalNama  = document.getElementById('modal-foto-nama');
    const btnBatal   = document.getElementById('modal-foto-batal');
    const btnKonfirm = document.getElementById('modal-foto-konfirm');

    if (!modal) return;

    let pendingId = null;

    /* Dipanggil dari onclick="hapusFoto(...)" di blade */
    window.hapusFoto = function(dokId, namaFile) {
        pendingId = dokId;
        if (modalNama) modalNama.textContent = namaFile;
        modal.style.display = 'flex';
        requestAnimationFrame(() => modal.classList.add('modal-open'));
    };

    function tutup() {
        modal.classList.remove('modal-open');
        setTimeout(() => {
            modal.style.display = 'none';
            pendingId = null;
            if (btnKonfirm) {
                btnKonfirm.disabled = false;
                btnKonfirm.innerHTML = `
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    </svg> Ya, Hapus`;
            }
        }, 200);
    }

    if (btnBatal) btnBatal.addEventListener('click', tutup);
    modal.addEventListener('click', e => { if (e.target === modal) tutup(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && pendingId) tutup(); });

    if (btnKonfirm) {
        btnKonfirm.addEventListener('click', function() {
            if (!pendingId) return;
            this.disabled  = true;
            this.innerHTML = `
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="animation:spin 1s linear infinite">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg> Menghapus...`;
            document.getElementById('hapus-dok-id').value = pendingId;
            document.getElementById('form-hapus-foto').submit();
        });
    }
}

/* ─── TinyMCE ─────────────────────────────────────────────── */
function initTinyMCE() {
    tinymce.init({
        selector: '#notulensi-editor',
        license_key: 'gpl',
        promotion: false,
        branding: false,
        height: 420,
        menubar: 'file edit view insert format tools table',
        plugins: 'lists link table',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | table',
        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',
        valid_elements: 'p,h1,h2,h3,strong/b,em/i,u,ul,ol,li,table,thead,tbody,tr,th,td,a[href|target=_blank]',
        forced_root_block: 'p',
        skin: 'oxide',
        content_css: 'default',
        setup: function(editor) {
            editor.on('init', function() {
                editor.getContainer().style.borderRadius = '7px';
                editor.getContainer().style.border = '1px solid #e2e8f0';
                editor.getContainer().style.overflow = 'hidden';
            });
            editor.on('focus', function() {
                editor.getContainer().style.borderColor = '#3b82f6';
                editor.getContainer().style.boxShadow = '0 0 0 3px rgba(59,130,246,.08)';
            });
            editor.on('blur', function() {
                editor.getContainer().style.borderColor = '#e2e8f0';
                editor.getContainer().style.boxShadow = 'none';
            });
        }
    });
}

/* ─── Partisipan dinamis ──────────────────────────────────── */
function initPartisipan() {
    const ROLES = ['Pimpinan Rapat','Sekretaris / Notulis','Narasumber','Peserta','Undangan'];

    const tbody   = document.getElementById('partisipan-tbody');
    const jsonIn  = document.getElementById('partisipan-json');
    const countEl = document.getElementById('partisipan-count');

    if (!tbody || !jsonIn) return;

    /* Baca nilai awal dari hidden field yang sudah di-set Blade */
    let initialRows = [];
    try {
        const raw = jsonIn.value.trim();
        if (raw) initialRows = JSON.parse(raw);
    } catch(e) { console.warn('Partisipan parse error:', e); }

    if (!initialRows.length) {
        initialRows = [{ nama: '', peran: 'Pimpinan Rapat' }, { nama: '', peran: 'Peserta' }];
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function buildRoleOptions(selected) {
        const isOther = selected && !ROLES.includes(selected);
        return ROLES.map(r =>
            `<option value="${r}" ${r === selected ? 'selected' : ''}>${r}</option>`
        ).join('') + `<option value="Lainnya" ${isOther ? 'selected' : ''}>Lainnya…</option>`;
    }

    function syncJson() {
        const rows = [];
        tbody.querySelectorAll('.partisipan-row').forEach(tr => {
            const nama  = tr.querySelector('.p-nama').value.trim();
            const sel   = tr.querySelector('.p-peran').value;
            const peran = sel === 'Lainnya' ? tr.querySelector('.p-peran-custom').value.trim() : sel;
            if (nama || peran) rows.push({ nama, peran });
        });
        jsonIn.value = JSON.stringify(rows);
        countEl.textContent = rows.filter(r => r.nama).length + ' peserta';
    }

    function renumber() {
        tbody.querySelectorAll('.partisipan-row').forEach((tr, i) => {
            tr.querySelector('.td-num').textContent = i + 1;
        });
    }

    function addRow(data) {
        data = data || { nama: '', peran: 'Peserta' };
        const isOther = data.peran && !ROLES.includes(data.peran);
        const tr = document.createElement('tr');
        tr.className = 'partisipan-row';
        tr.style.animation = 'rowIn .2s ease both';
        tr.innerHTML = `
            <td class="td-num"></td>
            <td><input type="text" class="form-input p-nama" placeholder="Nama peserta…" value="${escHtml(data.nama || '')}"></td>
            <td>
              <div style="display:flex;gap:6px;align-items:center;">
                <select class="form-select p-peran" style="flex:1;">${buildRoleOptions(isOther ? 'Lainnya' : (data.peran || 'Peserta'))}</select>
                <input type="text" class="form-input p-peran-custom" placeholder="Tulis peran…"
                  value="${isOther ? escHtml(data.peran) : ''}"
                  style="flex:1;display:${isOther ? 'block' : 'none'};">
              </div>
            </td>
            <td>
              <button type="button" class="partisipan-del-btn" title="Hapus baris">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </td>`;

        tr.querySelector('.p-peran').addEventListener('change', function() {
            const custom = tr.querySelector('.p-peran-custom');
            if (this.value === 'Lainnya') { custom.style.display = 'block'; custom.focus(); }
            else { custom.style.display = 'none'; custom.value = ''; }
            syncJson();
        });

        tr.querySelector('.partisipan-del-btn').addEventListener('click', function() {
            if (tbody.querySelectorAll('.partisipan-row').length <= 1) return;
            tr.style.opacity = '0'; tr.style.transform = 'translateX(12px)';
            tr.style.transition = 'opacity .18s, transform .18s';
            setTimeout(() => { tr.remove(); syncJson(); renumber(); }, 180);
        });

        tr.querySelectorAll('input, select').forEach(el => el.addEventListener('input', syncJson));
        tbody.appendChild(tr);
        renumber();
        syncJson();
    }

    initialRows.forEach(r => addRow(r));
    document.getElementById('btn-add-row').addEventListener('click', () => addRow());
}

/* ─── Submit handler ─────────────────────────────────────── */
function initFormSubmit() {
    document.getElementById('form-edit').addEventListener('submit', function() {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
        // syncJson dipanggil lewat event input, tapi panggil sekali lagi untuk jaga-jaga
        const tbody  = document.getElementById('partisipan-tbody');
        const jsonIn = document.getElementById('partisipan-json');
        if (!tbody || !jsonIn) return;
        const rows = [];
        tbody.querySelectorAll('.partisipan-row').forEach(tr => {
            const nama  = tr.querySelector('.p-nama').value.trim();
            const sel   = tr.querySelector('.p-peran').value;
            const peran = sel === 'Lainnya' ? tr.querySelector('.p-peran-custom').value.trim() : sel;
            if (nama || peran) rows.push({ nama, peran });
        });
        jsonIn.value = JSON.stringify(rows);
    });
}