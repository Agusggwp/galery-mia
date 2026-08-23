<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Anggota - {{ $invitation->name }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b0f19] font-sans antialiased text-white min-h-screen py-12 px-4 border-t-8 border-[#8b5cf6]">

    <div class="max-w-2xl mx-auto space-y-8">
        
        <!-- Header Banner Card -->
        <div class="brutal-card bg-[#1e1b4b] p-8 text-center space-y-3">
            <div class="w-16 h-16 bg-[#8b5cf6] text-white border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black text-3xl mx-auto">
                MI
            </div>
            <span class="brutal-badge bg-[#f59e0b] text-black text-xs">FORMULIR PENDAFTARAN ANGGOTA KELAS</span>
            <h1 class="text-3xl sm:text-4xl font-black uppercase text-white tracking-tight">
                {{ $invitation->name }}
            </h1>
            @if($invitation->description)
                <p class="text-xs font-bold text-gray-300 bg-[#0b0f19] p-3 border-2 border-black">
                    {{ $invitation->description }}
                </p>
            @endif
        </div>

        <!-- Form Card -->
        <div class="brutal-card bg-[#111827] p-8 sm:p-10 space-y-6">
            
            @if($errors->any())
                <div class="p-4 bg-[#f43f5e] text-white border-4 border-black font-black text-xs uppercase space-y-1">
                    <div class="font-black text-sm">⚠️ TERJADI KESALAHAN PENGISIAN:</div>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('member.join.store', $invitation->token) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- DATA WAJIB SECTION -->
                <div class="pb-3 border-b-3 border-black">
                    <h3 class="text-lg font-black uppercase text-[#8b5cf6]">📌 DATA WAJIB ANGGOTA</h3>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-black uppercase text-white mb-2">NAMA LENGKAP *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: I Made Agus Wirawan" class="brutal-input w-full px-4 py-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                </div>

                <!-- Nama Panggilan & NIM/Absen -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nickname" class="block text-xs font-black uppercase text-white mb-2">NAMA PANGGILAN *</label>
                        <input type="text" name="nickname" id="nickname" value="{{ old('nickname') }}" required placeholder="Contoh: Agus" class="brutal-input w-full px-4 py-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                    </div>

                    <div>
                        <label for="student_number" class="block text-xs font-black uppercase text-white mb-2">NIM / NOMOR ABSEN *</label>
                        <input type="text" name="student_number" id="student_number" value="{{ old('student_number') }}" required placeholder="Contoh: 2315323001" class="brutal-input w-full px-4 py-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                    </div>
                </div>

                <!-- Kelas, Jurusan, Angkatan -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="class_name" class="block text-xs font-black uppercase text-white mb-2">KELAS *</label>
                        <input type="text" name="class_name" id="class_name" value="{{ old('class_name', 'MI A') }}" required placeholder="Contoh: MI 3B" class="brutal-input w-full px-4 py-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                    </div>

                    <div>
                        <label for="major" class="block text-xs font-black uppercase text-white mb-2">JURUSAN *</label>
                        <input type="text" name="major" id="major" value="{{ old('major', 'Manajemen Informatika') }}" required placeholder="Contoh: Manajemen Informatika" class="brutal-input w-full px-4 py-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                    </div>

                    <div>
                        <label for="generation" class="block text-xs font-black uppercase text-white mb-2">ANGKATAN *</label>
                        <input type="text" name="generation" id="generation" value="{{ old('generation', '2024') }}" required placeholder="Contoh: 2024" class="brutal-input w-full px-4 py-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                    </div>
                </div>

                <!-- Upload Foto Profil -->
                <div>
                    <label for="photo" class="block text-xs font-black uppercase text-white mb-2">UPLOAD FOTO PROFIL (JPG/PNG/WEBP, MAX 5MB) *</label>
                    <input type="file" name="photo" id="photo" accept="image/*" required class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white file:mr-4 file:py-1 file:px-3 file:border-2 file:border-black file:bg-[#8b5cf6] file:text-white file:font-black">
                </div>

                <!-- DATA OPSIONAL SECTION -->
                <div class="pt-4 pb-3 border-b-3 border-black">
                    <h3 class="text-lg font-black uppercase text-[#f59e0b]">✨ DATA OPSIONAL & SOSIAL MEDIA</h3>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-xs font-black uppercase text-white mb-2">BIO / DESKRIPSI SINGKAT</label>
                    <textarea name="bio" id="bio" rows="3" placeholder="Tuliskan motto hidup atau deskripsi singkat diri Anda..." class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white">{{ old('bio') }}</textarea>
                </div>

                <!-- Instagram & WhatsApp -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="instagram" class="block text-xs font-black uppercase text-white mb-2">USERNAME INSTAGRAM</label>
                        <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}" placeholder="@username" class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white">
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-xs font-black uppercase text-white mb-2">NOMOR WHATSAPP</label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" placeholder="081234567890" class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white">
                    </div>
                </div>

                <!-- Privacy Agreement -->
                <div class="p-4 bg-[#0b0f19] border-3 border-black space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="privacy_agreed" value="1" required {{ old('privacy_agreed') ? 'checked' : '' }} class="w-5 h-5 mt-0.5 border-2 border-black text-[#8b5cf6]">
                        <span class="text-xs font-bold text-gray-200 leading-relaxed">
                            Saya menyetujui data yang saya isi digunakan untuk ditampilkan pada website dokumentasi kelas sesuai dengan ketentuan privasi yang berlaku.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="brutal-btn brutal-btn-primary w-full py-4 text-sm font-black">
                    🚀 KIRIM FORMULIR PENDAFTARAN
                </button>
            </form>
        </div>

    </div>

</body>
</html>
