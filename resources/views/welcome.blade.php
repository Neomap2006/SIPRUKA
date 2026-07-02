<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPRUKA - Sistem Informasi Perpustakaan & Ruangan</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }
        
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
    </style>
</head>
<body class="antialiased bg-slate-950 text-slate-50 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-grid z-0 opacity-40"></div>
    <div class="absolute top-[20%] left-1/2 -translate-x-1/2 w-[800px] h-[500px] opacity-30 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-500 via-purple-500 to-transparent blur-[100px] rounded-full"></div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b border-slate-800/50 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-xl shadow-lg shadow-indigo-500/30">
                        S
                    </div>
                    <span class="font-bold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-300">SIPRUKA</span>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex gap-4 items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium hover:text-indigo-400 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-medium bg-white text-slate-900 px-5 py-2.5 rounded-full hover:bg-indigo-50 transition-all hover:scale-105 active:scale-95 shadow-md">Daftar</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-80px)] px-4 sm:px-6 lg:px-8 text-center pt-32 pb-24 lg:pt-40">

        
        <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 max-w-4xl mx-auto leading-tight animate-fade-in-up" style="animation-delay: 0.1s; opacity: 0;">
            Kelola Peminjaman <br class="hidden md:block">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">Lebih Mudah & Cepat</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s; opacity: 0;">
            Platform terpadu untuk mengelola peminjaman buku perpustakaan dan ruangan kelas dalam satu sistem yang efisien dan modern.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto animate-fade-in-up" style="animation-delay: 0.3s; opacity: 0;">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-4 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-lg transition-all hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-1">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-lg transition-all hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-1">
                    Mulai Sekarang
                </a>
                <a href="#fitur" class="px-8 py-4 rounded-full bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-semibold text-lg transition-all hover:-translate-y-1">
                    Pelajari Fitur
                </a>
            @endauth
        </div>

        <!-- Features Grid -->
        <div id="fitur" class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto mt-24 md:mt-32 text-left animate-fade-in-up" style="animation-delay: 0.5s; opacity: 0;">
            <!-- Card 1 -->
            <div class="group p-8 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-md hover:bg-slate-800/80 transition-all hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/20">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform group-hover:bg-indigo-500/30">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-slate-100">Peminjaman Buku</h3>
                <p class="text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Cari, pinjam, dan kelola sirkulasi buku perpustakaan dengan mudah. Pantau status peminjaman secara real-time dan akurat.</p>
            </div>
            <!-- Card 2 -->
            <div class="group p-8 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-md hover:bg-slate-800/80 transition-all hover:border-purple-500/50 hover:shadow-2xl hover:shadow-purple-500/20">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform group-hover:bg-purple-500/30">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-slate-100">Peminjaman Ruangan</h3>
                <p class="text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Sistem reservasi ruang kelas yang terorganisir. Cegah bentrok jadwal dan pastikan ketersediaan ruangan untuk kegiatan Anda.</p>
            </div>
        </div>
    </main>

    <footer class="relative z-10 border-t border-slate-800/50 py-8 text-center text-slate-500 text-sm mt-auto bg-slate-950/80 backdrop-blur-sm">
        <p>&copy; {{ date('Y') }} SIPRUKA. Dibuat untuk kenyamanan kampus.</p>
    </footer>
</body>
</html>
