<script>
document.addEventListener('DOMContentLoaded', () => {
    // Global 2MB File Size Guard for all image & file uploads
    const MAX_FILE_SIZE_BYTES = 2 * 1024 * 1024; // 2 MB

    function showFileSizeToast(message) {
        // Remove existing toast if any
        const existingToast = document.getElementById('file-size-guard-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.id = 'file-size-guard-toast';
        toast.className = 'fixed top-5 right-5 z-[99999] max-w-md w-[calc(100%-2.5rem)] bg-rose-600 text-white p-4 rounded-xl border border-rose-700/50 flex items-start gap-3 transition-all duration-300 animate-in fade-in slide-in-from-top-4';
        toast.innerHTML = `
            <span class="material-symbols-outlined text-2xl shrink-0 mt-0.5">warning</span>
            <div class="flex-1 text-xs sm:text-sm font-medium leading-snug">
                <strong class="block font-bold mb-0.5 text-sm sm:text-base">Ukuran File Terlalu Besar!</strong>
                ${message}
            </div>
            <button type="button" class="shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors cursor-pointer" onclick="this.closest('#file-size-guard-toast').remove()">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast && toast.parentElement) {
                toast.classList.add('opacity-0', 'translate-y-[-10px]');
                setTimeout(() => toast.remove(), 300);
            }
        }, 6000);
    }

    document.addEventListener('change', (e) => {
        const input = e.target;
        if (input && input.tagName === 'INPUT' && input.type === 'file') {
            const files = input.files;
            if (!files || files.length === 0) return;

            // Determine max size limit for this input
            let maxLimitBytes = 2 * 1024 * 1024; // Default: 2MB for images and standard uploads
            let limitLabel = '2 MB';

            if (input.dataset.maxSize) {
                maxLimitBytes = parseInt(input.dataset.maxSize, 10);
                limitLabel = (maxLimitBytes / (1024 * 1024)).toFixed(0) + ' MB';
            } else if (input.dataset.maxSize === '0' || input.dataset.noLimit !== undefined) {
                // Explicit no-limit flag
                return;
            } else if (input.name === 'sql_file' || (input.accept && input.accept.includes('.sql'))) {
                // File SQL backup/restore — tidak ada batas (skip check)
                return;
            } else if (input.name === 'file' || (input.accept && (input.accept.includes('zip') || input.accept.includes('rar')))) {
                maxLimitBytes = 100 * 1024 * 1024; // 100MB for digital product delivery archives
                limitLabel = '100 MB';
            } else if (input.name === 'brochure_file' || (input.accept && input.accept.includes('pdf') && !input.accept.includes('image'))) {
                maxLimitBytes = 10 * 1024 * 1024; // 10MB for project PDF brochure
                limitLabel = '10 MB';
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.size > maxLimitBytes) {
                    const actualSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    const fileName = file.name || 'yang dipilih';
                    
                    // Reset input value
                    input.value = '';

                    // Trigger alert toast
                    showFileSizeToast(`File "<strong>${fileName}</strong>" berukuran <strong>${actualSizeMB} MB</strong>, melebihi batas maksimal <strong>${limitLabel}</strong>. Silakan pilih atau kompres file.`);
                    break;
                }
            }
        }
    });
});
</script>
