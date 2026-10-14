<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-[#00D1FF]/20 p-2 rounded-xl border border-[#00D1FF]/40 shadow-[0_0_12px_rgba(0,209,255,0.3)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-white tracking-tight leading-tight">Dashboard</h2>
                    <p class="text-xs text-slate-400">Ringkasan aktivitas peminjaman dan ketersediaan ruangan</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cyber-black border border-navy-deep text-xs font-semibold text-cyan-bright">
                <span class="w-2 h-2 rounded-full bg-cyan-neon animate-pulse shadow-[0_0_6px_#00D1FF]"></span>
                <span>System Active</span>
            </div>
        </div>
    </x-slot>

    @php
        // Sinkronisasi status lifecycle otomatis untuk seluruh data aktif
        $allBookings = \App\Models\PeminjamanRuangan::whereIn('status', ['disetujui', 'berlangsung'])->get();
        foreach ($allBookings as $b) {
            $b->syncLifecycleStatus();
        }
    @endphp

    <div class="py-8 bg-black min-h-screen relative overflow-hidden text-slate-100">
        <!-- Neon decorative background glows -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#00D1FF]/10 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 w-[600px] h-[600px] bg-[#004B87]/15 rounded-full blur-[150px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
            
            <!-- Greeting Banner -->
            <div class="bg-gradient-to-r from-[#050c18] via-[#004B87]/30 to-[#050c18] text-white rounded-2xl shadow-[0_0_30px_rgba(0,209,255,0.12)] overflow-hidden relative border border-[#004B87]/80 hover:border-[#00D1FF]/70 transition-all duration-300">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-[#00D1FF]/10 blur-[80px] rounded-full pointer-events-none"></div>
                <div class="p-8 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl md:text-3xl font-black tracking-tight flex items-center gap-2">
                            <span>Selamat Datang Kembali,</span> 
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00E9FF] to-white">{{ auth()->user()->nama }}</span>! 👋
                        </h3>
                        <p class="text-slate-400 text-sm mt-2 flex items-center gap-2">
                            <span>Masuk sebagai</span>
                            <span class="bg-[#004B87]/40 text-[#00E9FF] px-3 py-1 rounded-full font-bold text-xs border border-[#00D1FF]/40 shadow-[0_0_8px_rgba(0,209,255,0.3)] uppercase tracking-wider">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-black/60 backdrop-blur-md px-6 py-3 rounded-xl border border-[#004B87]/60 text-right md:self-center shadow-inner">
                        <span class="text-[10px] uppercase tracking-widest text-[#00D1FF] font-bold block">Kalender Sistem</span>
                        <span class="text-sm font-black text-slate-100 mt-0.5 block tracking-wide">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
                <!-- ADMIN DASHBOARD -->
                <div>
                    <h3 class="text-xs font-bold text-[#00D1FF]/80 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00D1FF]"></span>
                        Statistik Sistem & Inventaris
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Total Ruangan -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#004B87]/60 hover:border-[#00D1FF] shadow-lg hover:shadow-[0_0_20px_rgba(0,209,255,0.2)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-14 h-14 rounded-xl bg-[#004B87]/30 text-[#00E9FF] flex items-center justify-center shrink-0 border border-[#00D1FF]/40 shadow-[0_0_12px_rgba(0,209,255,0.3)]">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Ruangan</p>
                                <h4 class="text-4xl font-black text-white tracking-tight">{{ \App\Models\Ruangan::count() }}</h4>
                                <div class="pt-2">
                                    <a href="{{ route('ruangan.index') }}" class="text-xs font-bold text-[#00E9FF] hover:text-white flex items-center gap-1 transition-colors">
                                        Kelola Ruangan
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Total User -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#004B87]/60 hover:border-[#00D1FF] shadow-lg hover:shadow-[0_0_20px_rgba(0,209,255,0.2)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-14 h-14 rounded-xl bg-[#004B87]/30 text-[#00E9FF] flex items-center justify-center shrink-0 border border-[#00D1FF]/40 shadow-[0_0_12px_rgba(0,209,255,0.3)]">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                                <h4 class="text-4xl font-black text-white tracking-tight">{{ \App\Models\User::count() }}</h4>
                                <div class="pt-2">
                                    <a href="{{ route('users.index') }}" class="text-xs font-bold text-[#00E9FF] hover:text-white flex items-center gap-1 transition-colors">
                                        Manajemen User
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Logs -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#004B87]/60 hover:border-[#00D1FF] shadow-lg hover:shadow-[0_0_20px_rgba(0,209,255,0.2)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-14 h-14 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status WhatsApp</p>
                                <h4 class="text-xl font-bold text-emerald-400 tracking-tight flex items-center gap-2 mt-1">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                    Cloud API Ready
                                </h4>
                                <div class="pt-2">
                                    <a href="{{ route('whatsapp-setting.index') }}" class="text-xs font-bold text-[#00E9FF] hover:text-white flex items-center gap-1 transition-colors">
                                        WhatsApp Setting
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <h3 class="text-xs font-bold text-[#00D1FF]/80 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#004B87]"></span>
                        Pengajuan & Lifecycle Status
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        <!-- Pending -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/40 shadow-[0_0_10px_rgba(245,158,11,0.3)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Approval</p>
                                <h4 class="text-3xl font-black text-amber-400 tracking-tight">{{ \App\Models\PeminjamanRuangan::where('status', 'pending')->count() }}</h4>
                                <a href="{{ route('peminjaman-ruangan.index') }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 mt-1.5 inline-block transition-colors">
                                    Tinjau Berkas →
                                </a>
                            </div>
                        </div>

                        <!-- Disetujui -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-emerald-500/30 hover:border-emerald-400 shadow-lg hover:shadow-[0_0_20px_rgba(16,185,129,0.2)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/40 shadow-[0_0_10px_rgba(16,185,129,0.3)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Telah Disetujui</p>
                                <h4 class="text-3xl font-black text-emerald-400 tracking-tight">{{ \App\Models\PeminjamanRuangan::where('status', 'disetujui')->count() }}</h4>
                                <span class="text-xs text-slate-400 block mt-1 font-medium">Menunggu Jadwal</span>
                            </div>
                        </div>

                        <!-- Berlangsung -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#00D1FF]/40 hover:border-[#00E9FF] shadow-lg hover:shadow-[0_0_20px_rgba(0,209,255,0.25)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#004B87]/30 text-[#00E9FF] flex items-center justify-center shrink-0 border border-[#00D1FF]/50 shadow-[0_0_10px_rgba(0,209,255,0.4)]">
                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sedang Digunakan</p>
                                <h4 class="text-3xl font-black text-[#00E9FF] tracking-tight">{{ \App\Models\PeminjamanRuangan::where('status', 'berlangsung')->count() }}</h4>
                                <span class="text-xs text-[#00D1FF] block mt-1 font-medium">Ruangan Aktif</span>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#004B87]/40 hover:border-[#00D1FF]/60 shadow-lg hover:shadow-[0_0_20px_rgba(0,75,135,0.3)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-slate-800 text-slate-300 flex items-center justify-center shrink-0 border border-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Selesai</p>
                                <h4 class="text-3xl font-black text-slate-200 tracking-tight">{{ \App\Models\PeminjamanRuangan::where('status', 'selesai')->count() }}</h4>
                                <span class="text-xs text-slate-500 block mt-1 font-medium">Riwayat Arsip</span>
                            </div>
                        </div>

                    </div>
                </div>

            @else
                <!-- MAHASISWA DASHBOARD -->
                @php
                    $rejectedNotifBookings = \App\Models\PeminjamanRuangan::with('ruangan')
                        ->where('user_id', auth()->id())
                        ->where('status', 'ditolak')
                        ->orderBy('updated_at', 'desc')
                        ->get();

                    $completedNotifBookings = \App\Models\PeminjamanRuangan::with('ruangan')
                        ->where('user_id', auth()->id())
                        ->where('status', 'selesai')
                        ->orderBy('updated_at', 'desc')
                        ->take(3)
                        ->get();

                    $allRuangan = \App\Models\Ruangan::with(['peminjamanRuangan' => function($q) {
                        $q->whereIn('status', ['berlangsung', 'disetujui']);
                    }])->get();

                    $countTersedia = 0;
                    $countDipinjam = 0;
                    foreach ($allRuangan as $r) {
                        $hasActive = $r->peminjamanRuangan->contains(function($p) {
                            return $p->computed_status === 'berlangsung';
                        });
                        if ($r->status === 'perbaikan' || $r->status === 'dibooking' || $hasActive) {
                            $countDipinjam++;
                        } else {
                            $countTersedia++;
                        }
                    }
                @endphp

                <!-- Notifikasi Peminjaman Ditolak untuk Mahasiswa -->
                @if($rejectedNotifBookings->isNotEmpty())
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-red-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse shadow-[0_0_8px_#EF4444]"></span>
                            <span>Perhatian: Pengajuan Ditolak</span>
                        </h3>
                        <div class="space-y-3">
                            @foreach($rejectedNotifBookings as $rNotif)
                                <div class="bg-[#050c18] border border-red-500/40 hover:border-red-400 p-4 sm:p-5 rounded-2xl transition-all shadow-[0_0_20px_rgba(239,68,68,0.15)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <span class="w-9 h-9 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center shrink-0 border border-red-500/40 text-sm font-bold shadow-[0_0_10px_rgba(239,68,68,0.3)]">✕</span>
                                        <div class="space-y-1 flex-1 min-w-0">
                                            <p class="text-sm font-bold text-white leading-snug">
                                                Pengajuan <span class="text-red-400">{{ $rNotif->ruangan->nama_ruangan ?? 'Ruangan' }}</span> ({{ $rNotif->nomor_pengajuan }}) DITOLAK
                                            </p>
                                            <p class="text-xs text-red-300 bg-red-500/10 border border-red-500/20 p-2.5 rounded-xl font-medium leading-relaxed break-words">
                                                <strong class="text-white block text-[11px] uppercase tracking-wider mb-0.5">Alasan Penolakan:</strong>
                                                {{ $rNotif->alasan_penolakan ?? 'Tidak ada catatan.' }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('peminjaman-ruangan.show', $rNotif->id) }}" 
                                       class="w-full sm:w-auto text-center shrink-0 px-4 py-3 rounded-xl bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-black font-bold text-xs uppercase tracking-wider transition-all shadow-[0_0_12px_rgba(245,158,11,0.3)] flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                        Perbaiki Berkas & Ajukan Kembali
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Notifikasi Peminjaman Selesai untuk Mahasiswa -->
                @if($completedNotifBookings->isNotEmpty())
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-[#00D1FF] uppercase tracking-widest flex items-center gap-2">
                            <span>🔔 Notifikasi Peminjaman</span>
                        </h3>
                        <div class="space-y-2">
                            @foreach($completedNotifBookings as $cNotif)
                                <a href="{{ route('peminjaman-ruangan.show', $cNotif->id) }}" 
                                   class="block bg-[#050c18] hover:bg-[#0a172c] border border-[#004B87]/60 hover:border-[#00D1FF] p-4 rounded-xl transition-all shadow-md group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">✓</span>
                                            <div>
                                                <p class="text-sm font-bold text-slate-200 group-hover:text-[#00E9FF] transition-colors">
                                                    Peminjaman <strong class="text-white">{{ $cNotif->ruangan->nama_ruangan ?? 'Ruangan' }}</strong> telah selesai.
                                                </p>
                                                <p class="text-xs text-slate-400 mt-0.5">
                                                    {{ \Carbon\Carbon::parse($cNotif->tanggal_mulai)->translatedFormat('d F Y') }} ({{ \Carbon\Carbon::parse($cNotif->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($cNotif->waktu_selesai)->format('H:i') }} WIB)
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-[#00E9FF] font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                            Unduh Izin →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Ringkasan Statistik Mahasiswa -->
                <div>
                    <h3 class="text-xs font-bold text-[#00D1FF]/80 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00D1FF]"></span>
                        Aktivitas Peminjaman Saya
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Berlangsung -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#00D1FF]/50 shadow-[0_0_20px_rgba(0,209,255,0.15)] hover:shadow-[0_0_25px_rgba(0,209,255,0.35)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-14 h-14 rounded-xl bg-[#004B87]/30 text-[#00E9FF] flex items-center justify-center shrink-0 border border-[#00D1FF]/50 shadow-[0_0_10px_rgba(0,209,255,0.4)]">
                                <svg class="w-7 h-7 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sedang Berlangsung</p>
                                <h4 class="text-4xl font-black text-[#00E9FF] tracking-tight">
                                    {{ \App\Models\PeminjamanRuangan::where('user_id', auth()->id())->where('status', 'berlangsung')->count() }}
                                </h4>
                                <div class="pt-2">
                                    <a href="{{ route('peminjaman-ruangan.index') }}" class="text-xs font-bold text-[#00E9FF] hover:text-white flex items-center gap-1 transition-colors">
                                        Lihat Status
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Pengajuan Pending Saya -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-14 h-14 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/40">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengajuan Pending</p>
                                <h4 class="text-4xl font-black text-amber-400 tracking-tight">
                                    {{ \App\Models\PeminjamanRuangan::where('user_id', auth()->id())->where('status', 'pending')->count() }}
                                </h4>
                                <span class="text-xs text-slate-400 block pt-1">Menunggu Verifikasi Admin</span>
                            </div>
                        </div>

                        <!-- Selesai Saya -->
                        <div class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#004B87]/40 hover:border-[#00D1FF]/60 shadow-lg hover:shadow-[0_0_20px_rgba(0,75,135,0.3)] hover:-translate-y-1 transition-all duration-300 p-6 flex items-start gap-5">
                            <div class="w-14 h-14 rounded-xl bg-slate-800 text-slate-300 flex items-center justify-center shrink-0 border border-slate-700">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <div class="space-y-1 flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peminjaman Selesai</p>
                                <h4 class="text-4xl font-black text-slate-200 tracking-tight">
                                    {{ \App\Models\PeminjamanRuangan::where('user_id', auth()->id())->where('status', 'selesai')->count() }}
                                </h4>
                                <span class="text-xs text-slate-500 block pt-1">Riwayat Peminjaman</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SEKSI UTAMA: KATALOG KETERSEDIAAN RUANGAN REAL-TIME -->
                <div x-data="{ search: '', filterStatus: 'all' }" class="space-y-6 pt-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#050c18] border border-[#004B87]/70 p-5 rounded-2xl shadow-[0_0_25px_rgba(0,209,255,0.08)]">
                        <div>
                            <h3 class="text-lg font-black text-white tracking-tight flex items-center gap-2.5">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                <span>Ketersediaan Ruangan Kampus (Live Status)</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Menampilkan {{ $countTersedia }} ruangan <span class="text-emerald-400 font-bold">tersedia</span> dan {{ $countDipinjam }} ruangan <span class="text-amber-400 font-bold">dipinjam/non-aktif</span> saat ini.
                            </p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                            <!-- Filter Buttons -->
                            <div class="flex flex-wrap sm:flex-nowrap bg-black/80 p-1 rounded-xl border border-[#004B87] gap-1">
                                <button type="button" @click="filterStatus = 'all'" 
                                        :class="filterStatus === 'all' ? 'bg-[#004B87] text-white shadow-md' : 'text-slate-400 hover:text-white'" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex-1 text-center">
                                    Semua ({{ count($allRuangan) }})
                                </button>
                                <button type="button" @click="filterStatus = 'tersedia'" 
                                        :class="filterStatus === 'tersedia' ? 'bg-emerald-500/30 text-emerald-300 border border-emerald-500/50 shadow-md' : 'text-slate-400 hover:text-white'" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex-1 text-center whitespace-nowrap">
                                    🟢 Tersedia ({{ $countTersedia }})
                                </button>
                                <button type="button" @click="filterStatus = 'dipinjam'" 
                                        :class="filterStatus === 'dipinjam' ? 'bg-amber-500/30 text-amber-300 border border-amber-500/50 shadow-md' : 'text-slate-400 hover:text-white'" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex-1 text-center whitespace-nowrap">
                                    🔴 Non-Aktif ({{ $countDipinjam }})
                                </button>
                            </div>

                            <!-- Search Input -->
                            <div class="relative w-full sm:w-auto min-w-[200px]">
                                <input type="text" x-model="search" placeholder="Cari nama/lokasi..." 
                                       class="w-full bg-black/90 border border-[#004B87] text-xs text-slate-100 placeholder-slate-500 rounded-xl pl-9 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition">
                                <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Room Grid Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($allRuangan as $r)
                            @php
                                $activeBorrow = $r->peminjamanRuangan->first(function($p) {
                                    return $p->computed_status === 'berlangsung';
                                });
                                $isPerbaikan = $r->status === 'perbaikan';
                                $isDipinjam = $r->status === 'dibooking' || $activeBorrow;
                                $statusKey = $isPerbaikan ? 'perbaikan' : ($isDipinjam ? 'dipinjam' : 'tersedia');
                            @endphp

                            <div x-show="(filterStatus === 'all' || (filterStatus === 'tersedia' && '{{ $statusKey }}' === 'tersedia') || (filterStatus === 'dipinjam' && '{{ $statusKey }}' !== 'tersedia')) && ('{{ strtolower($r->nama_ruangan) }} {{ strtolower($r->lokasi) }}'.includes(search.toLowerCase()))"
                                 class="bg-[#050c18] backdrop-blur-md rounded-2xl border border-[#004B87]/60 hover:border-[#00D1FF] shadow-lg hover:shadow-[0_0_25px_rgba(0,209,255,0.2)] transition-all duration-300 p-6 flex flex-col justify-between group">
                                
                                <div class="space-y-4">
                                    <!-- Header Card -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="p-3 rounded-xl bg-[#004B87]/30 text-[#00E9FF] border border-[#00D1FF]/30 group-hover:border-[#00D1FF] transition-colors">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-base text-white group-hover:text-[#00E9FF] transition-colors">{{ $r->nama_ruangan }}</h4>
                                                <p class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3.5 h-3.5 text-[#00D1FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                    </svg>
                                                    {{ $r->lokasi ?? 'Gedung Utama' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        @if($isPerbaikan)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-red-500/15 text-red-400 border border-red-500/30 shrink-0">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                Perbaikan
                                            </span>
                                        @elseif($isDipinjam)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/40 shrink-0 shadow-[0_0_8px_rgba(245,158,11,0.2)]">
                                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                                Dipinjam
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/40 shrink-0 shadow-[0_0_10px_rgba(16,185,129,0.25)]">
                                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                                Tersedia
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Details & Info -->
                                    <div class="bg-black/60 rounded-xl p-3.5 border border-[#004B87]/40 space-y-2 text-xs">
                                        <div class="flex items-center justify-between text-slate-300">
                                            <span class="text-slate-400">Kapasitas:</span>
                                            <span class="font-bold text-white flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-[#00E9FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                </svg>
                                                {{ $r->kapasitas }} Orang
                                            </span>
                                        </div>

                                        @if($activeBorrow)
                                            <div class="pt-1.5 border-t border-slate-800 text-amber-300/90 text-[11px] leading-relaxed">
                                                <span>⏱️ Sedang dipakai s/d <strong>{{ \Carbon\Carbon::parse($activeBorrow->waktu_selesai)->format('H:i') }} WIB</strong> ({{ $activeBorrow->nama_peminjam }})</span>
                                            </div>
                                        @elseif($r->deskripsi)
                                            <div class="pt-1.5 border-t border-slate-800 text-slate-400 text-[11px] line-clamp-2">
                                                {{ $r->deskripsi }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="pt-5 mt-auto">
                                    @if($isPerbaikan)
                                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-800 text-slate-500 font-bold text-xs cursor-not-allowed text-center border border-slate-700">
                                            Tidak Dapat Dipinjam (Perbaikan)
                                        </button>
                                    @else
                                        <a href="{{ route('peminjaman-ruangan.create', ['ruangan_id' => $r->id]) }}" 
                                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl {{ $isDipinjam ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 hover:bg-amber-500/30' : 'bg-gradient-to-r from-[#004B87] to-[#00D1FF] text-black hover:brightness-110 shadow-[0_0_15px_rgba(0,209,255,0.3)]' }} font-bold text-xs transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            <span>{{ $isDipinjam ? 'Ajukan untuk Jadwal Lain' : 'Pinjam Ruangan Ini' }}</span>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center bg-[#050c18] border border-[#004B87]/40 rounded-2xl">
                                <p class="text-slate-400 text-sm">Belum ada data ruangan yang terdaftar di sistem.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

