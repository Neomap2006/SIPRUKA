<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-navy-deep/40 border border-cyan-neon/30 text-cyan-bright">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight leading-tight">Tambah Peminjaman Ruangan</h2>
                <p class="text-xs text-slate-400">Ajukan permohonan reservasi ruangan baru sesuai alur sistem</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-black min-h-screen" x-data="{ jenisPeminjam: '{{ old('jenis_peminjam', 'individu') }}' }">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#050c18] backdrop-blur-xl rounded-2xl border border-[#004B87] shadow-[0_0_30px_rgba(0,209,255,0.12)] p-4 sm:p-8">

                <a href="{{ route('peminjaman-ruangan.index') }}" 
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-[#00E9FF] text-sm font-semibold mb-6 transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar Peminjaman
                </a>

                @if (session('recommended_ruangan'))
                    <div class="mb-6 p-5 bg-[#004B87]/25 border border-[#00E9FF]/50 rounded-2xl shadow-[0_0_25px_rgba(0,233,255,0.2)]">
                        <div class="flex items-center gap-3 mb-2 text-[#00E9FF]">
                            <div class="p-2 rounded-lg bg-[#00E9FF]/10 text-[#00E9FF]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.516 0c.85.493 1.508 1.333 1.508 2.316V18" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm tracking-wide uppercase">Sistem Rekomendasi Ruangan Kosong</h4>
                                <p class="text-xs text-slate-300">Ruangan yang Anda pilih penuh. Berikut adalah pilihan ruangan alternatif yang <span class="text-[#00E9FF] font-semibold">kosong</span> pada jam tersebut:</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                            @foreach (session('recommended_ruangan') as $rec)
                                <button type="button" 
                                        onclick="selectRecommendedRuangan('{{ $rec->id }}', this)"
                                        class="recommended-card flex items-center justify-between p-3.5 bg-black/80 hover:bg-[#004B87]/50 border border-[#004B87] hover:border-[#00E9FF] rounded-xl text-left transition-all duration-200 group shadow-md cursor-pointer">
                                    <div>
                                        <span class="block font-bold text-sm text-white group-hover:text-[#00E9FF] transition">{{ $rec->nama_ruangan }}</span>
                                        <span class="text-xs text-slate-400">Kapasitas: {{ $rec->kapasitas }} org | Lokasi: {{ $rec->lokasi ?? '-' }}</span>
                                    </div>
                                    <span class="pilih-badge text-xs font-bold px-3 py-1.5 bg-[#00E9FF]/10 text-[#00E9FF] border border-[#00E9FF]/40 rounded-lg group-hover:bg-[#00E9FF] group-hover:text-black transition shrink-0">
                                        Pilih
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <script>
                        function selectRecommendedRuangan(ruanganId, element) {
                            const selectElem = document.getElementById('ruangan_id');
                            if (selectElem) {
                                selectElem.value = ruanganId;
                                selectElem.dispatchEvent(new Event('change'));
                                selectElem.classList.add('ring-4', 'ring-[#00E9FF]', 'border-[#00E9FF]');
                                setTimeout(() => {
                                    selectElem.classList.remove('ring-4', 'ring-[#00E9FF]', 'border-[#00E9FF]');
                                }, 2000);
                                selectElem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }

                            document.querySelectorAll('.recommended-card').forEach(card => {
                                card.classList.remove('border-[#00E9FF]', 'bg-[#004B87]/50', 'ring-2', 'ring-[#00E9FF]');
                                card.classList.add('border-[#004B87]', 'bg-black/80');
                                const badge = card.querySelector('.pilih-badge');
                                if (badge) {
                                    badge.textContent = 'Pilih';
                                    badge.className = 'pilih-badge text-xs font-bold px-3 py-1.5 bg-[#00E9FF]/10 text-[#00E9FF] border border-[#00E9FF]/40 rounded-lg group-hover:bg-[#00E9FF] group-hover:text-black transition shrink-0';
                                }
                            });

                            if (element) {
                                element.classList.remove('border-[#004B87]', 'bg-black/80');
                                element.classList.add('border-[#00E9FF]', 'bg-[#004B87]/50', 'ring-2', 'ring-[#00E9FF]');
                                const badge = element.querySelector('.pilih-badge');
                                if (badge) {
                                    badge.textContent = '✓ Terpilih';
                                    badge.className = 'pilih-badge text-xs font-bold px-3 py-1.5 bg-[#00E9FF] text-black border border-[#00E9FF] rounded-lg transition shrink-0 shadow-[0_0_10px_rgba(0,233,255,0.5)]';
                                }
                            }
                        }
                    </script>
                @elseif (session('no_recommendation'))
                    <div class="mb-6 p-4 bg-amber-500/10 text-amber-300 border border-amber-500/30 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <span class="text-xs font-medium">Sistem: Tidak ada ruangan alternatif lain yang tersedia pada rentang jam ini. Silakan pilih tanggal atau jam yang berbeda.</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl shadow-[0_0_15px_rgba(239,68,68,0.15)]">
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

                <form action="{{ route('peminjaman-ruangan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" data-auto-draft="peminjaman_create">
                    @csrf

                    {{-- Nama Peminjam & Jenis Peminjam --}}
                    <div>
                        <label for="nama_peminjam" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Nama Peminjam / Penanggung Jawab</label>
                        <input type="text" name="nama_peminjam" id="nama_peminjam" 
                               class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]"
                               value="{{ old('nama_peminjam', auth()->user()->nama ?? '') }}" 
                               placeholder="Masukkan nama peminjam"
                               required>
                    </div>

                    {{-- Jenis Peminjam Selection --}}
                    <div>
                        <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Jenis Peminjam</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label :class="jenisPeminjam === 'individu' ? 'border-[#00D1FF] bg-[#004B87]/30 text-white ring-2 ring-[#00D1FF]/40' : 'border-[#004B87]/50 bg-black/50 text-slate-400 hover:border-slate-500'" 
                                   class="relative flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all duration-200">
                                <input type="radio" name="jenis_peminjam" value="individu" x-model="jenisPeminjam" class="text-[#00D1FF] focus:ring-[#00D1FF] bg-black border-slate-700">
                                <div>
                                    <p class="text-sm font-bold">Individu / Mahasiswa</p>
                                    <p class="text-[11px] text-slate-400">Wajib upload NIPD/KTM</p>
                                </div>
                            </label>

                            <label :class="jenisPeminjam === 'berorganisasi' ? 'border-[#00D1FF] bg-[#004B87]/30 text-white ring-2 ring-[#00D1FF]/40' : 'border-[#004B87]/50 bg-black/50 text-slate-400 hover:border-slate-500'" 
                                   class="relative flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all duration-200">
                                <input type="radio" name="jenis_peminjam" value="berorganisasi" x-model="jenisPeminjam" class="text-[#00D1FF] focus:ring-[#00D1FF] bg-black border-slate-700">
                                <div>
                                    <p class="text-sm font-bold">Organisasi / UKM</p>
                                    <p class="text-[11px] text-slate-400">Wajib NIPD & Surat Permohonan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Ruangan --}}
                    <div>
                        <label for="ruangan_id" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Ruangan</label>
                        <select name="ruangan_id" id="ruangan_id" 
                                class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition appearance-none"
                                required>
                            <option value="" class="bg-black text-slate-500">-- Pilih Ruangan --</option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id }}" class="bg-black text-slate-200" {{ (old('ruangan_id') ?? request('ruangan_id')) == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }} (Kapasitas: {{ $r->kapasitas }} Orang)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal & waktu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition" 
                                   value="{{ old('tanggal_mulai') }}" required>
                        </div>

                        <div>
                            <label for="waktu_mulai" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition" 
                                   value="{{ old('waktu_mulai') }}" required>
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition" 
                                   value="{{ old('tanggal_selesai') }}" required>
                        </div>

                        <div>
                            <label for="waktu_selesai" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai" 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition" 
                                   value="{{ old('waktu_selesai') }}" required>
                        </div>
                    </div>

                    {{-- Durasi --}}
                    <div>
                        <label for="durasi_display" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Kalkulasi Durasi</label>
                        <input id="durasi_display" 
                               class="w-full bg-black/40 border border-[#004B87]/50 text-[#00D1FF] font-mono cursor-not-allowed rounded-xl px-4 py-3 focus:outline-none" 
                               readonly 
                               value="{{ old('durasi') ? old('durasi').' menit' : '' }}">
                        <input type="hidden" id="durasi" name="durasi" value="{{ old('durasi') }}">
                    </div>

                    {{-- Keperluan --}}
                    <div>
                        <label for="keperluan" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Keperluan / Agenda Peminjaman</label>
                        <textarea name="keperluan" id="keperluan" 
                                  class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition" 
                                  rows="3" 
                                  placeholder="Tuliskan detail agenda penggunaan ruangan..." required>{{ old('keperluan') }}</textarea>
                    </div>

                    {{-- Pesan / Catatan Khusus untuk Admin (Kirim via WhatsApp) --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="catatan_peminjam" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider">
                                Pesan / Catatan Khusus untuk Admin <span class="text-slate-500 font-normal lowercase">(opsional)</span>
                            </label>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#00D1FF] bg-[#004B87]/30 border border-[#004B87] px-2 py-0.5 rounded-full">
                                💬 Dikirim via WhatsApp
                            </span>
                        </div>
                        <textarea name="catatan_peminjam" id="catatan_peminjam" 
                                  class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition" 
                                  rows="2" 
                                  placeholder="Tuliskan pesan atau koordinasi khusus untuk Admin (misal: mohon bantuan alat/proyektor disiapkan awal)...">{{ old('catatan_peminjam') }}</textarea>
                    </div>

                    {{-- Upload Dokumen Persyaratan (Sesuai Diagram) --}}
                    <div class="space-y-4 pt-2 border-t border-[#004B87]/40">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#00D1FF] shadow-[0_0_8px_#00D1FF]"></span>
                            <h3 class="text-sm font-bold text-white tracking-wide">Unggah Berkas Persyaratan</h3>
                        </div>

                        {{-- Dokumen Identitas / NIPD --}}
                        <div>
                            <label for="file_dokumen" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-1.5">
                                Kartu Identitas / NIPD (KTM) <span class="text-red-400">*</span>
                            </label>
                            <p class="text-[11px] text-slate-400 mb-2">Format: PDF, JPG, JPEG, PNG (Maks. 5MB)</p>
                            <input type="file" name="file_dokumen" id="file_dokumen" 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-300 text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#004B87] file:text-white hover:file:bg-[#00D1FF] hover:file:text-black rounded-xl p-2 focus:outline-none focus:border-[#00E9FF] transition"
                                   required>
                        </div>

                        {{-- Surat Permohonan (Wajib jika Berorganisasi) --}}
                        <div x-show="jenisPeminjam === 'berorganisasi'" x-transition class="pt-2">
                            <label for="file_sp" class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-1.5">
                                Surat Permohonan Resmi (SP) <span class="text-red-400">*</span>
                            </label>
                            <p class="text-[11px] text-slate-400 mb-2">Surat pengantar permohonan peminjaman bertanda tangan ketua organisasi/pembina (PDF/JPG, Maks. 5MB)</p>
                            <input type="file" name="file_sp" id="file_sp" 
                                   :required="jenisPeminjam === 'berorganisasi'"
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-300 text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#004B87] file:text-white hover:file:bg-[#00D1FF] hover:file:text-black rounded-xl p-2 focus:outline-none focus:border-[#00E9FF] transition">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white py-3.5 rounded-xl font-bold text-sm transition-all shadow-[0_0_20px_rgba(0,209,255,0.35)] hover:shadow-[0_0_30px_rgba(0,209,255,0.65)] border border-[#00D1FF]/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Kirim Pengajuan Peminjaman
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

    window.addEventListener('load', hitungDurasi);
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if($errors->has('overlap_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Jadwal Bentrok!',
            text: '{{ $errors->first('overlap_error') }}',
            confirmButtonColor: '#00D1FF',
            background: '#050c18',
            color: '#f1f5f9'
        })
    </script>
    @endif
</x-app-layout>
