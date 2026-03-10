/**
 * modal.js
 * Reusable konfirmasi modal sebelum submit form (misal: hapus data).
 *
 * Cara pakai:
 *   import { initDeleteModal } from '../modules/modal.js';
 *   initDeleteModal();
 *
 * Syarat di HTML:
 *   - Tombol hapus pakai class .btn-delete dan data-name="Nama Target"
 *   - Form pembungkus tombol punya class .delete-form
 *   - Modal markup sudah ada di blade (lihat contoh di users/index.blade.php)
 */

export function initDeleteModal() {
    const modal        = document.getElementById('deleteModal');
    const targetName   = document.getElementById('deleteTargetName');
    const confirmBtn   = document.getElementById('modalConfirm');
    const cancelBtn    = document.getElementById('modalCancel');

    if (!modal) return;

    let pendingForm = null;

    // Buka modal saat klik tombol hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            pendingForm = this.closest('.delete-form');
            if (targetName) targetName.textContent = this.dataset.name || 'item ini';
            modal.style.display = 'flex';
            requestAnimationFrame(() => modal.classList.add('modal-open'));
        });
    });

    // Tutup modal
    function closeModal() {
        modal.classList.remove('modal-open');
        setTimeout(() => {
            modal.style.display = 'none';
            pendingForm = null;
            // Reset tombol konfirmasi jika sempat loading
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = `
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    </svg>
                    Ya, Hapus`;
            }
        }, 200);
    }

    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // Submit form dengan loading state
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (!pendingForm) return;
            this.disabled = true;
            this.innerHTML = `
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="animation:spin 1s linear infinite">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Menghapus...`;
            pendingForm.submit();
        });
    }
}