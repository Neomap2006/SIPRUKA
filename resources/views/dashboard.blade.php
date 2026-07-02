<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-950 min-h-screen relative overflow-hidden text-slate-100">
        <!-- Neon decorative background glows -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[130px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
            
            <!-- Greeting Banner -->
            <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl shadow-xl overflow-hidden relative border border-slate-800">
                <!-- Background glow effect -->
                <div class="absolute right-0 top-0 h-full w-1/3 bg-indigo-500/10 blur-[80px] rounded-full pointer-events-none"></div>
                <div class="p-8 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold tracking-tight">Selamat Datang Kembali, {{ auth()->user()->nama }}! 👋</h3>
                        <p class="text-slate-400 text-sm mt-1">
                            Anda masuk sebagai 
                            <span class="bg-indigo-500/30 text-indigo-200 px-3 py-1 rounded-full font-semibold text-xs border border-indigo-500/40 ml-1">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-slate-800/80 backdrop-blur-md px-5 py-2.5 rounded-xl border border-slate-700/50 text-right md:self-center">
                        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Hari Ini</span>
                        <span class="text-sm font-bold text-slate-200 mt-0.5 block">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
                <!-- ADMIN DASHBOARD -->
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Statistik Sistem & Inventaris</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Total Buku -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/15 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Buku</p>
                                <h4 class="text-3xl font-extrabold text-white tracking-tight">{{ \App\Models\Buku::count() }}</h4>
                                <div class="pt-2">
                                    <a href="{{ route('buku.index') }}" class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                        Kelola Data
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Ruangan -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-indigo-500/15 text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Ruangan</p>
                                <h4 class="text-3xl font-extrabold text-white tracking-tight">{{ \App\Models\Ruangan::count() }}</h4>
                                <div class="pt-2">
                                    <a href="{{ route('ruangan.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                                        Kelola Data
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-2">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Pengajuan & Status Aktif</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        <!-- Peminjaman Buku Menunggu -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-amber-500/15 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Buku Pending</p>
                                <h4 class="text-2xl font-extrabold text-white tracking-tight">{{ \App\Models\PeminjamanBuku::where('status', 'pending')->count() }}</h4>
                                <a href="{{ route('peminjaman-buku.index') }}" class="text-[11px] font-semibold text-amber-400 hover:underline mt-1.5 inline-block">
                                    Tinjau →
                                </a>
                            </div>
                        </div>

                        <!-- Peminjaman Ruangan Menunggu -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-orange-500/15 text-orange-400 flex items-center justify-center shrink-0 border border-orange-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ruang Pending</p>
                                <h4 class="text-2xl font-extrabold text-white tracking-tight">{{ \App\Models\PeminjamanRuangan::where('status', 'pending')->count() }}</h4>
                                <a href="{{ route('peminjaman-ruangan.index') }}" class="text-[11px] font-semibold text-orange-400 hover:underline mt-1.5 inline-block">
                                    Tinjau →
                                </a>
                            </div>
                        </div>

                        <!-- Peminjaman Buku Aktif -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/15 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Buku Dipinjam</p>
                                <h4 class="text-2xl font-extrabold text-white tracking-tight">{{ \App\Models\PeminjamanBuku::where('status', 'dipinjam')->count() }}</h4>
                                <span class="text-[11px] text-slate-400 block mt-1.5 font-medium">Sedang Berlangsung</span>
                            </div>
                        </div>

                        <!-- Peminjaman Ruangan Aktif -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-rose-500/15 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ruang Digunakan</p>
                                <h4 class="text-2xl font-extrabold text-white tracking-tight">{{ \App\Models\PeminjamanRuangan::where('status', 'disetujui')->count() }}</h4>
                                <span class="text-[11px] text-slate-400 block mt-1.5 font-medium">Sedang Berlangsung</span>
                            </div>
                        </div>

                    </div>
                </div>

            @else
                <!-- MAHASISWA DASHBOARD -->
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Aktivitas Peminjaman Saya</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Buku Aktif Saya -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Buku Sedang Dipinjam</p>
                                <h4 class="text-3xl font-extrabold text-white tracking-tight">
                                    {{ \App\Models\PeminjamanBuku::where('user_id', auth()->id())->where('status', 'dipinjam')->count() }}
                                </h4>
                                <div class="pt-2">
                                    <a href="{{ route('peminjaman-buku.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
                                        Lihat Riwayat
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Ruangan Aktif Saya -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-indigo-500/15 text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ruangan Aktif</p>
                                <h4 class="text-3xl font-extrabold text-white tracking-tight">
                                    {{ \App\Models\PeminjamanRuangan::where('user_id', auth()->id())->where('status', 'disetujui')->count() }}
                                </h4>
                                <div class="pt-2">
                                    <a href="{{ route('peminjaman-ruangan.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                                        Lihat Riwayat
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Menunggu Persetujuan Saya -->
                        <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengajuan Pending</p>
                                @php
                                    $pendingBuku = \App\Models\PeminjamanBuku::where('user_id', auth()->id())->where('status', 'pending')->count();
                                    $pendingRuang = \App\Models\PeminjamanRuangan::where('user_id', auth()->id())->where('status', 'pending')->count();
                                @endphp
                                <h4 class="text-3xl font-extrabold text-white tracking-tight">
                                    {{ $pendingBuku + $pendingRuang }}
                                </h4>
                                <span class="text-xs text-slate-400 block pt-2">Menunggu Konfirmasi Admin</span>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
