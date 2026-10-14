<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPRUKA - Sistem Peminjaman Ruangan Kampus</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .bg-cyber-grid {
            background-size: 50px 50px;
            background-image: linear-gradient(to right, rgba(0, 209, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0, 75, 135, 0.08) 1px, transparent 1px);
        }
        
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(24px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="antialiased bg-black text-slate-100 relative overflow-x-hidden selection:bg-[#00D1FF] selection:text-black">
    <!-- Background Effects -->
    <div class="fixed inset-0 bg-cyber-grid z-0 pointer-events-none opacity-60"></div>
    <div class="absolute top-[8%] left-1/2 -translate-x-1/2 w-[90vw] max-w-[700px] h-[350px] sm:h-[450px] opacity-25 z-0 pointer-events-none">
        <div class="w-full h-full bg-gradient-to-b from-[#00D1FF] via-[#004B87] to-transparent blur-[100px] sm:blur-[140px] rounded-full"></div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b border-[#004B87]/50 bg-black/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2 sm:gap-3 group cursor-pointer">
                    <x-application-logo class="w-10 h-10 sm:w-12 sm:h-12 transition-transform group-hover:scale-105" />
                    <span class="font-black text-xl sm:text-2xl tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-[#00E9FF] via-[#00D1FF] to-white">SIPRUKA</span>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex gap-2 sm:gap-4 items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs sm:text-sm font-bold text-[#00E9FF] hover:text-white px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl border border-[#004B87] hover:border-[#00D1FF] bg-[#050c18] hover:shadow-[0_0_15px_rgba(0,209,255,0.3)] transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-slate-300 hover:text-[#00E9FF] transition-colors px-2 sm:px-4 py-2">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-xs sm:text-sm font-bold bg-gradient-to-r from-[#004B87] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl transition-all shadow-[0_0_15px_rgba(0,209,255,0.35)] hover:shadow-[0_0_25px_rgba(0,209,255,0.6)] hover:scale-105 border border-[#00D1FF]/40">Daftar</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative z-10 flex flex-col items-center justify-start min-h-[calc(100vh-80px)] px-4 sm:px-6 lg:px-8 text-center pt-6 sm:pt-10 lg:pt-12 pb-16">
        
        <!-- Cyber Pill Badge -->
        <div class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-full bg-[#050c18] border border-[#00D1FF]/50 text-sm sm:text-base font-extrabold text-[#00E9FF] tracking-wide mb-6 shadow-[0_0_18px_rgba(0,209,255,0.35)] animate-fade-in-up">
            <span class="w-2.5 h-2.5 rounded-full bg-[#00D1FF] animate-ping"></span>
            <span>SISTEM PEMINJAMAN RUANGAN KAMPUS MODERN</span>
        </div>
        
        <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight mb-5 max-w-5xl mx-auto leading-[1.15] animate-fade-in-up" style="animation-delay: 0.1s;">
            Reservasi Ruangan Kampus <br class="hidden md:block">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00E9FF] via-[#00D1FF] to-cyan-200">Cepat, Terpadu & Digital</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
            Platform cerdas untuk mengelola jadwal, peminjaman ruangan kelas, verifikasi berkas digital, serta notifikasi otomatis WhatsApp.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto animate-fade-in-up" style="animation-delay: 0.3s;">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white font-bold text-lg transition-all shadow-[0_0_20px_rgba(0,209,255,0.4)] hover:shadow-[0_0_30px_rgba(0,209,255,0.7)] hover:-translate-y-1 border border-[#00D1FF]/50">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white font-bold text-lg transition-all shadow-[0_0_20px_rgba(0,209,255,0.4)] hover:shadow-[0_0_30px_rgba(0,209,255,0.7)] hover:-translate-y-1 border border-[#00D1FF]/50">
                    Mulai Sekarang
                </a>
                <a href="#fitur" class="px-8 py-4 rounded-xl bg-[#050c18] hover:bg-[#0a172c] border border-[#004B87] hover:border-[#00D1FF] text-slate-200 hover:text-[#00E9FF] font-bold text-lg transition-all hover:-translate-y-1 shadow-sm">
                    Pelajari Fitur
                </a>
            @endauth
        </div>

        <!-- Features Grid -->
        <div id="fitur" class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto mt-16 md:mt-24 text-left animate-fade-in-up" style="animation-delay: 0.5s;">
            <!-- Card 1 -->
            <div class="group p-8 rounded-2xl bg-[#050c18] border border-[#004B87]/60 hover:border-[#00D1FF] backdrop-blur-md transition-all duration-300 hover:shadow-[0_0_25px_rgba(0,209,255,0.2)] hover:-translate-y-1">
                <div class="w-14 h-14 rounded-xl bg-[#004B87]/30 border border-[#00D1FF]/40 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#00D1FF]/20 transition-all">
                    <svg class="w-7 h-7 text-[#00E9FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-white group-hover:text-[#00E9FF] transition-colors">Manajemen Ruangan</h3>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Pantau kapasitas, fasilitas, serta ketersediaan ruangan kelas secara real-time untuk mencegah bentrok jadwal.</p>
            </div>

            <!-- Card 2 -->
            <div class="group p-8 rounded-2xl bg-[#050c18] border border-[#004B87]/60 hover:border-[#00D1FF] backdrop-blur-md transition-all duration-300 hover:shadow-[0_0_25px_rgba(0,209,255,0.2)] hover:-translate-y-1">
                <div class="w-14 h-14 rounded-xl bg-[#004B87]/30 border border-[#00D1FF]/40 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#00D1FF]/20 transition-all">
                    <svg class="w-7 h-7 text-[#00E9FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-white group-hover:text-[#00E9FF] transition-colors">Izin Digital Otomatis</h3>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Pengajuan berkas online dengan verifikasi admin bertingkat, lengkap dengan penerbitan surat izin digital.</p>
            </div>

            <!-- Card 3 -->
            <div class="group p-8 rounded-2xl bg-[#050c18] border border-[#004B87]/60 hover:border-[#00D1FF] backdrop-blur-md transition-all duration-300 hover:shadow-[0_0_25px_rgba(0,209,255,0.2)] hover:-translate-y-1">
                <div class="w-14 h-14 rounded-xl bg-[#004B87]/30 border border-[#00D1FF]/40 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#00D1FF]/20 transition-all">
                    <svg class="w-7 h-7 text-[#00E9FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-white group-hover:text-[#00E9FF] transition-colors">Integrasi WhatsApp</h3>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Notifikasi instan via WhatsApp Cloud API saat pengajuan baru dibuat, disetujui, ataupun membutuhkan revisi.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-[#004B87]/40 py-8 text-center text-slate-500 text-sm bg-black">
        <div class="flex items-center justify-center gap-2 mb-2">
            <x-application-logo class="w-6 h-6" />
            <span class="font-bold text-slate-300">SIPRUKA</span>
        </div>
        <p>&copy; {{ date('Y') }} SIPRUKA. Sistem Informasi Peminjaman Ruangan Kampus.</p>
    </footer>
</body>
</html>

