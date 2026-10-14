<section>
    <header class="border-b border-navy-deep pb-4">
        <h2 class="text-lg font-bold text-white">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-xs text-slate-400">
            {{ __("Perbarui nama lengkap dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nama" :value="__('Nama Lengkap')" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1.5 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="email" :value="__('Alamat Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="no_hp" :value="__('No. WhatsApp / HP')" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1.5 block w-full" :value="old('no_hp', $user->no_hp)" placeholder="08123456789" />
                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
            </div>
        </div>

        @if($user->role === 'mahasiswa')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="nipd" :value="__('NIPD / NIM')" />
                    <x-text-input id="nipd" name="nipd" type="text" class="mt-1.5 block w-full" :value="old('nipd', $user->nipd)" placeholder="Nomor Induk Mahasiswa" />
                    <x-input-error class="mt-2" :messages="$errors->get('nipd')" />
                </div>

                <div>
                    <x-input-label for="prodi" :value="__('Program Studi')" />
                    <x-text-input id="prodi" name="prodi" type="text" class="mt-1.5 block w-full" :value="old('prodi', $user->prodi)" placeholder="Contoh: Teknik Informatika" />
                    <x-input-error class="mt-2" :messages="$errors->get('prodi')" />
                </div>
            </div>
        @elseif($user->role === 'admin')
            <div>
                <x-input-label for="jabatan" :value="__('Jabatan Struktural / Pengelola')" />
                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1.5 block w-full" :value="old('jabatan', $user->jabatan)" placeholder="Contoh: Kepala Biro Sarpras" />
                <x-input-error class="mt-2" :messages="$errors->get('jabatan')" />
            </div>
        @endif

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-slate-300">
                    {{ __('Alamat email Anda belum diverifikasi.') }}

                    <button form="send-verification" class="underline text-sm text-cyan-bright hover:text-cyan-neon focus:outline-none">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-xs text-cyan-bright">
                        {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-cyan-bright"
                >{{ __('Perubahan berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
