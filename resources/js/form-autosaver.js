/**
 * Universal Form Auto-Saver & File Draft Restorer for SIPRUKA
 */
export function initFormAutoSaver() {
    setupAutoDraftForms();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupAutoDraftForms);
    }
}

function setupAutoDraftForms() {
    const forms = document.querySelectorAll('form[data-auto-draft]');

    forms.forEach(form => {
        const formIdentifier = form.getAttribute('data-auto-draft') || window.location.pathname;
        const draftKey = 'sipruka_draft_' + formIdentifier;
        const filesKey = 'sipruka_files_' + formIdentifier;

        if (form.getAttribute('data-draft-initialized') === 'true') return;
        form.setAttribute('data-draft-initialized', 'true');

        // 1. Try Restoring Existing Text & File Drafts
        const restoredTextCount = restoreTextDraft(form, draftKey);
        const restoredFileCount = restoreFilesDraft(form, filesKey);

        if (restoredTextCount > 0 || restoredFileCount > 0) {
            showDraftRestoredBanner(form, draftKey, filesKey);
        }

        // 2. Listen to Input & File Changes
        const handleInputChange = (e) => {
            const target = e.target;
            if (!target || target.type === 'password' || target.name === '_token') return;

            if (target.type === 'file') {
                handleFileInputChange(target, filesKey);
            } else {
                saveTextDraft(form, draftKey);
            }
        };

        form.addEventListener('input', handleInputChange);
        form.addEventListener('change', handleInputChange);

        // 3. Clear Drafts on Successful Form Submit
        form.addEventListener('submit', () => {
            localStorage.removeItem(draftKey);
            localStorage.removeItem(filesKey);
        });
    });
}

function saveTextDraft(form, draftKey) {
    try {
        const draftData = {};
        const elements = form.querySelectorAll('input, select, textarea');

        elements.forEach(el => {
            if (!el.name || el.type === 'password' || el.type === 'file' || el.name === '_token' || el.name === '_method') return;

            if (el.type === 'checkbox' || el.type === 'radio') {
                if (el.checked) {
                    draftData[el.name] = el.value;
                }
            } else {
                if (el.value !== undefined && el.value !== '') {
                    draftData[el.name] = el.value;
                }
            }
        });

        if (Object.keys(draftData).length > 0) {
            localStorage.setItem(draftKey, JSON.stringify(draftData));
        } else {
            localStorage.removeItem(draftKey);
        }
    } catch (e) {
        console.warn('AutoDraft save error:', e);
    }
}

function handleFileInputChange(inputEl, filesKey) {
    try {
        let storedFiles = {};
        const raw = localStorage.getItem(filesKey);
        if (raw) {
            storedFiles = JSON.parse(raw) || {};
        }

        if (inputEl.files && inputEl.files[0]) {
            const file = inputEl.files[0];
            if (file.size <= 5 * 1024 * 1024) { // Max 5MB file cache
                const reader = new FileReader();
                reader.onload = function(e) {
                    storedFiles[inputEl.name] = {
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        data: e.target.result
                    };
                    localStorage.setItem(filesKey, JSON.stringify(storedFiles));
                    renderFileRestoredBadge(inputEl, file.name, file.size);
                };
                reader.readAsDataURL(file);
            }
        } else {
            delete storedFiles[inputEl.name];
            localStorage.setItem(filesKey, JSON.stringify(storedFiles));
        }
    } catch (e) {
        console.warn('File save error:', e);
    }
}

function restoreTextDraft(form, draftKey) {
    let restoredFields = 0;
    try {
        const raw = localStorage.getItem(draftKey);
        if (!raw) return 0;

        const draftData = JSON.parse(raw);
        if (!draftData || typeof draftData !== 'object') return 0;

        Object.keys(draftData).forEach(fieldName => {
            const val = draftData[fieldName];
            const fields = form.querySelectorAll(`[name="${fieldName}"]`);

            fields.forEach(el => {
                if (el.type === 'checkbox' || el.type === 'radio') {
                    if (el.value === val) {
                        el.checked = true;
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        restoredFields++;
                    }
                } else {
                    if (!el.value || el.value.trim() === '') {
                        el.value = val;
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        restoredFields++;
                    }
                }
            });
        });
    } catch (e) {
        console.warn('AutoDraft restore error:', e);
    }
    return restoredFields;
}

