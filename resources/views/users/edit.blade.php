<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-navy-deep/40 border border-cyan-neon/30 text-cyan-bright">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight leading-tight">Edit Data Pengguna</h2>
                <p class="text-xs text-slate-400">Perbarui profil, hak akses, dan atribut peran</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-black min-h-screen" x-data="{ role: '{{ old('role', $user->role) }}' }">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#050c18] border border-[#004B87] rounded-2xl shadow-[0_0_30px_rgba(0,209,255,0.1)] p-4 sm:p-8 relative overflow-hidden">

                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-[#00E9FF] text-sm font-medium mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Manajemen Pengguna
                </a>
                
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required 
                               class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm font-medium" 
                               placeholder="Masukkan nama lengkap...">
                        @error('nama') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm font-medium" 
                                   placeholder="nama@domain.com">
                            @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">No. WhatsApp / HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" 
                                   class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm font-medium" 
                                   placeholder="08123456789">
                            @error('no_hp') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-2">Role Akses <span class="text-red-400">*</span></label>
                        <select name="role" x-model="role" required 
                                class="w-full bg-black/80 border border-[#004B87] text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm font-medium appearance-none">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }} class="bg-black text-white">Admin</option>
                            <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }} class="bg-black text-white">Mahasiswa</option>
                        </select>
                        @error('role') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Atribut Khusus Mahasiswa (Class Diagram: nipd, prodi) -->
                    <div x-show="role === 'mahasiswa'" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-xl bg-black/50 border border-[#004B87]/50" x-transition>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">NIPD / NIM</label>
                            <input type="text" name="nipd" value="{{ old('nipd', $user->nipd) }}" 
                                   class="w-full bg-black border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#00E9FF] focus:outline-none" 
                                   placeholder="Nomor Induk Mahasiswa">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Program Studi</label>
                            <input type="text" name="prodi" value="{{ old('prodi', $user->prodi) }}" 
                                   class="w-full bg-black border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#00E9FF] focus:outline-none" 
                                   placeholder="Contoh: Teknik Informatika">
                        </div>
                    </div>

                    <!-- Atribut Khusus Admin (Class Diagram: jabatan) -->
                    <div x-show="role === 'admin'" class="p-4 rounded-xl bg-black/50 border border-[#004B87]/50" x-transition>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Jabatan Struktural / Pengelola</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" 
                               class="w-full bg-black border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#00E9FF] focus:outline-none" 
                               placeholder="Contoh: Kepala Biro Sarana & Prasarana">
                    </div>

                    <div class="border-t border-[#004B87] pt-5 mt-4">
                        <h3 class="text-xs font-bold text-[#00E9FF] uppercase tracking-wider mb-3">Ubah Password <span class="text-slate-400 font-normal normal-case">(opsional, biarkan kosong bila tidak ingin mengganti)</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Password Baru</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" 
                                           class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-2.5 pr-12 focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm" 
                                           placeholder="Kosongkan bila tidak diganti">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00D1FF] focus:outline-none">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div x-data="{ show: false }">
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" 
                                           class="w-full bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-2.5 pr-12 focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition text-sm" 
                                           placeholder="Ulangi password...">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00D1FF] focus:outline-none">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t border-[#004B87]">
                        <a href="{{ route('users.index') }}" 
                           class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:text-slate-200 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-[0_0_15px_rgba(0,209,255,0.4)] border border-[#00D1FF]/50">
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
