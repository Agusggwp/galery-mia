<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Gallery Kelas</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b0f19] min-h-screen flex items-center justify-center p-4 font-sans text-white border-t-8 border-[#8b5cf6]">

    <div class="w-full max-w-md bg-[#111827] border-5 border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] p-8 sm:p-10 relative">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#8b5cf6] text-white border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black text-3xl mx-auto mb-3">
                MI
            </div>
            <h1 class="text-3xl font-black uppercase text-white tracking-tight">ADMIN LOGIN</h1>
            <p class="text-xs font-bold text-[#f59e0b] mt-1 uppercase">D3 MANAJEMEN INFORMATIKA PNB</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-[#f43f5e] text-white border-3 border-black font-black text-xs uppercase">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-black uppercase text-white mb-2">EMAIL ADMIN</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="ADMIN@GALLERY.COM" class="brutal-input w-full px-4 py-3 text-xs uppercase font-black bg-[#0b0f19] text-white">
            </div>

            <div>
                <label for="password" class="block text-xs font-black uppercase text-white mb-2">PASSWORD</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" class="brutal-input w-full px-4 py-3 text-xs font-black bg-[#0b0f19] text-white">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer font-black text-xs uppercase text-gray-300">
                    <input type="checkbox" name="remember" class="w-4 h-4 border-2 border-black text-[#8b5cf6]">
                    <span>INGAT SAYA</span>
                </label>
            </div>

            <button type="submit" class="brutal-btn brutal-btn-primary w-full py-4 text-sm font-black">
                🚀 MASUK KE ADMIN PANEL
            </button>
        </form>

        <div class="mt-8 text-center border-t-3 border-black pt-4">
            <a href="{{ route('home') }}" class="text-xs font-black text-gray-300 hover:text-[#8b5cf6] uppercase">
                &larr; KEMBALI KE WEBSITE PUBLIK
            </a>
        </div>
    </div>

</body>
</html>
