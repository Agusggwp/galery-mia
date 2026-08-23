<!-- Lightbox Modal (Cyber Dark Neo-Brutalist with Loading Indicator) -->
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center p-4 sm:p-6 transition-opacity duration-200">
    
    <!-- Close Button -->
    <button onclick="closeLightbox()" class="absolute top-5 right-5 z-50 brutal-btn brutal-btn-crimson p-3" title="Tutup (Esc)">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    <!-- Navigation Prev Button -->
    <button id="lb-prev-btn" onclick="navigateLightbox(-1)" class="absolute left-5 z-50 brutal-btn brutal-btn-primary p-3 hidden sm:flex" title="Media Sebelumnya">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M15 19l-7-7 7-7"/></svg>
    </button>

    <!-- Navigation Next Button -->
    <button id="lb-next-btn" onclick="navigateLightbox(1)" class="absolute right-5 z-50 brutal-btn brutal-btn-primary p-3 hidden sm:flex" title="Media Selanjutnya">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Modal Content Box -->
    <div class="relative w-full max-w-5xl max-h-[90vh] flex flex-col items-center justify-center overflow-hidden bg-[#111827] border-5 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
        
        <!-- Media Container -->
        <div id="lb-media-container" class="relative w-full min-h-[50vh] max-h-[72vh] flex items-center justify-center bg-[#0b0f19] p-3 border-b-4 border-black overflow-hidden">
            
            <!-- Loading Indicator Spinner Overlay -->
            <div id="lb-loader" class="absolute inset-0 flex items-center justify-center bg-[#0b0f19]/90 z-20">
                <div class="brutal-card bg-[#8b5cf6] text-white px-6 py-4 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] flex items-center gap-3 font-black text-sm uppercase">
                    <svg class="w-6 h-6 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>⚡ MEMUAT MEDIA RESOLUSI TINGGI...</span>
                </div>
            </div>

            <div id="lb-content-box" class="flex items-center justify-center w-full h-full"></div>
        </div>

        <!-- Info Bar -->
        <div class="w-full bg-[#6d28d9] border-t-3 border-black p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-white">
            <div>
                <h3 id="lb-title" class="font-black text-lg sm:text-xl uppercase tracking-tight text-white truncate max-w-xl"></h3>
                <span id="lb-album" class="brutal-badge bg-[#f59e0b] text-black text-xs font-black mt-1"></span>
            </div>
            <div class="flex items-center gap-3">
                <a id="lb-drive-link" href="#" target="_blank" class="brutal-btn brutal-btn-amber px-4 py-2 text-xs">
                    ⚡ Buka Google Drive
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let lightboxItems = [];
    let currentIndex = 0;

    function openLightbox(mediaList, index) {
        lightboxItems = mediaList;
        currentIndex = index;
        updateLightboxContent();
        const modal = document.getElementById('lightbox-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const modal = document.getElementById('lightbox-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('lb-content-box').innerHTML = '';
    }

    function navigateLightbox(direction) {
        if (!lightboxItems.length) return;
        currentIndex = (currentIndex + direction + lightboxItems.length) % lightboxItems.length;
        updateLightboxContent();
    }

    function updateLightboxContent() {
        const item = lightboxItems[currentIndex];
        if (!item) return;

        document.getElementById('lb-title').innerText = item.name || 'Dokumentasi Media';
        document.getElementById('lb-album').innerText = 'ALBUM: ' + (item.album_name || 'UMUM');
        document.getElementById('lb-drive-link').href = item.drive_url || '#';

        const loader = document.getElementById('lb-loader');
        const contentBox = document.getElementById('lb-content-box');
        
        // Show loader while new media is being loaded
        if (loader) loader.classList.remove('hidden');
        contentBox.innerHTML = '';

        if (item.type === 'video') {
            let embedUrl = `https://drive.google.com/file/d/${item.google_drive_id}/preview`;
            contentBox.innerHTML = `
                <div class="w-full h-[65vh] max-w-4xl border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-black">
                    <iframe src="${embedUrl}" onload="hideLbLoader()" class="w-full h-full border-0" allow="autoplay" allowfullscreen></iframe>
                </div>
            `;
            // Fallback hide loader after 1.5s for video iframes
            setTimeout(hideLbLoader, 1500);
        } else {
            let imgSrc = item.drive_url || item.thumbnail_url;
            let driveId = item.google_drive_id || '';
            let iframeUrl = `https://drive.google.com/file/d/${driveId}/preview`;
            let thumbUrl = `/media/${item.id}/thumbnail`;

            contentBox.innerHTML = `
                <img src="${imgSrc}" alt="${item.name}" 
                     onload="hideLbLoader()"
                     onerror="if (this.dataset.triedThumb !== 'true') { this.dataset.triedThumb = 'true'; this.src='${thumbUrl}'; } else { hideLbLoader(); this.parentElement.innerHTML = '<div class=\\'w-full h-[65vh] max-w-4xl border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-black\\'><iframe src=\\'${iframeUrl}\\' onload=\\'hideLbLoader()\\' class=\\'w-full h-full border-0\\'></iframe></div>'; }" 
                     class="max-h-[68vh] max-w-full object-contain border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] bg-black">
            `;
        }
    }

    function hideLbLoader() {
        const loader = document.getElementById('lb-loader');
        if (loader) loader.classList.add('hidden');
    }

    // Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightbox-modal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') navigateLightbox(-1);
            if (e.key === 'ArrowRight') navigateLightbox(1);
        }
    });

    document.getElementById('lightbox-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });
</script>
