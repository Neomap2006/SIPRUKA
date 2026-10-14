<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="p-2 sm:p-2.5 rounded-xl bg-[#004B87]/30 border border-[#00D1FF]/30 text-[#00E9FF] shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-white tracking-tight leading-tight">Manajemen Pengguna</h2>
                    <p class="text-xs text-slate-400">Kelola data Mahasiswa (NIPD/Prodi) dan Admin (Jabatan)</p>
                </div>
            </div>

            <a href="{{ route('users.create') }}" 
               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#004B87] to-[#00D1FF] hover:to-[#00E9FF] text-black font-bold px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition shadow-[0_0_12px_rgba(0,209,255,0.3)] text-xs sm:text-sm shrink-0 whitespace-nowrap self-start md:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="pt-4 pb-24 sm:pb-12 bg-black min-h-screen">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            @if (session('success'))
                <div class="p-3.5 sm:p-4 bg-cyan-neon/10 text-cyan-bright border border-cyan-neon/30 rounded-xl flex items-center gap-2 shadow-[0_0_12px_rgba(0,209,255,0.15)] text-xs sm:text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="p-3.5 sm:p-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl flex items-center gap-2 text-xs sm:text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-[#050c18] border border-[#004B87] rounded-2xl shadow-[0_0_25px_rgba(0,209,255,0.08)] overflow-hidden">
                <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-[#004B87]">
                    <table class="w-full text-left text-sm text-slate-300 border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-black border-b border-[#004B87] text-xs uppercase tracking-wider text-[#00E9FF]">
                                <th class="py-4 px-4 text-center w-12 notranslate" translate="no">No.</th>
                                <th class="py-4 px-4">Nama & Identitas</th>
                                <th class="py-4 px-4">Email & Kontak</th>
                                <th class="py-4 px-4">Keterangan / Prodi</th>
                                <th class="py-4 px-4 text-center">Role</th>
                                <th class="py-4 px-4 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#004B87]/30 font-medium">
                            @foreach ($users as $user)
                            <tr class="hover:bg-[#004B87]/15 transition-colors">
                                <td class="py-4 px-4 text-center text-slate-400 font-mono text-xs">{{ $loop->iteration }}</td>
                                <td class="py-4 px-4 font-bold text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-[#004B87]/50 border border-[#00D1FF]/40 text-[#00E9FF] flex items-center justify-center font-bold text-sm shadow-[0_0_8px_rgba(0,209,255,0.2)] shrink-0">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="block">{{ $user->nama }}</span>
                                            @if($user->nipd)
                                                <span class="text-[11px] text-slate-400 font-mono font-normal">NIPD: {{ $user->nipd }}</span>
                                            @endif
                                            @if(auth()->id() === $user->id)
                                                <span class="text-[10px] text-[#00D1FF] font-mono ml-1">(Anda)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-xs">
                                    <p class="text-slate-200 font-mono">{{ $user->email }}</p>
                                    @if($user->no_hp)
                                        <p class="text-[11px] text-emerald-400 font-mono mt-0.5">{{ $user->no_hp }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-xs">
                                    @if($user->role === 'admin')
                                        <span class="text-slate-300 font-semibold">{{ $user->jabatan ?? 'Pengelola Ruangan' }}</span>
                                    @else
                                        <span class="text-slate-300">{{ $user->prodi ?? 'Mahasiswa Umum' }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#00D1FF]/15 text-[#00E9FF] border border-[#00D1FF]/30 shadow-[0_0_8px_rgba(0,209,255,0.2)]">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#004B87]/30 text-slate-300 border border-[#004B87]">
                                            Mahasiswa
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" 
                                           class="p-2 rounded-xl bg-[#004B87]/30 text-[#00E9FF] hover:bg-[#00D1FF] hover:text-black transition-colors border border-[#004B87]" 
                                           title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button class="p-2 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors border border-red-500/20" 
                                                        onclick="return confirm('Hapus data pengguna ini? Tindakan ini tidak dapat dibatalkan.')" title="Hapus Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
