<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-navy-deep/40 border border-cyan-neon/30 text-cyan-bright">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight leading-tight">Edit Ruangan</h2>
                <p class="text-xs text-slate-400">Perbarui spesifikasi dan data ruangan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-cyber-card border border-navy-deep rounded-2xl shadow-neon p-4 sm:p-8 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-neon/5 rounded-full blur-3xl pointer-events-none"></div>

                <a href="{{ route('ruangan.index') }}" 
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-cyan-bright text-sm font-medium mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar Ruangan
                </a>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm flex items-start gap-2">
                                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('ruangan.update', $ruangan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nama_ruangan" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Ruangan <span class="text-cyan-neon">*</span></label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan" 
                               class="w-full bg-cyber-black border border-navy-deep text-slate-200 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-neon focus:ring-1 focus:ring-cyan-neon transition text-sm font-medium"
                               value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" 
                               placeholder="Contoh: Lab Komputer A"
                               required>
                    </div>

                    <div>
                        <label for="lokasi" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Lokasi / Gedung</label>
                        <input type="text" name="lokasi" id="lokasi" 
                               class="w-full bg-cyber-black border border-navy-deep text-slate-200 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-neon focus:ring-1 focus:ring-cyan-neon transition text-sm font-medium"
                               value="{{ old('lokasi', $ruangan->lokasi) }}" 
                               placeholder="Contoh: Gedung Rektorat Lt. 2">
                    </div>

                    <div>
                        <label for="kapasitas" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kapasitas (orang) <span class="text-cyan-neon">*</span></label>
                        <input type="number" name="kapasitas" id="kapasitas" 
                               class="w-full bg-cyber-black border border-navy-deep text-slate-200 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-neon focus:ring-1 focus:ring-cyan-neon transition text-sm font-medium"
                               value="{{ old('kapasitas', $ruangan->kapasitas) }}" 
                               placeholder="Contoh: 30"
                               min="1"
                               required>
                    </div>

                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t border-navy-deep">
                        <a href="{{ route('ruangan.index') }}" 
                           class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:text-slate-200 hover:bg-navy-deep/30 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-navy-deep to-cyan-neon hover:to-cyan-bright text-black font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-neon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
