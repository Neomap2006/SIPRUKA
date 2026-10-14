<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap lg:flex-nowrap items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3 min-w-0">
                <div class="p-2 rounded-xl bg-[#004B87]/40 border border-[#00D1FF]/30 text-[#00E9FF] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-xl lg:text-2xl text-white tracking-tight leading-tight truncate">Laporan & Rekapitulasi Peminjaman</h2>
                    <p class="text-xs text-slate-400 truncate">Rekapitulasi data reservasi ruangan dan cetak laporan resmi</p>
                </div>
            </div>

            <!-- Tombol Cetak & Ekspor (Sejajar Rapi 1 Baris) -->
            <div class="no-print flex flex-wrap sm:flex-nowrap items-center gap-2 shrink-0 w-full sm:w-auto">
                <a href="{{ route('peminjaman-ruangan.export-excel', request()->all()) }}" 
                   class="inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-700 to-emerald-500 hover:from-emerald-600 hover:to-emerald-400 text-white font-bold px-3 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_12px_rgba(16,185,129,0.3)] border border-emerald-400/50 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V4.5m0 15h7.5m-7.5-15h17.25c.621 0 1.125.504 1.125 1.125M3.375 4.5A1.125 1.125 0 014.5 3.375h15A1.125 1.125 0 0120.625 4.5M3.375 4.5h7.5c.621 0 1.125.504 1.125 1.125m8.625-1.125v15m0 0a1.125 1.125 0 01-1.125 1.125m1.125-1.125h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V4.5m0 15h-7.5m7.5-15h-7.5" />
                    </svg>
                    Export Excel
                </a>

                <a href="{{ route('peminjaman-ruangan.export-csv', request()->all()) }}" 
                   class="inline-flex items-center gap-1.5 bg-gradient-to-r from-sky-700 to-cyan-600 hover:from-sky-600 hover:to-cyan-500 text-white font-bold px-3 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_12px_rgba(6,182,212,0.3)] border border-cyan-400/50 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3h7.5M8.25 6H7.5a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 007.5 21h9a2.25 2.25 0 002.25-2.25V9" />
                    </svg>
                    Export CSV
                </a>

                <button onclick="window.print()" 
                        class="inline-flex items-center gap-1.5 bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white font-bold px-3 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_12px_rgba(0,209,255,0.4)] border border-[#00D1FF]/50 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656" />
                    </svg>
                    Cetak / PDF
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .no-print, header, nav, .sidebar {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            .card-report {
                background: white !important;
                border: 1px solid #cbd5e1 !important;
                color: black !important;
                box-shadow: none !important;
            }
            table {
                color: black !important;
            }
            th {
                background: #f1f5f9 !important;
                color: black !important;
            }
            td {
                color: black !important;
                border-color: #cbd5e1 !important;
            }
        }
    </style>

    <div class="pt-6 pb-24 sm:pb-12 bg-black min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Print Header (Only appears on printed documents) -->
            <div class="hidden print-only border-b-2 border-black pb-4 mb-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('assets/Logo untuk Sidebar.png') }}" class="w-16 h-16 object-contain" alt="Logo SIPRUKA">
                    <div>
                        <h1 class="text-xl font-black uppercase">LAPORAN REKAPITULASI PEMINJAMAN RUANGAN</h1>
                        <p class="text-xs text-slate-600">Sistem Informasi Peminjaman Ruangan Kampus (SIPRUKA)</p>
                        <p class="text-xs text-slate-600">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB oleh {{ auth()->user()->nama }} ({{ auth()->user()->jabatan ?? 'Admin' }})</p>
                    </div>
                </div>
            </div>

            <!-- Filter Panel (Hidden in Print) -->
            <div class="no-print bg-[#050c18] border border-[#004B87] rounded-2xl p-6 shadow-[0_0_20px_rgba(0,209,255,0.08)] space-y-4">
                <div class="flex items-center justify-between border-b border-[#004B87]/50 pb-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#00E9FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                        Filter Data Peminjaman
                    </h3>

                    @if(request()->anyFilled(['dari_tanggal', 'sampai_tanggal', 'ruangan_id', 'status', 'jenis_peminjam']))
                        <a href="{{ route('peminjaman-ruangan.laporan') }}" class="text-xs text-red-400 hover:text-red-300">Reset Filter</a>
                    @endif
                </div>

                <form action="{{ route('peminjaman-ruangan.laporan') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#00E9FF] mb-1.5 uppercase">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                               class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#00E9FF]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#00E9FF] mb-1.5 uppercase">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                               class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#00E9FF]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#00E9FF] mb-1.5 uppercase">Ruangan</label>
                        <select name="ruangan_id" class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#00E9FF]">
                            <option value="">Semua Ruangan</option>
                            @foreach($ruanganList as $r)
                                <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#00E9FF] mb-1.5 uppercase">Status</label>
                        <select name="status" class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#00E9FF]">
                            <option value="">Semua Status</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-[#004B87] hover:bg-[#00D1FF] hover:text-black text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="card-report bg-[#050c18] border border-[#004B87] rounded-2xl p-4 shadow-[0_0_15px_rgba(0,209,255,0.05)]">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengajuan</p>
                    <p class="text-2xl font-black text-white mt-1">{{ $stats['total'] }}</p>
                </div>

                <div class="card-report bg-[#050c18] border border-[#004B87] rounded-2xl p-4 shadow-[0_0_15px_rgba(0,209,255,0.05)]">
                    <p class="text-[11px] font-bold text-[#00E9FF] uppercase tracking-wider">Disetujui / Selesai</p>
                    <p class="text-2xl font-black text-[#00D1FF] mt-1">{{ $stats['disetujui'] }}</p>
                </div>

                <div class="card-report bg-[#050c18] border border-[#004B87] rounded-2xl p-4 shadow-[0_0_15px_rgba(0,209,255,0.05)]">
                    <p class="text-[11px] font-bold text-amber-400 uppercase tracking-wider">Menunggu (Pending)</p>
                    <p class="text-2xl font-black text-amber-300 mt-1">{{ $stats['pending'] }}</p>
                </div>

                <div class="card-report bg-[#050c18] border border-[#004B87] rounded-2xl p-4 shadow-[0_0_15px_rgba(0,209,255,0.05)]">
                    <p class="text-[11px] font-bold text-red-400 uppercase tracking-wider">Ditolak</p>
                    <p class="text-2xl font-black text-red-400 mt-1">{{ $stats['ditolak'] }}</p>
                </div>

                <div class="card-report bg-[#050c18] border border-[#004B87] rounded-2xl p-4 shadow-[0_0_15px_rgba(0,209,255,0.05)] col-span-2 sm:col-span-1">
                    <p class="text-[11px] font-bold text-slate-300 uppercase tracking-wider">Total Jam Pakai</p>
                    <p class="text-2xl font-black text-cyan-bright font-mono mt-1">{{ $stats['total_jam'] }} <span class="text-xs text-slate-400">Jam</span></p>
                </div>
            </div>

            <!-- Tabel Data Laporan -->
            <div class="card-report bg-[#050c18] border border-[#004B87] rounded-2xl overflow-hidden shadow-[0_0_25px_rgba(0,209,255,0.08)]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-black border-b border-[#004B87] text-xs uppercase font-bold text-[#00E9FF]">
                            <tr>
                                <th class="px-4 py-3.5">No.</th>
                                <th class="px-4 py-3.5">ID / No. Surat</th>
                                <th class="px-4 py-3.5">Peminjam</th>
                                <th class="px-4 py-3.5">Ruangan</th>
                                <th class="px-4 py-3.5">Tanggal & Jam</th>
                                <th class="px-4 py-3.5">Durasi</th>
                                <th class="px-4 py-3.5">Keperluan</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5">Verifikator</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#004B87]/30">
                            @forelse($data as $index => $item)
                                <tr class="hover:bg-black/40 transition">
                                    <td class="px-4 py-3 font-mono text-xs">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">
                                        <span class="font-bold text-white">{{ $item->nomor_pengajuan }}</span>
                                        @if($item->nomor_surat_izin)
                                            <p class="text-[10px] text-[#00D1FF]">{{ $item->nomor_surat_izin }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-white text-xs">{{ $item->nama_peminjam }}</p>
                                        <p class="text-[11px] text-slate-400">
                                            {{ $item->user->nipd ?? '-' }} ({{ $item->user->prodi ?? '-' }})
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#00E9FF]">
                                        {{ $item->ruangan->nama_ruangan }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <p class="text-white">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono">
                                        {{ $item->durasi }} m
                                    </td>
                                    <td class="px-4 py-3 text-xs max-w-xs truncate" title="{{ $item->keperluan }}">
                                        {{ $item->keperluan }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($item->status === 'disetujui')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#00D1FF]/20 text-[#00E9FF] border border-[#00D1FF]/30">Disetujui</span>
                                        @elseif($item->status === 'pending')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Pending</span>
                                        @elseif($item->status === 'berlangsung')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30">Berlangsung</span>
                                        @elseif($item->status === 'selesai')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-700 text-slate-300">Selesai</span>
                                        @elseif($item->status === 'ditolak')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-500/30">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        {{ $item->admin->nama ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-slate-500 text-xs">
                                        Tidak ada data peminjaman yang sesuai dengan filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Print Signature Footer (Only in Print) -->
            <div class="hidden print-only pt-8 text-right">
                <p class="text-xs">Mengetahui,</p>
                <p class="text-xs font-bold">{{ auth()->user()->jabatan ?? 'Penanggung Jawab Sarana & Prasarana' }}</p>
                <div class="h-16"></div>
                <p class="text-xs font-bold underline">{{ auth()->user()->nama }}</p>
            </div>

        </div>
    </div>
</x-app-layout>
