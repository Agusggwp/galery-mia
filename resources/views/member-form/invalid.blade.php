<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Formulir Tidak Valid</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b0f19] font-sans antialiased text-white min-h-screen flex items-center justify-center p-4 border-t-8 border-[#f43f5e]">

    <div class="max-w-md w-full brutal-card bg-[#111827] p-8 sm:p-10 text-center space-y-6">
        <div class="w-16 h-16 bg-[#f43f5e] text-white border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black text-3xl mx-auto">
            ⚠️
        </div>

        <h1 class="text-2xl font-black uppercase text-white tracking-tight">LINK TIDAK VALID</h1>

        <p class="text-xs font-bold text-gray-300 bg-[#0b0f19] p-4 border-3 border-black leading-relaxed">
            {{ $reason ?? 'Link pendaftaran ini tidak dapat diakses atau sudah kadaluarsa.' }}
        </p>

        <a href="{{ route('home') }}" class="brutal-btn brutal-btn-primary w-full py-3.5 text-xs font-black">
            &larr; KEMBALI KE BERANDA UTAMA
        </a>
    </div>

</body>
</html>
