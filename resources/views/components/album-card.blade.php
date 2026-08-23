@props(['album'])

<a href="{{ route('album.show', $album->slug) }}" class="brutal-card block group overflow-hidden bg-[#111827]">
    <div class="relative h-60 border-b-4 border-black bg-[#0b0f19] overflow-hidden">
        <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        
        <!-- Counter Badge -->
        <div class="absolute bottom-3 left-3 brutal-badge bg-[#f59e0b] text-black text-xs font-black">
            {{ $album->photos_count ?? $album->media_count ?? 0 }} FOTO • {{ $album->videos_count ?? 0 }} VIDEO
        </div>
    </div>

    <div class="p-6 bg-[#111827]">
        <h3 class="font-black text-xl text-white uppercase group-hover:text-[#8b5cf6] transition-colors line-clamp-1">
            {{ $album->name }}
        </h3>
        <p class="text-xs font-bold text-gray-400 mt-2 line-clamp-2 leading-relaxed">
            {{ $album->description ?: 'Dokumentasi foto dan video kegiatan kelas.' }}
        </p>
    </div>
</a>
