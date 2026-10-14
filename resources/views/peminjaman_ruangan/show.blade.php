<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-navy-deep/40 border border-cyan-neon/30 text-cyan-bright">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight leading-tight">Detail Peminjaman Ruangan</h2>
                <p class="text-xs text-slate-400">Informasi lengkap, verifikasi berkas, dan status permohonan</p>
            </div>
        </div>
    </x-slot>

    @php
        $status = $peminjaman->computed_status;
        $isOwner = auth()->check() && $peminjaman->user_id === auth()->id();
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    @endphp

    <div class="py-8 bg-black min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Top Navigation & Action Badges -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('peminjaman-ruangan.index') }}" 
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-[#00E9FF] text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar Peminjaman
                </a>

                <div class="flex items-center gap-3">
                    @if(in_array($status, ['disetujui', 'berlangsung', 'selesai']))
                        <a href="{{ route('peminjaman-ruangan.izin-digital', $peminjaman->id) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white font-bold px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(0,209,255,0.4)] border border-[#00D1FF]/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Unduh Surat Izin Digital
                        </a>
                    @endif

                    @if($status === 'pending')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                            <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.8)]"></span>
                            Menunggu Persetujuan
                        </span>
                    @elseif($status === 'disetujui')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#00D1FF]/15 text-[#00E9FF] border border-[#00D1FF]/40 shadow-[0_0_12px_rgba(0,209,255,0.2)]">
                            <span class="w-2 h-2 bg-[#00D1FF] rounded-full shadow-[0_0_8px_#00D1FF]"></span>
                            Disetujui
                        </span>
                    @elseif($status === 'berlangsung')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-500/15 text-blue-300 border border-blue-500/40">
                            <span class="w-2 h-2 bg-blue-400 rounded-full animate-ping"></span>
                            Sedang Berlangsung
                        </span>
                    @elseif($status === 'selesai')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Selesai
                        </span>
                    @elseif($status === 'ditolak')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-red-500/15 text-red-400 border border-red-500/30">
                            <span class="w-2 h-2 bg-red-400 rounded-full shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                            Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <!-- Flash Session Alerts -->
            @if (session('success'))
                <div class="p-4 bg-[#00D1FF]/10 text-[#00E9FF] border border-[#00D1FF]/30 rounded-xl flex items-center gap-2 shadow-[0_0_12px_rgba(0,209,255,0.15)]">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl flex items-center gap-2 shadow-[0_0_12px_rgba(239,68,68,0.15)]">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informative Message Banner -->
            @if($status === 'disetujui')
                <div class="p-4 bg-[#00D1FF]/10 border border-[#00D1FF]/30 rounded-xl flex items-start gap-3 text-[#00E9FF] text-sm">
                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-[#00D1FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong class="font-bold block text-white mb-0.5">Peminjaman Disetujui & Surat Izin Digital Diterbitkan</strong>
                        Nomor Izin: <span class="font-mono text-[#00D1FF] font-bold">{{ $peminjaman->nomor_surat_izin ?? '-' }}</span>. Anda dapat mencetak Surat Izin Digital sebagai bukti sah penggunaan ruangan.
                    </div>
                </div>
            @elseif($status === 'ditolak')
                <div class="p-5 bg-red-500/10 border border-red-500/30 rounded-2xl space-y-3">
                    <div class="flex items-center gap-2 text-red-400 font-bold text-base">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        Pengajuan Ditolak oleh Admin
                    </div>
                    <div class="text-sm text-red-300 bg-black/40 border border-red-500/20 p-3.5 rounded-xl">
                        <strong class="text-white block text-xs uppercase mb-1">Catatan / Alasan Penolakan:</strong>
                        {{ $peminjaman->alasan_penolakan ?? 'Tidak ada catatan.' }}
                    </div>

                    @if($isOwner)
                        <div class="pt-2 flex items-center justify-between" x-data="{ openRevisiModal: false }">
                            <p class="text-xs text-slate-400">Silakan lakukan perbaikan berkas dan ajukan kembali permohonan ini.</p>
                            <button type="button" @click="openRevisiModal = true" 
                                    class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-black font-bold px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Perbaiki Berkas & Ajukan Kembali
                            </button>

                            <!-- Modal Perbaikan Berkas / Ajukan Kembali -->
                            <div x-show="openRevisiModal" x-cloak style="display: none;" 
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
                                 x-transition>
                                <div class="bg-[#050c18] border border-[#004B87] rounded-2xl max-w-lg w-full p-6 shadow-[0_0_30px_rgba(0,209,255,0.2)] space-y-4" @click.away="openRevisiModal = false">
                                    <div class="flex items-center justify-between border-b border-[#004B87] pb-3">
                                        <h4 class="text-lg font-bold text-white flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                            Perbaikan Berkas / Ajukan Kembali
                                        </h4>
                                        <button @click="openRevisiModal = false" class="text-slate-400 hover:text-white">✕</button>
                                    </div>

                                    <form action="{{ route('peminjaman-ruangan.ajukan-kembali', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf @method('PATCH')

                                        <div>
                                            <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-1">Keperluan / Keterangan Tambahan</label>
                                            <textarea name="keperluan" rows="3" class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl p-3 text-sm focus:border-[#00E9FF] focus:outline-none" required>{{ old('keperluan', $peminjaman->keperluan) }}</textarea>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-1">Upload Ulang NIPD / Identitas (Opsional)</label>
                                            <input type="file" name="file_dokumen" class="w-full bg-black border border-[#004B87] text-slate-300 text-xs rounded-xl p-2">
                                        </div>

                                        @if($peminjaman->jenis_peminjam === 'berorganisasi')
                                            <div>
                                                <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-1">Upload Ulang Surat Permohonan (SP) (Opsional)</label>
                                                <input type="file" name="file_sp" class="w-full bg-black border border-[#004B87] text-slate-300 text-xs rounded-xl p-2">
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#004B87]/50">
                                            <button type="button" @click="openRevisiModal = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-400 hover:text-slate-200">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-[#00D1FF] hover:bg-[#00E9FF] text-black transition">Ajukan Sekarang</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Grid 2 Kolom (Detail Information & Documents + Timeline) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Informasi Peminjaman & Dokumen Verifikasi -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#050c18] border border-[#004B87] rounded-2xl p-6 sm:p-8 space-y-6 relative overflow-hidden shadow-[0_0_30px_rgba(0,209,255,0.08)]">
                        
                        <div class="border-b border-[#004B87] pb-4 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Nomor Pengajuan</span>
                                <h3 class="text-2xl font-black text-[#00E9FF] tracking-wide">{{ $peminjaman->nomor_pengajuan }}</h3>
                                @if($peminjaman->nomor_surat_izin)
                                    <p class="text-xs font-mono text-[#00D1FF] mt-0.5">Surat Izin: {{ $peminjaman->nomor_surat_izin }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-400 block">Dibuat Pada</span>
                                <span class="text-sm text-slate-200 font-medium">{{ \Carbon\Carbon::parse($peminjaman->created_at)->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- Data Fields -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87]">
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Peminjam / Penanggung Jawab</label>
                                <p class="text-base font-semibold text-white mt-1">{{ $peminjaman->nama_peminjam }}</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $peminjaman->jenis_peminjam === 'berorganisasi' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-[#00D1FF]/20 text-[#00E9FF] border border-[#00D1FF]/30' }}">
                                        {{ $peminjaman->jenis_peminjam === 'berorganisasi' ? 'Organisasi / UKM' : 'Individu / Mahasiswa' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87]">
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Data Akun Mahasiswa</label>
                                <p class="text-sm font-medium text-slate-200 mt-1">{{ $peminjaman->user->email ?? '-' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    NIPD/NIM: <span class="text-white font-mono">{{ $peminjaman->user->nipd ?? '-' }}</span> | Prodi: <span class="text-white">{{ $peminjaman->user->prodi ?? '-' }}</span>
                                </p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87]">
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Ruangan Dipinjam</label>
                                <p class="text-base font-semibold text-[#00E9FF] mt-1">{{ $peminjaman->ruangan->nama_ruangan }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Lokasi: {{ $peminjaman->ruangan->lokasi ?? '-' }} (Kapasitas: {{ $peminjaman->ruangan->kapasitas }} Orang)</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87]">
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Admin Verifikator</label>
                                @if($peminjaman->admin)
                                    <p class="text-sm font-semibold text-white mt-1">{{ $peminjaman->admin->nama }}</p>
                                    <p class="text-xs text-[#00D1FF]">{{ $peminjaman->admin->jabatan ?? 'Pengelola Ruangan' }}</p>
                                @else
                                    <p class="text-sm text-slate-500 mt-1">Belum diverifikasi</p>
                                @endif
                            </div>

                            <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87]">
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Rentang Tanggal</label>
                                <p class="text-sm font-medium text-slate-200 mt-1">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->translatedFormat('d F Y') }}
                                    @if($peminjaman->tanggal_mulai !== $peminjaman->tanggal_selesai)
                                        s/d {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->translatedFormat('d F Y') }}
                                    @endif
                                </p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87]">
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Waktu & Durasi</label>
                                <p class="text-sm font-medium text-slate-200 mt-1">
                                    {{ \Carbon\Carbon::parse($peminjaman->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->waktu_selesai)->format('H:i') }} WIB
                                </p>
                                <p class="text-xs font-mono text-[#00D1FF] mt-0.5">Total: {{ $peminjaman->durasi }} menit ({{ round($peminjaman->durasi/60, 1) }} jam)</p>
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="border-t border-[#004B87] pt-4">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Keperluan / Agenda Peminjaman</label>
                            <p class="text-sm text-slate-200 bg-black/80 border border-[#004B87] rounded-xl p-4 leading-relaxed">
                                {{ $peminjaman->keperluan ?? 'Tidak mencantumkan keperluan.' }}
                            </p>
                        </div>

                        @if(!empty($peminjaman->catatan_peminjam))
                        <!-- Pesan / Catatan Khusus Mahasiswa -->
                        <div class="border-t border-[#004B87] pt-4">
                            <div class="flex items-center gap-2 mb-2">
                                <label class="text-xs font-semibold text-[#00E9FF] uppercase tracking-wider block">💬 Pesan Khusus Mahasiswa untuk Admin</label>
                                <span class="text-[10px] font-bold bg-[#004B87]/50 text-[#00D1FF] px-2 py-0.5 rounded-full border border-[#004B87]">Terkirim via WhatsApp</span>
                            </div>
                            <p class="text-sm text-slate-100 bg-[#004B87]/20 border border-[#004B87]/60 rounded-xl p-4 leading-relaxed italic">
                                "{{ $peminjaman->catatan_peminjam }}"
                            </p>
                        </div>
                        @endif

                        <!-- Berkas Persyaratan & Verifikasi Dokumen (Sesuai Diagram) -->
                        <div class="border-t border-[#004B87] pt-4 space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#00D1FF] shadow-[0_0_8px_#00D1FF]"></span>
                                <h4 class="text-sm font-bold text-white uppercase tracking-wider">Dokumen Verifikasi Pemohon</h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Dokumen NIPD / Identitas --}}
                                <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87] flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-slate-300">Kartu Identitas / NIPD</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Identitas Pemohon / KTM</p>
                                    </div>
                                    @if($peminjaman->file_dokumen)
                                        <a href="{{ route('peminjaman-ruangan.dokumen', ['id' => $peminjaman->id, 'type' => 'dokumen']) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#004B87]/50 hover:bg-[#00D1FF] text-white hover:text-black font-semibold text-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                            Buka Dokumen
                                        </a>
                                    @else
                                        <span class="text-xs text-red-400 font-medium">Belum diunggah</span>
                                    @endif
                                </div>

                                {{-- Surat Permohonan (SP) --}}
                                <div class="p-3.5 rounded-xl bg-black/70 border border-[#004B87] flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-slate-300">Surat Permohonan (SP)</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Pengantar Organisasi/UKM</p>
                                    </div>
                                    @if($peminjaman->file_sp)
                                        <a href="{{ route('peminjaman-ruangan.dokumen', ['id' => $peminjaman->id, 'type' => 'sp']) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#004B87]/50 hover:bg-[#00D1FF] text-white hover:text-black font-semibold text-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                            Buka Surat
                                        </a>
                                    @elseif($peminjaman->jenis_peminjam === 'berorganisasi')
                                        <span class="text-xs text-red-400 font-medium">Wajib diunggah</span>
                                    @else
                                        <span class="text-xs text-slate-500 font-medium">Tidak diwajibkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons untuk Admin Approval & Rejection -->
                        @if($isAdmin && $status === 'pending')
                            <div class="border-t border-[#004B87] pt-6 flex flex-wrap items-center justify-end gap-3" x-data="{ openRejectModal: false }">
                                <form action="{{ route('peminjaman-ruangan.approve', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini dan menerbitkan Surat Izin Digital?')"
                                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#004B87] to-[#00D1FF] hover:to-[#00E9FF] text-black font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-[0_0_15px_rgba(0,209,255,0.4)]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Setujui & Terbitkan Izin Digital
                                    </button>
                                </form>

                                <button type="button" 
                                        @click="openRejectModal = true"
                                        class="inline-flex items-center gap-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 px-5 py-2.5 rounded-xl font-semibold text-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tolak Pengajuan
                                </button>

                                <!-- Modal Alasan Penolakan -->
                                <div x-show="openRejectModal" 
                                     x-cloak style="display: none;" 
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
                                     x-transition>
                                    
                                    <div class="bg-[#050c18] border border-[#004B87] rounded-2xl max-w-md w-full p-6 shadow-[0_0_30px_rgba(239,68,68,0.2)] space-y-5" @click.away="openRejectModal = false">
                                        <div class="flex items-center justify-between border-b border-[#004B87] pb-3">
                                            <h4 class="text-lg font-bold text-white flex items-center gap-2">
                                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                </svg>
                                                Alasan Penolakan Pengajuan
                                            </h4>
                                            <button @click="openRejectModal = false" class="text-slate-400 hover:text-white">✕</button>
                                        </div>

                                        <form action="{{ route('peminjaman-ruangan.reject', $peminjaman->id) }}" method="POST" class="space-y-4">
                                            @csrf @method('PATCH')
                                            <div>
                                                <label for="alasan_penolakan" class="block text-xs font-semibold text-slate-300 uppercase mb-2">Tuliskan Catatan & Alasan Penolakan <span class="text-red-400">*</span></label>
                                                <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" 
                                                          class="w-full bg-black border border-[#004B87] text-slate-200 placeholder-slate-500 rounded-xl p-3 focus:outline-none focus:border-[#00D1FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm"
                                                          placeholder="Contoh: Dokumen Surat Permohonan belum ada tanda tangan Ketua Organisasi, atau Jadwal bertabrakan dengan agenda Dies Natalis..." 
                                                          required></textarea>
                                            </div>

                                            <div class="flex items-center justify-end gap-3 pt-2">
                                                <button type="button" @click="openRejectModal = false" 
                                                        class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-400 hover:text-slate-200 hover:bg-[#004B87]/40 transition">
                                                    Batal
                                                </button>
                                                <button type="submit" 
                                                        class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-red-600 hover:bg-red-500 text-white transition shadow-lg shadow-red-600/20">
                                                    Tolak Pengajuan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Right Column: Status Timeline -->
                <div class="space-y-6">
                    <div class="bg-[#050c18] border border-[#004B87] rounded-2xl shadow-[0_0_20px_rgba(0,209,255,0.08)] p-6 space-y-5">
                        <h3 class="text-base font-bold text-white border-b border-[#004B87] pb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#00E9FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Timeline Status
                        </h3>

                        <!-- Timeline Item List -->
                        <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#004B87]">
                            
                            <!-- Step 1: Pengajuan Dibuat -->
                            <div class="relative">
                                <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#00D1FF] border-2 border-black flex items-center justify-center text-black text-[9px] font-bold shadow-[0_0_8px_#00D1FF]">✓</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-100">Pengajuan Dibuat</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($peminjaman->created_at)->translatedFormat('d F Y, H:i') }}</p>
                                </div>
                            </div>

                            <!-- Step 2: Menunggu Persetujuan -->
                            <div class="relative">
                                @if($status === 'pending')
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-amber-400 border-2 border-black animate-ping"></span>
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-amber-500 border-2 border-black flex items-center justify-center text-black text-[9px] font-bold">⌛</span>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-400">Menunggu Persetujuan</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Sedang ditinjau oleh Admin</p>
                                    </div>
                                @else
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#00D1FF] border-2 border-black flex items-center justify-center text-black text-[9px] font-bold">✓</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-100">Menunggu Persetujuan</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Selesai ditinjau</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Step 3: Keputusan Status (Disetujui / Ditolak) -->
                            @if(in_array($status, ['disetujui', 'berlangsung', 'selesai']))
                                <div class="relative">
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#00D1FF] border-2 border-black flex items-center justify-center text-black text-[9px] font-bold shadow-[0_0_8px_#00D1FF]">✓</span>
                                    <div>
                                        <p class="text-sm font-semibold text-[#00E9FF]">Pengajuan Disetujui</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($peminjaman->updated_at)->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @elseif($status === 'ditolak')
                                <div class="relative">
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-red-500 border-2 border-black flex items-center justify-center text-white text-[9px] font-bold">✕</span>
                                    <div>
                                        <p class="text-sm font-semibold text-red-400">Pengajuan Ditolak</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($peminjaman->updated_at)->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="relative">
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#004B87] border-2 border-black"></span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Persetujuan Admin</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Belum ada keputusan</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 4: Peminjaman Berlangsung -->
                            <div class="relative">
                                @if($status === 'berlangsung')
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-blue-500 border-2 border-black animate-pulse shadow-[0_0_8px_rgba(59,130,246,0.8)]"></span>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-400">Peminjaman Berlangsung</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Ruangan sedang digunakan</p>
                                    </div>
                                @elseif($status === 'selesai')
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#00D1FF] border-2 border-black flex items-center justify-center text-black text-[9px] font-bold">✓</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-200">Peminjaman Berlangsung</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Telah dilaksanakan</p>
                                    </div>
                                @else
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#004B87] border-2 border-black"></span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Peminjaman Berlangsung</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Menunggu waktu pelaksanaan</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Step 5: Peminjaman Selesai -->
                            <div class="relative">
                                @if($status === 'selesai')
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-slate-400 border-2 border-black flex items-center justify-center text-black text-[9px] font-bold">✓</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-200">Peminjaman Selesai</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Waktu peminjaman berakhir</p>
                                    </div>
                                @else
                                    <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#004B87] border-2 border-black"></span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Peminjaman Selesai</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Belum selesai</p>
                                    </div>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
