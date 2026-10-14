<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPRUKA')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- Prevent Alpine.js FOUC / Modal Flash -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-slate-100 font-sans antialiased selection:bg-cyan-500 selection:text-black">
    <div class="flex min-h-screen bg-black">
        {{-- Sidebar --}}
        <div class="w-64 bg-black border-r border-[#004B87]/60 shadow-[4px_0_24px_rgba(0,209,255,0.08)]">
            <div class="p-6 flex items-center gap-3 border-b border-[#004B87]/50">
                <x-application-logo class="w-12 h-12" />
                <h2 class="text-xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#00E9FF] to-white">SIPRUKA</h2>
            </div>
            <nav class="flex flex-col p-4 space-y-1.5">
                <a href="{{ route('dashboard') }}" class="hover:bg-[#050c18] hover:text-[#00E9FF] p-3 rounded-xl transition text-slate-300 border border-transparent hover:border-[#004B87]/40">Dashboard</a>
                <a href="{{ route('ruangan.index') }}" class="hover:bg-[#050c18] hover:text-[#00E9FF] p-3 rounded-xl transition text-slate-300 border border-transparent hover:border-[#004B87]/40">Ruangan</a>
                <a href="{{ route('peminjaman-ruangan.index') }}" class="hover:bg-[#050c18] hover:text-[#00E9FF] p-3 rounded-xl transition text-slate-300 border border-transparent hover:border-[#004B87]/40">Peminjaman Ruangan</a>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 p-6 bg-black">
            <nav class="flex justify-end items-center mb-6 pb-4 border-b border-[#004B87]/40">
                <span class="mr-4 text-sm font-semibold text-slate-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#00D1FF] shadow-[0_0_6px_#00D1FF]"></span>
                    {{ auth()->user()->nama }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 px-3.5 py-1.5 rounded-lg text-sm font-semibold transition">Logout</button>
                </form>
            </nav>
            <div class="bg-[#050c18] border border-[#004B87] p-6 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.8)]">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>

