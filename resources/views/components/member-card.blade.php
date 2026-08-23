@props(['member'])

<a href="{{ route('members.show', $member->slug) }}" class="brutal-card block group overflow-hidden bg-[#111827]">
    <!-- Profile Photo Header -->
    <div class="relative h-64 border-b-4 border-black bg-[#0b0f19] overflow-hidden">
        <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        
        <!-- Class Badge -->
        <div class="absolute top-3 left-3 brutal-badge bg-[#8b5cf6] text-white text-[10px] font-black">
            KELAS {{ strtoupper($member->class_name) }}
        </div>

        <!-- NIM / Absen Badge -->
        <div class="absolute bottom-3 right-3 brutal-badge bg-[#f59e0b] text-black text-[10px] font-black">
            {{ $member->student_number }}
        </div>
    </div>

    <!-- Info Footer -->
    <div class="p-5 bg-[#111827]">
        <h3 class="font-black text-lg text-white uppercase group-hover:text-[#8b5cf6] transition-colors truncate">
            {{ $member->name }}
        </h3>
        <div class="text-xs font-black text-[#f59e0b] uppercase block -mt-0.5">
            "{{ $member->nickname }}" • {{ $member->generation }}
        </div>

        <p class="text-xs font-bold text-gray-400 mt-2 line-clamp-2 leading-relaxed">
            {{ $member->bio ?: 'Mahasiswa D3 Manajemen Informatika Politeknik Negeri Bali.' }}
        </p>
    </div>
</a>