function restoreFilesDraft(form, filesKey) {
    let restoredCount = 0;
    try {
        const raw = localStorage.getItem(filesKey);
        if (!raw) return 0;

        const storedFiles = JSON.parse(raw);
        if (!storedFiles || typeof storedFiles !== 'object') return 0;

        Object.keys(storedFiles).forEach(fieldName => {
            const fileData = storedFiles[fieldName];
            const fileInput = form.querySelector(`input[type="file"][name="${fieldName}"]`);

            if (fileInput && fileData && fileData.data) {
                try {
                    const fileObj = dataURLtoFile(fileData.data, fileData.name);
                    const container = new DataTransfer();
                    container.items.add(fileObj);
                    fileInput.files = container.files;

                    renderFileRestoredBadge(fileInput, fileData.name, fileData.size);
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    restoredCount++;
                } catch (e) {
                    console.warn('Failed to reconstruct file', e);
                }
            }
        });
    } catch (e) {
        console.warn('File restore error:', e);
    }
    return restoredCount;
}

function dataURLtoFile(dataurl, filename) {
    let arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, { type: mime });
}

function renderFileRestoredBadge(inputEl, fileName, fileSize) {
    let parent = inputEl.parentElement;
    let existingBadge = parent.querySelector('.file-restored-badge');
    if (existingBadge) existingBadge.remove();

    const sizeFormatted = fileSize ? (fileSize / 1024 > 1024 ? (fileSize / (1024 * 1024)).toFixed(1) + ' MB' : Math.round(fileSize / 1024) + ' KB') : '';

    const badge = document.createElement('div');
    badge.className = 'file-restored-badge mt-2 px-3 py-1.5 rounded-lg bg-[#00E9FF]/10 border border-[#00E9FF]/50 text-[#00E9FF] text-xs font-bold flex items-center justify-between shadow-[0_0_10px_rgba(0,233,255,0.2)] animate-fade-in-up';
    badge.innerHTML = `
        <span class="flex items-center gap-1.5 truncate">
            <svg class="w-4 h-4 shrink-0 text-[#00E9FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94a3 3 0 114.243 4.243L8.567 18.312a1.5 1.5 0 11-2.122-2.122l8.88-8.88"/></svg>
            <span class="truncate">File Pulih: <strong>${fileName}</strong> ${sizeFormatted ? '(' + sizeFormatted + ')' : ''}</span>
        </span>
        <span class="text-[10px] bg-[#00E9FF]/20 px-2 py-0.5 rounded text-white font-mono uppercase ml-2 shrink-0">Tersimpan</span>
    `;

    parent.appendChild(badge);
}

function showDraftRestoredBanner(form, draftKey, filesKey) {
    if (form.querySelector('.draft-restored-banner')) return;

    const banner = document.createElement('div');
    banner.className = 'draft-restored-banner mb-6 p-4 rounded-xl bg-[#004B87]/30 border border-[#00E9FF]/60 text-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-[0_0_20px_rgba(0,209,255,0.25)]';
    banner.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-[#00E9FF]/20 text-[#00E9FF] border border-[#00E9FF]/40 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
            </div>
            <div>
                <h5 class="text-xs font-extrabold text-[#00E9FF] uppercase tracking-wider">💾 DRAFT OTOMATIS DIPULIHKAN</h5>
                <p class="text-xs text-slate-200 mt-0.5">Formulir & Berkas yang Anda unggah sebelumnya telah dipulihkan secara otomatis.</p>
            </div>
        </div>
        <button type="button" class="clear-draft-btn shrink-0 text-xs font-bold px-3.5 py-1.5 rounded-lg bg-red-500/20 hover:bg-red-500/40 text-red-300 border border-red-500/40 transition-colors cursor-pointer">
            Kosongkan Form
        </button>
    `;

    form.prepend(banner);

    const clearBtn = banner.querySelector('.clear-draft-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            localStorage.removeItem(draftKey);
            localStorage.removeItem(filesKey);
            banner.remove();
            form.querySelectorAll('.file-restored-badge').forEach(b => b.remove());
            form.reset();
            form.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.type !== 'submit' && el.type !== 'hidden' && el.type !== 'button') {
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        el.checked = false;
                    } else {
                        el.value = '';
                    }
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    }
}

// Auto init on load
initFormAutoSaver();
