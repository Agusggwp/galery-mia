@props(['item', 'index', 'mediaList'])

<div onclick='openLightbox({{ json_encode($mediaList->map(fn($m) => [
    "id" => $m->id,
    "name" => $m->name,
    "type" => $m->type,
    "mime_type" => $m->mime_type,
    "thumbnail_url" => route("media.thumbnail", $m->id),
    "drive_url" => route("media.stream", $m->id),
    "google_drive_id" => $m->google_drive_id,
    "album_name" => $m->album->name ?? "Umum"
])) }}, {{ $index }})'
class="brutal-card relative h-56 sm:h-64 cursor-pointer overflow-hidden group bg-black">
    
    <img src="{{ route('media.thumbnail', $item->id) }}" alt="{{ $item->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90 group-hover:opacity-100">

    <!-- Type Indicator Badge -->
    <div class="absolute top-3 left-3 z-10">
        @if($item->type === 'video')
            <span class="brutal-badge bg-[#f43f5e] text-white text-[10px]">
                🎬 VIDEO
            </span>
        @else
            <span class="brutal-badge bg-[#8b5cf6] text-white text-[10px]">
                📷 FOTO
            </span>
        @endif
    </div>

    @if($item->type === 'video')
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-12 h-12 bg-[#f59e0b] text-black border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black group-hover:scale-110 transition-transform">
                ▶
            </div>
        </div>
    @endif

    <div class="absolute inset-x-0 bottom-0 bg-[#111827] border-t-3 border-black p-3 text-white">
        <span class="text-[10px] font-black uppercase text-[#8b5cf6] block truncate">{{ $item->album->name ?? 'Album' }}</span>
        <h4 class="font-black text-xs uppercase truncate">{{ $item->name }}</h4>
    </div>
</div>
