<section class="space-y-6">
    <header class="border-b border-navy-deep pb-4">
        <h2 class="text-lg font-bold text-red-400">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-xs text-slate-400">
            {{ __('Setelah akun Anda dihapus, semua data dan riwayat peminjaman akan dihapus secara permanen. Harap berhati-hati.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun Saya') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-white">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="text-xs text-slate-400 leading-relaxed">
                {{ __('Setelah akun Anda dihapus, seluruh data yang berkaitan akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.') }}
            </p>

            <div class="mt-4" x-data="{ show: false }">
                <x-input-label for="password" value="{{ __('Kata Sandi') }}" class="sr-only" />

                <div class="relative mt-1">
                    <x-text-input
                        id="password"
                        name="password"
                        x-bind:type="show ? 'text' : 'password'"
                        class="block w-full pr-12"
                        placeholder="{{ __('Masukkan Kata Sandi Anda') }}"
                    />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-[#00D1FF] transition-colors focus:outline-none">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-navy-deep">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Hapus Akun Permanen') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
