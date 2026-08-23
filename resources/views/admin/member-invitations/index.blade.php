@extends('layouts.admin')

@section('title', 'Link Formulir Undangan Anggota - Admin')
@section('header_title', 'Kelola Link Undangan Pendaftaran Anggota')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action Card -->
    <div class="brutal-card bg-[#1e1b4b] p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="brutal-badge bg-[#f59e0b] text-black text-xs font-black">SYSTEM INVITATION LINKS</span>
            <h3 class="text-2xl font-black text-white uppercase">LINK FORMULIR PENDAFTARAN ANGGOTA</h3>
            <p class="text-xs font-bold text-gray-300 max-w-xl bg-[#0b0f19] p-3 border-2 border-black">
                Buat link formulir pendaftaran khusus ber-token acak yang dapat dibagikan kepada anggota kelas di WhatsApp/Instagram.
            </p>
        </div>

        <button onclick="document.getElementById('add-invitation-modal').classList.remove('hidden')" class="brutal-btn brutal-btn-primary px-6 py-4 text-xs font-black w-full md:w-auto">
            🔗 BUAT LINK BARU
        </button>
    </div>

    <!-- Toast Notification for Clipboard -->
    <div id="toast-notification" class="hidden fixed bottom-6 right-6 z-50 p-4 bg-[#8b5cf6] text-white border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] font-black text-xs uppercase flex items-center gap-3">
        <span>⚡</span>
        <span id="toast-message">Link berhasil disalin!</span>
    </div>

    <!-- Invitations Table -->
    <div class="brutal-card bg-[#111827] p-6">
        <div class="overflow-x-auto border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <table class="w-full text-left text-xs font-bold text-white">
                <thead class="bg-[#6d28d9] uppercase text-[11px] font-black text-white border-b-3 border-black">
                    <tr>
                        <th class="py-4 px-4">Nama Formulir</th>
                        <th class="py-4 px-4">URL Token Pendaftaran</th>
                        <th class="py-4 px-4">Submissions</th>
                        <th class="py-4 px-4">Status Link</th>
                        <th class="py-4 px-4 text-right">Aksi Bagikan</th>
                    </tr>
                </thead>
                <tbody class="divide-y-3 divide-black bg-[#111827]">
                    @forelse($invitations as $inv)
                        <tr class="hover:bg-[#1f2937] transition-colors">
                            <td class="py-4 px-4">
                                <h4 class="font-black text-sm text-white uppercase">{{ $inv->name }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium">{{ $inv->description ?: 'Tidak ada deskripsi' }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-mono text-xs font-black text-[#f59e0b] bg-[#0b0f19] p-2 border-2 border-black truncate max-w-xs select-all">
                                    {{ $inv->join_url }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-black text-[#8b5cf6] text-sm">{{ $inv->submission_count }}</span>
                                <span class="text-[10px] font-bold text-gray-400">/ {{ $inv->max_submissions ?: '∞' }} Pengisian</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($inv->isValid())
                                    <span class="brutal-badge bg-[#8b5cf6] text-white text-[9px]">✓ AKTIF</span>
                                @else
                                    <span class="brutal-badge bg-[#f43f5e] text-white text-[9px]">✕ NONAKTIF / KADALUARSA</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Copy Link Button -->
                                    <button onclick="copyToClipboard('{{ $inv->join_url }}')" class="brutal-btn brutal-btn-amber px-3 py-1.5 text-[10px]" title="Salin Link">
                                        📋 SALIN LINK
                                    </button>

                                    <!-- Open Form Button -->
                                    <a href="{{ $inv->join_url }}" target="_blank" class="brutal-btn brutal-btn-primary px-3 py-1.5 text-[10px]" title="Buka Formulir">
                                        🚀 BUKA
                                    </a>

                                    <!-- Web Share Button (If Supported) -->
                                    <button onclick="shareLink('{{ $inv->name }}', '{{ $inv->join_url }}')" class="brutal-btn brutal-btn-blue px-3 py-1.5 text-[10px]" title="Share Link">
                                        📲 SHARE
                                    </button>

                                    <!-- Toggle Button -->
                                    <form action="{{ route('admin.member-invitations.toggle', $inv->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="brutal-btn text-[10px] px-3 py-1.5 {{ $inv->is_active ? 'brutal-btn-slate' : 'brutal-btn-primary' }}">
                                            {{ $inv->is_active ? 'NONAKTIFKAN' : 'AKTIFKAN' }}
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.member-invitations.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Hapus link undangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="brutal-btn brutal-btn-crimson px-2.5 py-1.5 text-[10px]">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center font-black text-gray-400">BELUM ADA LINK FORMULIR DIBUAT.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $invitations->links() }}
        </div>
    </div>
</div>

<!-- Modal Buat Link Baru -->
<div id="add-invitation-modal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4">
    <div class="brutal-card bg-[#111827] max-w-lg w-full p-6 sm:p-8 relative border-5 border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex items-center justify-between pb-4 border-b-3 border-black mb-6">
            <h3 class="font-black text-xl text-white uppercase">BUAT LINK FORMULIR BARU</h3>
            <button onclick="document.getElementById('add-invitation-modal').classList.add('hidden')" class="brutal-btn brutal-btn-crimson px-3 py-1 text-xs">
                ✕ TUTUP
            </button>
        </div>

        <form action="{{ route('admin.member-invitations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black uppercase text-white mb-1">NAMA FORMULIR UNTUK IDENTIFIKASI *</label>
                <input type="text" name="name" required placeholder="Contoh: Form Pendaftaran D3 MI 2026" class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-white mb-1">DESKRIPSI FORMULIR (OPSIONAL)</label>
                <textarea name="description" rows="2" placeholder="Catatan atau petunjuk pengisian bagi anggota..." class="brutal-input w-full px-3 py-2 text-xs font-bold bg-[#0b0f19] text-white"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">BATAS MAKSIMAL SUBMISSION</label>
                    <input type="number" name="max_submissions" min="1" placeholder="Kosongkan jika unlimted" class="brutal-input w-full px-3 py-2 text-xs font-black bg-[#0b0f19] text-white">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-1">TANGGAL KADALUARSA</label>
                    <input type="datetime-local" name="expires_at" class="brutal-input w-full px-3 py-2 text-xs font-bold bg-[#0b0f19] text-white">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" value="1" id="is_act" checked class="w-4 h-4 border-2 border-black text-[#8b5cf6]">
                <label for="is_act" class="text-xs font-black uppercase text-white">LANGSUNG AKTIFKAN LINK</label>
            </div>

            <button type="submit" class="brutal-btn brutal-btn-primary w-full py-3 text-xs font-black mt-3">
                🔗 GENERATE LINK UNTUK DIBAGIKAN
            </button>
        </form>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Link berhasil disalin!');
            }).catch(err => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    function fallbackCopy(text) {
        const input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        showToast('Link berhasil disalin!');
    }

    function showToast(msg) {
        const toast = document.getElementById('toast-notification');
        const toastMsg = document.getElementById('toast-message');
        if (toast && toastMsg) {
            toastMsg.innerText = msg;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    }

    function shareLink(title, url) {
        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Formulir Pendaftaran Anggota Kelas D3 MI PNB',
                url: url
            }).catch(err => console.log('Share canceled'));
        } else {
            copyToClipboard(url);
        }
    }
</script>
@endsection
