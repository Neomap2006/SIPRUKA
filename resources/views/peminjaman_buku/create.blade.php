<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-100 leading-tight">Tambah Peminjaman Buku</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg p-8">

                <a href="{{ route('peminjaman-buku.index') }}" 
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-200 text-sm font-medium mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar Peminjaman
                </a>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl">
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

                <form action="{{ route('peminjaman-buku.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Buku --}}
                    <div>
                        <label for="buku_id" class="block text-sm font-semibold text-slate-300 mb-2">Buku</label>
                        <select name="buku_id" id="buku_id" 
                                class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition appearance-none"
                                required>
                            <option value="" class="bg-slate-900 text-slate-400">Pilih Buku</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->id }}" class="bg-slate-900 text-slate-200" {{ old('buku_id') == $b->id ? 'selected' : '' }}>{{ $b->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-semibold text-slate-300 mb-2">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" 
                               class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                               required>
                    </div>

                    {{-- Tanggal Kembali --}}
                    <div>
                        <label for="tanggal_kembali" class="block text-sm font-semibold text-slate-300 mb-2">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" 
                               class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition"
                               value="{{ old('tanggal_kembali') }}">
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-semibold text-sm transition shadow-lg shadow-indigo-600/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Simpan Peminjaman
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
