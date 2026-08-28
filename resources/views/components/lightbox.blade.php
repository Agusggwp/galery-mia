<!-- Lightbox Modal (Cyber Dark Neo-Brutalist Mobile Responsive with Touch Gestures) -->
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black/95 backdrop-blur-md flex flex-col items-center justify-center p-2 sm:p-4 md:p-6 transition-opacity duration-200 select-none">
    
    <!-- Top Bar: Counter & Close Button -->
    <div class="absolute top-3 inset-x-3 sm:top-5 sm:inset-x-5 z-50 flex items-center justify-between pointer-events-none">
        <div class="flex items-center gap-2 pointer-events-auto">
            <span id="lb-counter" class="brutal-badge bg-[#f59e0b] text-black text-xs font-black px-3 py-1 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                MEDIA 1 / 1
            </span>
            <span class="hidden md:inline-flex brutal-badge bg-[#3b82f6] text-white text-[10px]">
                💡 SWIPE ATAL TEKAN &larr; &rarr; UNTUK NAVIGASI
            </span>
        </div>

        <!-- Close Button -->
        <button onclick="closeLightbox()" class="pointer-events-auto brutal-btn brutal-btn-crimson p-2.5 sm:p-3 active:scale-95 transition-transform" title="Tutup (Esc)" aria-label="Tutup Media Viewer">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Floating Navigation Prev Button (Mobile & Desktop) -->
    <button id="lb-prev-btn" onclick="navigateLightbox(-1)" class="absolute left-2 sm:left-5 top-1/2 -translate-y-1/2 z-40 brutal-btn brutal-btn-primary p-2.5 sm:p-3.5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:scale-90 transition-transform" title="Media Sebelumnya" aria-label="Media Sebelumnya">
        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M15 19l-7-7 7-7"/></svg>
    </button>

    <!-- Floating Navigation Next Button (Mobile & Desktop) -->
    <button id="lb-next-btn" onclick="navigateLightbox(1)" class="absolute right-2 sm:right-5 top-1/2 -translate-y-1/2 z-40 brutal-btn brutal-btn-primary p-2.5 sm:p-3.5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:scale-90 transition-transform" title="Media Selanjutnya" aria-label="Media Selanjutnya">
        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Modal Content Box -->
    <div class="relative w-full max-w-5xl max-h-[88vh] sm:max-h-[90vh] flex flex-col items-center justify-center overflow-hidden bg-[#111827] border-4 sm:border-5 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] sm:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] mt-10 sm:mt-0">
        
        <!-- Media Container (Supports Touch Swiping) -->
        <div id="lb-media-container" class="relative w-full min-h-[45vh] max-h-[62vh] sm:max-h-[72vh] flex items-center justify-center bg-[#0b0f19] p-2 sm:p-4 border-b-3 sm:border-b-4 border-black overflow-hidden touch-pan-y">
            
            <!-- Visual Swipe Indicator Feedback overlay -->
            <div id="lb-swipe-indicator" class="absolute inset-0 z-30 pointer-events-none opacity-0 transition-opacity duration-150 flex items-center justify-center">
                <div id="lb-swipe-badge" class="brutal-badge text-white px-5 py-3 text-sm font-black uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]"></div>
            </div>

            <!-- Loading Indicator Spinner Overlay -->
            <div id="lb-loader" class="absolute inset-0 flex items-center justify-center bg-[#0b0f19]/90 z-20">
                <div class="brutal-card bg-[#8b5cf6] text-white px-4 py-3 sm:px-6 sm:py-4 border-3 sm:border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] sm:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] flex items-center gap-3 font-black text-xs sm:text-sm uppercase">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>⚡ MEMUAT MEDIA...</span>
                </div>
            </div>

            <div id="lb-content-box" class="flex items-center justify-center w-full h-full"></div>
        </div>

        <!-- Info & Action Bar -->
        <div class="w-full bg-[#6d28d9] border-t-3 border-black p-3 sm:p-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-white">
            <div class="min-w-0 flex-1">
                <h3 id="lb-title" class="font-black text-base sm:text-lg md:text-xl uppercase tracking-tight text-white truncate"></h3>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    <span id="lb-album" class="brutal-badge bg-[#f59e0b] text-black text-[10px] sm:text-xs font-black"></span>
                    <span id="lb-type-badge" class="brutal-badge bg-[#8b5cf6] text-white text-[10px] sm:text-xs font-black"></span>
                </div>
            </div>

            <!-- Action Controls Bar -->
            <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t border-black/30 sm:border-t-0">
                <!-- Mobile Prev/Next Control Group for Quick Tapping -->
                <div class="flex sm:hidden items-center gap-1.5">
                    <button onclick="navigateLightbox(-1)" class="brutal-btn brutal-btn-primary px-3 py-1.5 text-xs">
                        ◀ PREV
                    </button>
                    <button onclick="navigateLightbox(1)" class="brutal-btn brutal-btn-primary px-3 py-1.5 text-xs">
                        NEXT ▶
                    </button>
                </div>

                <a id="lb-drive-link" href="#" target="_blank" class="brutal-btn brutal-btn-amber px-3.5 py-1.5 sm:px-4 sm:py-2 text-xs text-center flex-1 sm:flex-none">
                    ⚡ Buka Drive
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let lightboxItems = [];
    let currentIndex = 0;

    function openLightbox(mediaList, index) {
        lightboxItems = mediaList || [];
        currentIndex = index || 0;
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
        document.getElementById('lb-counter').innerText = `MEDIA ${currentIndex + 1} / ${lightboxItems.length}`;
        document.getElementById('lb-drive-link').href = item.drive_url || '#';

        const typeBadge = document.getElementById('lb-type-badge');
        if (typeBadge) {
            typeBadge.innerText = item.type === 'video' ? '🎬 VIDEO' : '📷 FOTO';
            typeBadge.className = item.type === 'video' 
                ? 'brutal-badge bg-[#f43f5e] text-white text-[10px] sm:text-xs font-black' 
                : 'brutal-badge bg-[#8b5cf6] text-white text-[10px] sm:text-xs font-black';
        }

        const loader = document.getElementById('lb-loader');
        const contentBox = document.getElementById('lb-content-box');
        
        // Show loader while media loads
        if (loader) loader.classList.remove('hidden');
        contentBox.innerHTML = '';

        if (item.type === 'video') {
            let embedUrl = `https://drive.google.com/file/d/${item.google_drive_id}/preview`;
            contentBox.innerHTML = `
                <div class="relative w-full max-w-4xl aspect-video max-h-[58vh] sm:max-h-[68vh] border-3 sm:border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-black overflow-hidden mx-auto flex items-center justify-center">
                    <iframe src="${embedUrl}" onload="hideLbLoader()" class="w-full h-full border-0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                </div>
            `;
            setTimeout(hideLbLoader, 1500);
        } else {
            let imgSrc = item.drive_url || item.thumbnail_url;
            let driveId = item.google_drive_id || '';
            let iframeUrl = `https://drive.google.com/file/d/${driveId}/preview`;
            let thumbUrl = `/media/${item.id}/thumbnail`;

            contentBox.innerHTML = `
                <img src="${imgSrc}" alt="${item.name}" 
                     onload="hideLbLoader()"
                     onerror="if (this.dataset.triedThumb !== 'true') { this.dataset.triedThumb = 'true'; this.src='${thumbUrl}'; } else { hideLbLoader(); this.parentElement.innerHTML = '<div class=\\'relative w-full max-w-4xl aspect-video max-h-[58vh] sm:max-h-[68vh] border-3 sm:border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-black overflow-hidden mx-auto\\'><iframe src=\\'${iframeUrl}\\' onload=\\'hideLbLoader()\\' class=\\'w-full h-full border-0\\'></iframe></div>'; }" 
                     class="max-h-[58vh] sm:max-h-[68vh] max-w-full object-contain border-3 sm:border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] bg-black select-none">
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

    // Mobile Touch Gesture Support (Swipe Left / Swipe Right / Swipe Down)
    (function initMobileTouchGestures() {
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;
        
        const mediaContainer = document.getElementById('lb-media-container');
        if (!mediaContainer) return;

        mediaContainer.addEventListener('touchstart', function(e) {
            if (e.touches.length > 1) return; // ignore multi-touch pinch
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        mediaContainer.addEventListener('touchend', function(e) {
            if (e.changedTouches.length === 0) return;
            touchEndX = e.changedTouches[0].clientX;
            touchEndY = e.changedTouches[0].clientY;

            handleSwipeGesture();
        }, { passive: true });

        function handleSwipeGesture() {
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;
            const absX = Math.abs(diffX);
            const absY = Math.abs(diffY);

            // Minimum swipe distance threshold (px)
            const minSwipeDist = 45;

            // Horizontal Swipe (Next / Prev)
            if (absX > minSwipeDist && absX > absY * 1.2) {
                if (diffX < 0) {
                    showSwipeFeedback('NEXT ▶', 'bg-[#8b5cf6]');
                    navigateLightbox(1);
                } else {
                    showSwipeFeedback('◀ PREV', 'bg-[#8b5cf6]');
                    navigateLightbox(-1);
                }
            } 
            // Vertical Swipe Down (Dismiss Modal)
            else if (diffY > minSwipeDist * 1.5 && absY > absX * 1.5) {
                showSwipeFeedback('❌ TUTUP', 'bg-[#f43f5e]');
                setTimeout(closeLightbox, 150);
            }
        }

        function showSwipeFeedback(text, bgClass) {
            const indicator = document.getElementById('lb-swipe-indicator');
            const badge = document.getElementById('lb-swipe-badge');
            if (!indicator || !badge) return;

            badge.innerText = text;
            badge.className = `brutal-badge text-white px-5 py-3 text-sm font-black uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] ${bgClass}`;
            indicator.classList.remove('opacity-0');
            indicator.classList.add('opacity-100');

            setTimeout(() => {
                indicator.classList.remove('opacity-100');
                indicator.classList.add('opacity-0');
            }, 300);
        }
    })();
</script>

