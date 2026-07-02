<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-100 leading-tight">Daftar Peminjaman Ruangan</h2>
    </x-slot>

    <div class="p-6">
        <div class="mb-4">
            <a href="{{ route('peminjaman-ruangan.create') }}" 
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl transition shadow-md shadow-indigo-600/10 font-semibold text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Peminjaman Ruangan
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-slate-800">
            <table class="w-full text-slate-300 border-collapse">
                <thead>
                    <tr class="bg-slate-900 border-b border-slate-800 text-center">
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4 text-center">Nama Peminjam</th>
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
                <tbody class="divide-y divide-slate-800 bg-slate-900/40">
                    @foreach ($data as $item)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="p-4 text-center font-medium text-slate-400">{{ $loop->iteration }}</td>
                        <td class="p-4 text-center font-semibold text-slate-200">{{ $item->nama_peminjam }}</td>
                        <td class="p-4 text-center text-slate-300">{{ $item->ruangan->nama_ruangan }}</td>
                        <td class="p-4 text-center text-slate-400">{{ $item->tanggal_mulai }}</td>
                        <td class="p-4 text-center text-slate-400">{{ $item->waktu_mulai }}</td>
                        <td class="p-4 text-center text-slate-400">{{ $item->tanggal_selesai }}</td>
                        <td class="p-4 text-center text-slate-400">{{ $item->waktu_selesai }}</td>
                        <td class="p-4 text-center text-slate-300">{{ $item->durasi }} menit</td>
                        <td class="p-4 text-center">
                            @if($item->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/25">
                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                                    Pending
                                </span>
                            @elseif($item->status === 'disetujui')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                    Disetujui
                                </span>
                            @elseif($item->status === 'ditolak')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/15 text-red-400 border border-red-500/25">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-500/15 text-slate-400 border border-slate-500/25 capitalize">
                                    {{ $item->status }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex gap-1.5 justify-center flex-wrap">
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    @if($item->status === 'pending')
                                        <form action="{{ route('peminjaman-ruangan.approve', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 hover:text-emerald-300 transition-colors border border-emerald-500/20" 
                                                    onclick="return confirm('Setujui peminjaman ruangan ini?')" title="Setujui">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('peminjaman-ruangan.reject', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors border border-red-500/20" 
                                                    onclick="return confirm('Tolak peminjaman ruangan ini?')" title="Tolak">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('peminjaman-ruangan.edit', $item->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 hover:text-blue-300 transition-colors border border-blue-500/20" 
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('peminjaman-ruangan.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors border border-red-500/20" 
                                                onclick="return confirm('Hapus data peminjaman ruangan ini?')" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-600 text-xs">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
