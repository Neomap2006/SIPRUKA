<x-guest-layout>
    <div class="mb-5 text-sm text-slate-400 leading-relaxed">
        {{ __('Lupa kata sandi Anda? Masukkan alamat email terdaftar Anda beserta password baru untuk memperbarui akses akun Anda.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email or Username -->
        <div>
            <x-input-label for="login" :value="__('Email atau Username')" class="text-[#00E9FF] font-semibold text-xs uppercase tracking-wider" />
            <x-text-input id="login" class="block mt-1.5 w-full" type="text" name="login" :value="old('login')" placeholder="nama@domain.com / username" required autofocus />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password Baru -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password Baru')" class="text-[#00E9FF] font-semibold text-xs uppercase tracking-wider" />
            
            <div class="relative mt-1.5">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="block w-full pr-12 bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)] focus:shadow-[0_0_12px_rgba(0,209,255,0.3)]" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00D1FF] transition-colors focus:outline-none">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password Baru -->
        <div x-data="{ show: false }">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-[#00E9FF] font-semibold text-xs uppercase tracking-wider" />
            
            <div class="relative mt-1.5">
                <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Ulangi password baru"
                       class="block w-full pr-12 bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)] focus:shadow-[0_0_12px_rgba(0,209,255,0.3)]" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00D1FF] transition-colors focus:outline-none">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-cyan-bright transition-colors">
                ← Kembali ke Login
            </a>
            <x-primary-button>
                {{ __('Reset Password & Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
