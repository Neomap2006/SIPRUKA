<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="p-2 sm:p-2.5 rounded-xl bg-[#004B87]/30 border border-[#00D1FF]/30 text-[#00E9FF] shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-white tracking-tight leading-tight">Daftar Peminjaman Ruangan</h2>
                    <p class="text-xs text-slate-400">Jadwal reservasi dan riwayat peminjaman</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full md:w-auto">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('peminjaman-ruangan.laporan') }}" 
                       class="inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-[#004B87]/50 hover:bg-[#004B87] text-[#00E9FF] border border-[#00D1FF]/40 font-bold px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl transition text-xs sm:text-sm flex-1 sm:flex-initial shrink-0 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Laporan Rekapitulasi
                    </a>
                @endif
                <a href="{{ route('peminjaman-ruangan.create') }}" 
                   class="inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-gradient-to-r from-[#004B87] to-[#00D1FF] hover:to-[#00E9FF] text-black font-bold px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition shadow-[0_0_12px_rgba(0,209,255,0.3)] text-xs sm:text-sm flex-1 sm:flex-initial shrink-0 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Ajukan Peminjaman
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-3 sm:p-6 pb-24 sm:pb-12 bg-black min-h-screen space-y-4 sm:space-y-6">
        @if (session('success'))
            <div class="p-3.5 sm:p-4 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-xl flex items-center gap-3 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold text-xs sm:text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-[#050c18] border border-[#004B87]/70 rounded-2xl shadow-[0_0_25px_rgba(0,209,255,0.08)] overflow-hidden">
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-[#004B87]">
                <table class="w-full text-slate-300 border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-black/80 border-b border-[#004B87]/60 text-xs font-bold text-[#00E9FF] uppercase tracking-wider text-center">
                        <th class="p-3 sm:p-4 w-12 text-center notranslate" translate="no">No.</th>
                        <th class="p-4 text-center">Peminjam</th>
                        <th class="p-4 text-center">Ruangan</th>
                        <th class="p-4 text-center">Tgl Mulai</th>
                        <th class="p-4 text-center">Jam Mulai</th>
                        <th class="p-4 text-center">Tgl Selesai</th>
                        <th class="p-4 text-center">Jam Selesai</th>
                        <th class="p-4 text-center">Durasi</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#004B87]/30 bg-transparent text-sm">
                    @forelse ($data as $item)
                    <tr class="hover:bg-[#004B87]/15 transition-colors">
                        <td class="p-4 text-center font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="p-4 text-center font-bold text-slate-100">{{ $item->nama_peminjam }}</td>
                        <td class="p-4 text-center font-semibold text-[#00E9FF]">{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                        <td class="p-4 text-center text-slate-400 font-mono text-xs">{{ $item->tanggal_mulai }}</td>
                        <td class="p-4 text-center text-slate-400 font-mono text-xs">{{ $item->waktu_mulai }}</td>
                        <td class="p-4 text-center text-slate-400 font-mono text-xs">{{ $item->tanggal_selesai }}</td>
                        <td class="p-4 text-center text-slate-400 font-mono text-xs">{{ $item->waktu_selesai }}</td>
                        <td class="p-4 text-center text-slate-300 font-medium">{{ $item->durasi }} menit</td>
                        <td class="p-4 text-center">
                            @php $st = $item->computed_status; @endphp
                            @if($st === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30 shadow-[0_0_8px_rgba(245,158,11,0.2)]">
                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                                    Pending
                                </span>
                            @elseif($st === 'disetujui')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-[0_0_8px_rgba(16,185,129,0.2)]">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                    Disetujui
                                </span>
                            @elseif($st === 'berlangsung')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#004B87]/40 text-[#00E9FF] border border-[#00D1FF]/50 shadow-[0_0_12px_rgba(0,209,255,0.4)]">
                                    <span class="w-1.5 h-1.5 bg-[#00D1FF] rounded-full animate-ping"></span>
                                    Berlangsung
                                </span>
                            @elseif($st === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                    Selesai
                                </span>
                            @elseif($st === 'ditolak')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-500/15 text-red-400 border border-red-500/30 shadow-[0_0_8px_rgba(239,68,68,0.2)]">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-400 border border-slate-700 capitalize">
                                    {{ $st }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center" x-data="{ openRejectModal: false }">
                            <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                <!-- Tombol Detail (Dapat diakses oleh Mahasiswa & Admin) -->
                                <a href="{{ route('peminjaman-ruangan.show', $item->id) }}" 
                                   class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg bg-[#004B87]/30 text-[#00E9FF] hover:bg-[#00D1FF]/20 hover:text-white transition-all border border-[#00D1FF]/30 hover:border-[#00D1FF] text-xs font-bold shadow-sm" 
                                   title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Detail
                                </a>

                                @if(in_array($st, ['disetujui', 'berlangsung', 'selesai']))
                                    <a href="{{ route('peminjaman-ruangan.izin-digital', $item->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30 hover:text-white transition-all border border-emerald-500/40 text-xs font-bold shadow-sm" 
                                       title="Unduh / Cetak Surat Izin Digital">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Surat Izin
                                    </a>
                                @endif

                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    @if($item->status === 'pending')
                                        <form action="{{ route('peminjaman-ruangan.approve', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/25 hover:text-emerald-300 transition-colors border border-emerald-500/30 shadow-[0_0_8px_rgba(16,185,129,0.2)]" 
                                                    onclick="return confirm('Setujui peminjaman ruangan ini?')" title="Setujui">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                        </form>

                                        <button type="button" @click="openRejectModal = true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500/25 hover:text-amber-300 transition-colors border border-amber-500/30 shadow-[0_0_8px_rgba(245,158,11,0.2)]" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    @endif

                                    <a href="{{ route('peminjaman-ruangan.edit', $item->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/25 hover:text-blue-300 transition-colors border border-blue-500/30 shadow-sm" 
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    
                                    <form action="{{ route('peminjaman-ruangan.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/25 hover:text-red-300 transition-colors border border-red-500/30 shadow-[0_0_8px_rgba(239,68,68,0.2)]" 
                                                onclick="return confirm('Hapus data peminjaman ruangan ini?')" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal Alasan Penolakan untuk Index Admin -->
                            @if(auth()->check() && auth()->user()->role === 'admin' && $item->status === 'pending')
                                <div x-show="openRejectModal" x-cloak style="display: none;" 
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md text-left"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">
                                    <div class="bg-[#050c18] border border-[#004B87] shadow-[0_0_35px_rgba(245,158,11,0.25)] rounded-2xl max-w-md w-full p-6 space-y-4" @click.away="openRejectModal = false">
                                        <div class="flex items-center justify-between border-b border-[#004B87]/50 pb-3">
                                            <h4 class="text-base font-bold text-slate-100 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                                Alasan Penolakan
                                            </h4>
                                            <button @click="openRejectModal = false" class="text-slate-400 hover:text-white">&times;</button>
                                        </div>
                                        <form action="{{ route('peminjaman-ruangan.reject', $item->id) }}" method="POST" class="space-y-4">
                                            @csrf @method('PATCH')
                                            <div>
                                                <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Tuliskan Alasan Penolakan <span class="text-red-400">*</span></label>
                                                <textarea name="alasan_penolakan" rows="3" 
                                                          class="w-full bg-black/80 border border-[#004B87] text-slate-200 placeholder-slate-500 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] text-sm"
                                                          placeholder="Tuliskan alasan penolakan berkas..." required></textarea>
                                            </div>
                                            <div class="flex items-center justify-end gap-3">
                                                <button type="button" @click="openRejectModal = false" class="px-4 py-2 text-sm text-slate-400 hover:text-slate-200 font-semibold">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-bold bg-amber-600 hover:bg-amber-500 text-white rounded-xl shadow-[0_0_12px_rgba(245,158,11,0.3)]">Tolak Pengajuan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center text-slate-500">
                            Belum ada data peminjaman ruangan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>

