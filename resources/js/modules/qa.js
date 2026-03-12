/**
 * modules/qa.js
 * Q&A dinamis untuk halaman meetings/show.
 *
 * Fitur:
 *  - Kirim pertanyaan (viewer) tanpa reload → append langsung ke list
 *  - Kirim jawaban (notulis) tanpa reload → append ke reply-wrap
 *  - Loading state pada tombol submit
 *  - Error handling dengan flash inline
 *  - Auto-resize textarea saat mengetik
 *  - Animasi entrance untuk item baru (.qa-visible, .reply-new)
 *  - Counter pertanyaan di card-header ikut update
 */

export function initQA() {
    const qaSection = document.querySelector('.qa-section');
    if (!qaSection) return;

    // ── Auto-resize semua textarea Q&A ──────────────────────
    qaSection.querySelectorAll('textarea').forEach(ta => autoResize(ta));
    qaSection.addEventListener('input', e => {
        if (e.target.tagName === 'TEXTAREA') autoResize(e.target);
    });

    // ── Form pertanyaan (viewer) ─────────────────────────────
    const questionForm = qaSection.querySelector('.qa-question-form');
    if (questionForm) {
        questionForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            await submitQuestion(this);
        });
    }

    // ── Form jawaban (notulis) — event delegation ────────────
    qaSection.addEventListener('submit', async function (e) {
        if (!e.target.classList.contains('qa-reply-form')) return;
        e.preventDefault();
        await submitReply(e.target);
    });
}

// ─────────────────────────────────────────────────────────────
// SUBMIT PERTANYAAN
// ─────────────────────────────────────────────────────────────
async function submitQuestion(form) {
    const textarea   = form.querySelector('textarea[name="isi"]');
    const submitBtn  = form.querySelector('[type="submit"]');
    const isi        = textarea.value.trim();

    if (!isi) {
        shakeField(textarea);
        return;
    }

    setLoading(submitBtn, true, 'Mengirim...');

    try {
        const res  = await fetchPost(form.action, { isi }, getCsrf(form));
        const data = await res.json();

        if (!res.ok) throw new Error(data.message || 'Gagal mengirim pertanyaan.');

        // Append kartu pertanyaan baru
        const list = document.querySelector('.qa-list');
        const emptyState = document.querySelector('.empty-qa');
        if (emptyState) emptyState.remove();

        const card = buildQuestionCard(data.question);
        list.appendChild(card);

        // Trigger entrance animation
        requestAnimationFrame(() => card.classList.add('qa-visible'));

        // Update counter
        updateQuestionCounter(1);

        // Reset form
        textarea.value = '';
        autoResize(textarea);
        showInlineSuccess(form, 'Pertanyaan berhasil dikirim!');

    } catch (err) {
        showInlineError(form, err.message);
    } finally {
        setLoading(submitBtn, false, originalLabel(submitBtn, 'Kirim Pertanyaan', sendIcon()));
    }
}

// ─────────────────────────────────────────────────────────────
// SUBMIT JAWABAN
// ─────────────────────────────────────────────────────────────
async function submitReply(form) {
    const textarea  = form.querySelector('textarea[name="isi"]');
    const submitBtn = form.querySelector('[type="submit"]');
    const isi       = textarea.value.trim();

    if (!isi) {
        shakeField(textarea);
        return;
    }

    setLoading(submitBtn, true, 'Mengirim...');

    try {
        const res  = await fetchPost(form.action, { isi }, getCsrf(form));
        const data = await res.json();

        if (!res.ok) throw new Error(data.message || 'Gagal mengirim jawaban.');

        // Cari atau buat reply-wrap
        const questionCard = form.closest('.question-card');
        let replyWrap = questionCard.querySelector('.reply-wrap');
        if (!replyWrap) {
            replyWrap = document.createElement('div');
            replyWrap.className = 'reply-wrap';
            // Sisipkan sebelum reply-form-wrap
            const replyFormWrap = questionCard.querySelector('.reply-form-wrap');
            questionCard.insertBefore(replyWrap, replyFormWrap);
        }

        // Append reply baru
        const replyEl = buildReplyCard(data.reply);
        replyWrap.appendChild(replyEl);

        // Trigger animasi
        requestAnimationFrame(() => replyEl.classList.add('reply-new'));

        // Reset textarea
        textarea.value = '';
        autoResize(textarea);
        showInlineSuccess(form, 'Jawaban berhasil dikirim!');

    } catch (err) {
        showInlineError(form, err.message);
    } finally {
        setLoading(submitBtn, false, originalLabel(submitBtn, 'Balas', replyIcon()));
    }
}

