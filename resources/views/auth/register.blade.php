<x-guest-layout>
    <div class="w-full bg-[#050c18] rounded-3xl shadow-[0_0_50px_rgba(0,209,255,0.25)] border border-[#004B87]/80 overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[550px]">
        
        <!-- Left Side: Animated Cyber Tech Background & Branding Panel -->
        <div class="relative p-8 sm:p-10 flex flex-col justify-between items-center text-center overflow-hidden min-h-[380px] md:min-h-full border-b md:border-b-0 md:border-r border-[#004B87]/60">
            
            <!-- Animated Tech Image Background -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat animate-bg-pan opacity-75 filter brightness-95 contrast-105"
                 style="background-image: url('{{ asset('assets/login_bg.png') }}');">
            </div>

            <!-- Gradient Overlay for Legibility -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#050c18]/85 via-[#002647]/75 to-[#004B87]/90"></div>

            <!-- Laser Scanline inside Left Panel -->
            <div class="absolute inset-x-0 h-32 bg-gradient-to-b from-transparent via-[#00D1FF]/20 to-transparent animate-scanline pointer-events-none"></div>

            <div></div> <!-- Top spacer -->

            <!-- Center Content (Logo & Title) -->
            <div class="flex flex-col items-center justify-center z-10 my-auto">
                <!-- Glassmorphism Logo Box matching Logo Shape -->
                <div class="w-40 h-40 p-2 rounded-2xl bg-[#050c18]/80 backdrop-blur-xl border border-[#00D1FF]/40 shadow-[0_0_30px_rgba(0,209,255,0.3)] flex items-center justify-center mb-6 group transition-all duration-300 hover:border-[#00E9FF] hover:shadow-[0_0_45px_rgba(0,209,255,0.6)]">
                    <x-application-logo class="w-full h-full object-contain animate-float" />
                </div>

                <h1 class="text-3xl sm:text-4xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#00E9FF] via-[#00D1FF] to-white drop-shadow-[0_0_15px_rgba(0,209,255,0.5)]">SIPRUKA</h1>
                <p class="text-xs text-slate-200 font-semibold tracking-wide mt-2 max-w-xs drop-shadow text-center">
                    Sistem Peminjaman Ruangan Kampus
                </p>
                <p class="text-[11px] text-slate-400 font-medium tracking-wide mt-1 max-w-xs text-center">
                    Kelola Peminjaman Ruangan Kampus dengan Mudah
                </p>
            </div>

            <!-- Bottom Pill Badges -->
            <div class="flex flex-wrap items-center justify-center gap-2 z-10 mt-6 pt-4">
                <span class="px-3.5 py-1.5 rounded-full bg-[#050c18]/90 backdrop-blur-md border border-[#00D1FF]/40 text-[#00E9FF] text-[11px] font-semibold flex items-center gap-1.5 shadow-[0_0_12px_rgba(0,209,255,0.2)]">
                    <svg class="w-3.5 h-3.5 text-[#00D1FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Booking Mudah
                </span>
                <span class="px-3.5 py-1.5 rounded-full bg-[#050c18]/90 backdrop-blur-md border border-[#00D1FF]/40 text-[#00E9FF] text-[11px] font-semibold flex items-center gap-1.5 shadow-[0_0_12px_rgba(0,209,255,0.2)]">
                    <svg class="w-3.5 h-3.5 text-[#00D1FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Multi Pengguna
                </span>
                <span class="px-3.5 py-1.5 rounded-full bg-[#050c18]/90 backdrop-blur-md border border-[#00D1FF]/40 text-[#00E9FF] text-[11px] font-semibold flex items-center gap-1.5 shadow-[0_0_12px_rgba(0,209,255,0.2)]">
                    <svg class="w-3.5 h-3.5 text-[#00D1FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Real-Time
                </span>
            </div>
        </div>

        <!-- Right Side: Dark Register Form Panel -->
        <div class="p-8 sm:p-10 md:p-12 flex flex-col justify-between bg-[#050c18]/95 backdrop-blur-xl">
            <div>
                <!-- Top Header Tag -->
                <div class="text-[11px] font-bold text-[#00E9FF] uppercase tracking-widest flex items-center gap-1.5 mb-2">
                    <span class="w-2 h-2 rounded-full bg-[#00D1FF] animate-pulse shadow-[0_0_6px_#00D1FF]"></span>
                    REGISTER
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Create Your Account</h2>
                <p class="text-xs text-slate-400 mt-1 mb-6">Lengkapi data di bawah ini untuk membuat akun baru</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nama Lengkap Input -->
                    <div>
                        <label for="nama" class="block text-[11px] font-bold text-[#00E9FF] uppercase tracking-wider mb-1.5">NAMA LENGKAP</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input id="nama" type="text" name="nama" :value="old('nama')" required autofocus autocomplete="name"
                                   placeholder="Nama lengkap Anda"
                                   class="w-full pl-11 pr-4 py-2.5 bg-black/80 border border-[#004B87] rounded-xl text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" />
                        </div>
                        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-[11px] font-bold text-[#00E9FF] uppercase tracking-wider mb-1.5">EMAIL KAMPUS / PRIBADI</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                                   placeholder="email@kampus.ac.id"
                                   class="w-full pl-11 pr-4 py-2.5 bg-black/80 border border-[#004B87] rounded-xl text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password Input with Toggle -->
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-[11px] font-bold text-[#00E9FF] uppercase tracking-wider mb-1.5">PASSWORD</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full pl-11 pr-11 py-2.5 bg-black/80 border border-[#004B87] rounded-xl text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00E9FF] transition-colors focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password Input with Toggle -->
                    <div x-data="{ show: false }">
                        <label for="password_confirmation" class="block text-[11px] font-bold text-[#00E9FF] uppercase tracking-wider mb-1.5">KONFIRMASI PASSWORD</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Ulangi password"
                                   class="w-full pl-11 pr-11 py-2.5 bg-black/80 border border-[#004B87] rounded-xl text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-[#00E9FF] focus:ring-1 focus:ring-[#00D1FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00E9FF] transition-colors focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Register Submit Button -->
                    <div class="pt-3">
                        <button type="submit" class="w-full py-3.5 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-[#004B87] via-[#008ba8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] shadow-[0_0_20px_rgba(0,209,255,0.35)] transition-all duration-300 flex items-center justify-center gap-2 group cursor-pointer text-sm">
                            <svg class="w-4 h-4 text-white group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            <span>Daftar Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Help / Login text -->
            <div class="text-center pt-6 border-t border-[#004B87]/40 mt-4 flex items-center justify-center gap-1.5 text-slate-400 text-xs">
                <span>Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="text-[#00E9FF] hover:underline font-bold">Masuk di sini</a>
            </div>
        </div>

    </div>
</x-guest-layout>
