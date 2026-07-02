<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-100 leading-tight">Tambah Peminjaman Ruangan</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 shadow-lg p-8">

                <a href="{{ route('peminjaman-ruangan.index') }}" 
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

                <form action="{{ route('peminjaman-ruangan.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Nama Peminjam --}}
                    <div>
                        <label for="nama_peminjam" class="block text-sm font-semibold text-slate-300 mb-2">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" id="nama_peminjam" 
                               class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition"
                               value="{{ old('nama_peminjam') }}" 
                               placeholder="Masukkan nama peminjam"
                               required>
                    </div>

                    {{-- Ruangan --}}
                    <div>
                        <label for="ruangan_id" class="block text-sm font-semibold text-slate-300 mb-2">Ruangan</label>
                        <select name="ruangan_id" id="ruangan_id" 
                                class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition appearance-none"
                                required>
                            <option value="" class="bg-slate-900 text-slate-400">-- Pilih Ruangan --</option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id }}" class="bg-slate-900 text-slate-200" {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal & waktu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-semibold text-slate-300 mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                   class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition" 
                                   value="{{ old('tanggal_mulai') }}" required>
                        </div>

                        <div>
                            <label for="waktu_mulai" class="block text-sm font-semibold text-slate-300 mb-2">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" 
                                   class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition" 
                                   value="{{ old('waktu_mulai') }}" required>
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-semibold text-slate-300 mb-2">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                   class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition" 
                                   value="{{ old('tanggal_selesai') }}" required>
                        </div>

                        <div>
                            <label for="waktu_selesai" class="block text-sm font-semibold text-slate-300 mb-2">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai" 
                                   class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition" 
                                   value="{{ old('waktu_selesai') }}" required>
                        </div>
                    </div>

                    {{-- Durasi --}}
                    <div>
                        <label for="durasi_display" class="block text-sm font-semibold text-slate-300 mb-2">Durasi (menit)</label>
                        <input id="durasi_display" 
                               class="w-full bg-slate-800/30 border border-slate-700/50 text-slate-400 cursor-not-allowed rounded-xl px-4 py-3 focus:outline-none" 
                               readonly 
                               value="{{ old('durasi') ? old('durasi').' menit' : '' }}">
                        <input type="hidden" id="durasi" name="durasi" value="{{ old('durasi') }}">
                    </div>

                    {{-- Keperluan --}}
                    <div>
                        <label for="keperluan" class="block text-sm font-semibold text-slate-300 mb-2">Keperluan</label>
                        <textarea name="keperluan" id="keperluan" 
                                  class="w-full bg-slate-800/60 border border-slate-700 text-slate-200 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition" 
                                  rows="3" 
                                  placeholder="Tuliskan keperluan penggunaan ruangan...">{{ old('keperluan') }}</textarea>
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

    <script>
    function hitungDurasi() {
        const tglMulai = document.querySelector("[name='tanggal_mulai']").value;
        const waktuMulai = document.querySelector("[name='waktu_mulai']").value;
        const tglSelesai = document.querySelector("[name='tanggal_selesai']").value;
        const waktuSelesai = document.querySelector("[name='waktu_selesai']").value;

        if (!tglMulai || !waktuMulai || !tglSelesai || !waktuSelesai) return;

        const start = new Date(tglMulai + 'T' + waktuMulai + ':00');
        const end   = new Date(tglSelesai + 'T' + waktuSelesai + ':00');

        const diffMin = Math.round((end - start) / 60000);

        if (!isNaN(diffMin) && diffMin >= 0) {
            document.getElementById('durasi_display').value = diffMin + ' menit';
            document.getElementById('durasi').value = diffMin;
        } else {
            document.getElementById('durasi_display').value = '';
            document.getElementById('durasi').value = '';
        }
    }

    document.querySelectorAll("[name='tanggal_mulai'], [name='waktu_mulai'], [name='tanggal_selesai'], [name='waktu_selesai']").forEach(el => {
        el.addEventListener('change', hitungDurasi);
        el.addEventListener('input', hitungDurasi);
    });

    // jalankan sekali kalau ada old() values
    window.addEventListener('load', hitungDurasi);
    </script>
</x-app-layout>