// ─────────────────────────────────────────────────────────────
// BUILD ELEMENTS
// ─────────────────────────────────────────────────────────────
function buildQuestionCard(q) {
    const card = document.createElement('div');
    card.className = 'question-card';
    card.dataset.id = q.id;

    // Nama: pakai user_name (snapshot kolom di MeetingQuestion)
    const initials = getInitials(q.user_name || 'U');

    card.innerHTML = `
        <div class="question-meta">
            <div class="avatar-sm">${initials}</div>
            <span class="question-author">${escHtml(q.user_name || '')}</span>
            <span style="font-size:11px; color:#94a3b8; margin-left:auto;">
                ${q.created_at_human || 'Baru saja'}
            </span>
        </div>
        <div class="question-isi">${escHtml(q.isi)}</div>
    `;
    return card;
}

function buildReplyCard(r) {
    const el = document.createElement('div');
    el.className = 'reply-card';

    const initials = getInitials(r.user_name || 'N');

    el.innerHTML = `
        <div class="avatar-sm" style="background:linear-gradient(135deg,#10b981,#0891b2);">
            ${initials}
        </div>
        <div class="reply-content">
            <div class="reply-author">${escHtml(r.user_name || '')}</div>
            <div class="reply-isi">${escHtml(r.isi)}</div>
        </div>
    `;
    return el;
}

// ─────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────
function fetchPost(url, data, csrf) {
    return fetch(url, {
        method : 'POST',
        headers: {
            'Content-Type'    : 'application/json',
            'X-CSRF-TOKEN'    : csrf,
            'Accept'          : 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(data),
    });
}

function getCsrf(form) {
    return form.querySelector('[name="_token"]')?.value
        || document.querySelector('meta[name="csrf-token"]')?.content
        || '';
}

function setLoading(btn, loading, label) {
    btn.disabled   = loading;
    btn.innerHTML  = loading
        ? `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               style="animation:spin 1s linear infinite">
               <polyline points="23 4 23 10 17 10"/>
               <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
           </svg> ${label}`
        : label;
}

function originalLabel(btn, fallback, iconHtml) {
    return `${iconHtml} ${btn.dataset.label || fallback}`;
}

function autoResize(ta) {
    ta.style.height = 'auto';
    ta.style.height = ta.scrollHeight + 'px';
}

function shakeField(el) {
    el.classList.remove('field-shake');
    void el.offsetWidth;
    el.classList.add('field-shake');
    el.addEventListener('animationend', () => el.classList.remove('field-shake'), { once: true });
    el.focus();
}

function updateQuestionCounter(delta) {
    const counter = document.querySelector('.qa-counter');
    if (!counter) return;
    const current = parseInt(counter.textContent, 10) || 0;
    counter.textContent = `${current + delta} pertanyaan`;
}

function showInlineSuccess(form, msg) {
    showInlineMsg(form, msg, 'success');
}

function showInlineError(form, msg) {
    showInlineMsg(form, msg, 'error');
}

function showInlineMsg(form, msg, type) {
    // Hapus pesan lama
    form.querySelectorAll('.qa-inline-msg').forEach(el => el.remove());

    const el = document.createElement('div');
    el.className = `qa-inline-msg qa-inline-${type}`;
    el.textContent = msg;
    form.appendChild(el);

    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-4px)';
        setTimeout(() => el.remove(), 300);
    }, 3000);
}

function getInitials(name) {
    return name
        .split(' ')
        .slice(0, 2)
        .map(w => w[0] || '')
        .join('')
        .toUpperCase();
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function sendIcon() {
    return `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="22" y1="2" x2="11" y2="13"/>
        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
    </svg>`;
}

function replyIcon() {
    return `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 17 4 12 9 7"/>
        <path d="M20 18v-2a4 4 0 0 0-4-4H4"/>
    </svg>`;
}