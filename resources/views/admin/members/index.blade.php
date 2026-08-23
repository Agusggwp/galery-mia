@extends('layouts.admin')

@section('title', 'Manajemen Anggota Kelas - Admin')
@section('header_title', 'Kelola & Persetujuan Anggota Kelas')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Search Bar -->
    <div class="brutal-card bg-[#111827] p-6 space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Search Form -->
            <form action="{{ route('admin.members.index') }}" method="GET" class="w-full sm:w-80">
                @if($status !== 'all')<input type="hidden" name="status" value="{{ $status }}">@endif
                <input type="text" name="search" value="{{ $search }}" placeholder="CARI NAMA / NIM / KELAS..." class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
            </form>

            <!-- Add Member Modal Trigger -->
            <button onclick="document.getElementById('add-member-modal').classList.remove('hidden')" class="brutal-btn brutal-btn-primary px-5 py-2.5 text-xs font-black w-full sm:w-auto">
                ➕ TAMBAH ANGGOTA MANUAL
            </button>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 pt-3 border-t-3 border-black font-black text-xs uppercase">
            <a href="{{ route('admin.members.index', ['status' => 'all', 'search' => $search]) }}" class="brutal-btn text-xs px-3.5 py-1.5 {{ $status === 'all' ? 'brutal-btn-primary' : 'brutal-btn-slate' }}">
                SEMUA ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.members.index', ['status' => 'pending', 'search' => $search]) }}" class="brutal-btn text-xs px-3.5 py-1.5 {{ $status === 'pending' ? 'brutal-btn-amber' : 'brutal-btn-slate' }}">
                ⏳ MENUNGGU PERSETUJUAN ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.members.index', ['status' => 'approved', 'search' => $search]) }}" class="brutal-btn text-xs px-3.5 py-1.5 {{ $status === 'approved' ? 'brutal-btn-primary' : 'brutal-btn-slate' }}">
                ✓ DISETUJUI ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.members.index', ['status' => 'rejected', 'search' => $search]) }}" class="brutal-btn text-xs px-3.5 py-1.5 {{ $status === 'rejected' ? 'brutal-btn-crimson' : 'brutal-btn-slate' }}">
                ✕ DITOLAK ({{ $counts['rejected'] }})
            </a>
            <a href="{{ route('admin.members.index', ['status' => 'hidden', 'search' => $search]) }}" class="brutal-btn text-xs px-3.5 py-1.5 {{ $status === 'hidden' ? 'brutal-btn-slate border-2 border-white' : 'brutal-btn-slate' }}">
                👁️ DISEMBUYIKAN ({{ $counts['hidden'] }})
            </a>
        </div>
    </div>

    <!-- Members Table -->
    <div class="brutal-card bg-[#111827] p-6">
        <div class="overflow-x-auto border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <table class="w-full text-left text-xs font-bold text-white">
                <thead class="bg-[#6d28d9] uppercase text-[11px] font-black text-white border-b-3 border-black">
                    <tr>
                        <th class="py-4 px-4">Foto & Anggota</th>
                        <th class="py-4 px-4">Kelas & NIM</th>
                        <th class="py-4 px-4">Angkatan</th>
                        <th class="py-4 px-4">Status Approver</th>
                        <th class="py-4 px-4">Visibilitas</th>
                        <th class="py-4 px-4 text-right">Aksi Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y-3 divide-black bg-[#111827]">
                    @forelse($members as $m)
                        <tr class="hover:bg-[#1f2937] transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $m->photo_url }}" alt="{{ $m->name }}" class="w-12 h-12 border-2 border-black object-cover bg-black flex-shrink-0">
                                    <div>
                                        <h4 class="font-black text-sm text-white uppercase">{{ $m->name }}</h4>
                                        <span class="text-[10px] font-black text-[#f59e0b] uppercase">"{{ $m->nickname }}"</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-black text-white uppercase block">{{ $m->class_name }}</span>
                                <span class="text-[10px] font-mono text-gray-400 block">NIM: {{ $m->student_number }}</span>
                            </td>
                            <td class="py-3 px-4 font-black">
                                {{ $m->generation }}
                            </td>
                            <td class="py-3 px-4">
                                @if($m->status === 'approved')
                                    <span class="brutal-badge bg-[#8b5cf6] text-white text-[9px]">✓ APPROVED</span>
                                @elseif($m->status === 'pending')
                                    <span class="brutal-badge bg-[#f59e0b] text-black text-[9px]">⏳ PENDING</span>
                                @else
                                    <span class="brutal-badge bg-[#f43f5e] text-white text-[9px]">✕ REJECTED</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <form action="{{ route('admin.members.toggle', $m->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="brutal-btn text-[10px] px-3 py-1 {{ $m->is_visible ? 'brutal-btn-primary' : 'brutal-btn-crimson' }}">
                                        {{ $m->is_visible ? '✓ TAMPIL' : '✕ SEMBUNYI' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($m->status === 'pending')
                                        <form action="{{ route('admin.members.approve', $m->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="brutal-btn brutal-btn-primary px-2.5 py-1 text-[10px]" title="Setujui">
                                                ✓ APPROVE
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.members.reject', $m->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="brutal-btn brutal-btn-crimson px-2.5 py-1 text-[10px]" title="Tolak">
                                                ✕ REJECT
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Edit Dropdown -->
                                    <details class="group relative inline-block text-left">
                                        <summary class="brutal-btn brutal-btn-slate px-2.5 py-1 text-[10px]">
                                            EDIT
                                        </summary>
                                        <div class="absolute right-0 mt-2 w-80 bg-[#1e1b4b] border-4 border-black p-5 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] z-50 text-left">
                                            <h4 class="font-black text-sm uppercase text-white mb-3">EDIT ANGGOTA</h4>
                                            <form action="{{ route('admin.members.update', $m->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="block text-[10px] font-black uppercase text-white mb-1">NAMA LENGKAP</label>
                                                    <input type="text" name="name" value="{{ $m->name }}" required class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black uppercase text-white mb-1">PANGGILAN</label>
                                                    <input type="text" name="nickname" value="{{ $m->nickname }}" required class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-[10px] font-black uppercase text-white mb-1">NIM/ABSEN</label>
                                                        <input type="text" name="student_number" value="{{ $m->student_number }}" required class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black uppercase text-white mb-1">KELAS</label>
                                                        <input type="text" name="class_name" value="{{ $m->class_name }}" required class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-[10px] font-black uppercase text-white mb-1">JURUSAN</label>
                                                        <input type="text" name="major" value="{{ $m->major }}" required class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black uppercase text-white mb-1">ANGKATAN</label>
                                                        <input type="text" name="generation" value="{{ $m->generation }}" required class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black uppercase text-white mb-1">STATUS</label>
                                                    <select name="status" class="brutal-input w-full px-3 py-1.5 text-xs font-black uppercase bg-[#0b0f19] text-white">
                                                        <option value="pending" {{ $m->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                                                        <option value="approved" {{ $m->status === 'approved' ? 'selected' : '' }}>APPROVED</option>
                                                        <option value="rejected" {{ $m->status === 'rejected' ? 'selected' : '' }}>REJECTED</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="brutal-btn brutal-btn-amber w-full py-2 text-xs">
                                                    SIMPAN PERUBAHAN
                                                </button>
                                            </form>
                                        </div>
                                    </details>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $m->name }} dari anggota?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="brutal-btn brutal-btn-crimson px-2.5 py-1 text-[10px]">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center font-black text-gray-400">TIDAK ADA ANGGOTA DITEMUKAN.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota Manual -->
<div id="add-member-modal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4">
    <div class="brutal-card bg-[#111827] max-w-xl w-full p-6 sm:p-8 relative border-5 border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex items-center justify-between pb-4 border-b-3 border-black mb-6">
            <h3 class="font-black text-xl text-white uppercase">TAMBAH ANGGOTA KELAS MANUAL</h3>
            <button onclick="document.getElementById('add-member-modal').classList.add('hidden')" class="brutal-btn brutal-btn-crimson px-3 py-1 text-xs">
                ✕ TUTUP
            </button>
        </div>

        <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black uppercase text-white mb-1">NAMA LENGKAP *</label>
                <input type="text" name="name" required placeholder="Contoh: I Made Agus" class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">NAMA PANGGILAN *</label>
                    <input type="text" name="nickname" required placeholder="Contoh: Agus" class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">NIM / ABSEN *</label>
                    <input type="text" name="student_number" required placeholder="Contoh: 2315323001" class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">KELAS *</label>
                    <input type="text" name="class_name" value="MI A" required class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">JURUSAN *</label>
                    <input type="text" name="major" value="Manajemen Informatika" required class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">ANGKATAN *</label>
                    <input type="text" name="generation" value="2024" required class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-white mb-1">FOTO PROFIL (OPSIONAL)</label>
                <input type="file" name="photo" accept="image/*" class="brutal-input w-full px-3 py-2 text-xs font-bold bg-[#0b0f19] text-white">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-white mb-1">STATUS ANGGOTA *</label>
                <select name="status" class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
                    <option value="approved">APPROVED (LANGSUNG TAMPIL)</option>
                    <option value="pending">PENDING (MENUNGGU)</option>
                </select>
            </div>

            <button type="submit" class="brutal-btn brutal-btn-primary w-full py-3 text-xs font-black mt-2">
                💾 SIMPAN ANGGOTA
            </button>
        </form>
    </div>
</div>
@endsection
