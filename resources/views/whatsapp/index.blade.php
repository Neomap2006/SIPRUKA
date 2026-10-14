<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-cyan-neon/10 text-cyan-bright flex items-center justify-center border border-cyan-neon/30 shrink-0 shadow-[0_0_15px_rgba(0,209,255,0.2)]">
                <svg class="w-7 h-7 fill-current text-cyan-bright" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-black text-2xl text-white tracking-tight leading-tight">Pengaturan WhatsApp Gateway</h2>
                    <span class="bg-cyan-neon/20 text-cyan-bright text-[10px] font-mono font-bold uppercase px-3 py-1 rounded-full border border-cyan-neon/40 shadow-[0_0_8px_rgba(0,209,255,0.2)]">Fonnte API Gateway</span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Konfigurasi API Key, Endpoint, Scheduler Harian, Test Send, dan Log Riwayat Pengiriman.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7">

            <!-- Flash Alerts -->
            @if (session('success'))
                <div class="p-4 bg-cyan-neon/10 text-cyan-bright border border-cyan-neon/30 rounded-xl flex items-center gap-3 shadow-[0_0_12px_rgba(0,209,255,0.15)]">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl flex items-center gap-3 shadow-lg">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $err)
                            <li class="text-xs flex items-center gap-2">
                                <span>•</span> {{ $err }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- MAIN FORM FOR CONFIGURATION -->
            <form action="{{ route('whatsapp-setting.save-config') }}" method="POST" id="configForm" class="space-y-7">
                @csrf

                <!-- SECTION 1: TOP CONFIGURATION CARDS (2-COLUMN GRID) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- CARD 1: WhatsApp API Key (Fonnte) -->
                    <div class="bg-cyber-card border border-navy-deep rounded-2xl p-6 shadow-neon space-y-4">
                        <div class="flex items-center justify-between border-b border-navy-deep pb-3">
                            <div>
                                <h3 class="text-sm font-bold text-white">WhatsApp API Key (Fonnte)</h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Masukkan kode akses WhatsApp dari penyedia layanan.</p>
                            </div>
                            <div>
                                @if(($setting->status ?? '') === 'terhubung')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-cyan-neon/15 text-cyan-bright border border-cyan-neon/40 shadow-[0_0_8px_rgba(0,209,255,0.2)]">
                                        <span class="w-2 h-2 bg-cyan-neon rounded-full animate-pulse"></span> Terhubung
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-700/50 text-slate-300 border border-slate-600">
                                        <span class="w-2 h-2 bg-slate-400 rounded-full"></span> Belum Diuji
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="password" id="WhatsApp_ApiKey_Input" name="WhatsApp_ApiKey" value="{{ old('WhatsApp_ApiKey', $configs['WhatsApp_ApiKey']) }}" placeholder="Masukkan Token API Fonnte..." required
                                    class="w-full bg-navy-deep/70 border border-slate-700 rounded-xl pl-4 pr-11 py-2.5 text-sm text-white focus:border-cyan-bright focus:ring-1 focus:ring-cyan-bright font-mono">
                                <button type="button" onclick="toggleApiKeyVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-cyan-bright focus:outline-none transition cursor-pointer" title="Tampilkan / Sembunyikan Token">
                                    <svg id="eyeIconShow" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg id="eyeIconHide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition shrink-0 shadow-lg shadow-blue-600/30">
                                Simpan
                            </button>
                        </div>
                    </div>

                    <!-- CARD 2: WhatsApp API URL -->
                    <div class="bg-cyber-card border border-navy-deep rounded-2xl p-6 shadow-neon space-y-4">
                        <div class="border-b border-navy-deep pb-3">
                            <h3 class="text-sm font-bold text-white">WhatsApp API URL</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Endpoint Fonnte untuk pengiriman pesan.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="url" name="WhatsApp_GatewayUrl" value="{{ old('WhatsApp_GatewayUrl', $configs['WhatsApp_GatewayUrl']) }}" required
                                class="w-full bg-navy-deep/70 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-cyan-bright focus:ring-1 focus:ring-cyan-bright font-mono">
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition shrink-0 shadow-lg shadow-blue-600/30">
                                Simpan
                            </button>
                        </div>
                    </div>

                </div>

                <!-- SECTION 2: PENGATURAN SCHEDULER (MAIN CARD) -->
                <div class="bg-cyber-card border border-navy-deep rounded-2xl p-6 sm:p-7 shadow-neon space-y-6 relative">

                    <div class="flex items-center justify-between border-b border-navy-deep pb-4">
                        <div>
                            <h3 class="text-base font-bold text-white">Pengaturan Scheduler</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Atur daftar penerima dan jam target kirim ringkasan harian.</p>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-blue-600/30">
                            Simpan
                        </button>
                    </div>

                    <!-- GRID: PENERIMA & DAFTAR PENERIMA (LEFT) + JAM TARGET (RIGHT) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- LEFT SIDE: PENERIMA WHATSAPP & DAFTAR PENERIMA (col-span-8) -->
                        <div class="lg:col-span-8 space-y-4">

                            <!-- FIELD: Penerima WhatsApp (Tags / Pills Container) -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-300">Penerima WhatsApp</label>
                                <div id="selectedTagsContainer" class="min-h-[46px] bg-navy-deep/80 border border-cyan-bright/50 rounded-xl px-3 py-2 flex flex-wrap items-center gap-2 shadow-[0_0_10px_rgba(0,209,255,0.1)]">
                                    <!-- Filled dynamically by JS -->
                                </div>
                            </div>

                            <!-- BOX: DAFTAR PENERIMA -->
                            <div class="border border-slate-700/80 rounded-xl overflow-hidden bg-navy-deep/40 shadow-md">
                                <!-- Box Header -->
                                <div class="bg-[#1e293b] px-4 py-3 flex items-center justify-between border-b border-slate-700">
                                    <h4 class="text-xs font-bold text-white tracking-wide">Daftar Penerima</h4>
                                    <button type="button" onclick="openAddRecipientModal()" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-bright hover:text-cyan-neon transition">
                                        <span>+</span> Tambah Penerima
                                    </button>
                                </div>

                                <!-- Box List Items -->
                                <div class="p-2 max-h-56 overflow-y-auto divide-y divide-slate-800/60 space-y-1">
                                    @forelse($recipientsList as $rec)
                                        <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-slate-800/40 cursor-pointer transition">
                                            <input type="checkbox" name="selected_recipients[]" value="{{ $rec['no_hp'] }}" data-nama="{{ $rec['nama'] }}"
                                                {{ $rec['selected'] ? 'checked' : '' }} onchange="updateSelectedPills()"
                                                class="w-4 h-4 text-blue-600 bg-navy-deep border-slate-700 rounded focus:ring-blue-500 focus:ring-offset-0 transition">
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="font-bold text-white">{{ $rec['nama'] }}</span>
                                                <span class="font-mono text-slate-400">{{ $rec['no_hp'] }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="p-4 text-center text-xs text-slate-500">
                                            Belum ada data penerima. Klik "+ Tambah Penerima" di atas.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Hidden field fallback for raw CSV values -->
                            <input type="hidden" name="WhatsApp_Recipients" id="WhatsApp_Recipients_Input" value="{{ $configs['WhatsApp_Recipients'] }}">

                        </div>

                        <!-- RIGHT SIDE: JAM TARGET SCHEDULER & TEMPLATE (col-span-4) -->
                        <div class="lg:col-span-4 space-y-4">
                            <!-- Field: Jam Target Scheduler -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-300">Jam Target Scheduler (format HH:mm:ss)</label>
                                <input type="text" name="Scheduler_HourTarget" value="{{ old('Scheduler_HourTarget', $configs['Scheduler_HourTarget']) }}" placeholder="08.00.00" required
                                    class="w-full bg-navy-deep/80 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-cyan-bright focus:ring-1 focus:ring-cyan-bright font-mono">
                                <p class="text-[11px] text-slate-400 leading-tight">Masukkan jam target scheduler dalam format <code class="text-cyan-bright">hh.mm.ss</code> atau <code class="text-cyan-bright">hh:mm:00</code>. Jika hanya jam, cukup tulis <code class="text-cyan-bright">08</code>.</p>
                            </div>

                            <!-- Field: Template Pesan -->
                            <div class="space-y-1.5 pt-2">
                                <label class="block text-xs font-bold text-slate-300">Template Pesan Ringkasan Harian</label>
                                <textarea name="WhatsApp_AlertTemplate" rows="4" required
                                    class="w-full bg-navy-deep/80 border border-slate-700 rounded-xl p-3 text-xs font-mono text-white focus:border-cyan-bright focus:ring-1 focus:ring-cyan-bright leading-relaxed">{{ old('WhatsApp_AlertTemplate', $configs['WhatsApp_AlertTemplate']) }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

            </form>

            <!-- SECTION 3: TEST SEND CARD -->
            <div class="bg-cyber-card border border-navy-deep rounded-2xl p-6 sm:p-7 shadow-neon space-y-4">
                <div class="flex items-center gap-3 border-b border-navy-deep pb-3">
                    <div class="w-8 h-8 rounded-lg bg-navy-deep/40 text-cyan-bright flex items-center justify-center border border-cyan-neon/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3 21l18-9L3 3l3 9zm0 0h75" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Uji Coba Pengiriman Pesan (Test Send)</h3>
                </div>

                <form action="{{ route('whatsapp-setting.send-test') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-300">Nomor HP Target</label>
                        <input type="text" name="TestPhone" placeholder="62812xxxxxxx" required
                            class="w-full bg-navy-deep/60 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-cyan-bright focus:ring-1 focus:ring-cyan-bright font-mono">
                        <p class="text-[11px] text-slate-400">Nomor dapat ditulis dalam format <code class="text-cyan-bright">628...</code> atau <code class="text-cyan-bright">08...</code>.</p>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-300">Isi Pesan Test</label>
                        <textarea name="TestMessage" rows="3" required placeholder="Tulis isi pesan test di sini..."
                            class="w-full bg-navy-deep/60 border border-slate-700 rounded-xl p-3 text-xs text-white focus:border-cyan-bright focus:ring-1 focus:ring-cyan-bright leading-relaxed"></textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-2 shadow-lg shadow-blue-600/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3 21l18-9L3 3l3 9zm0 0h75" />
                            </svg>
                            Kirim Test WA
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECTION 4: LOGGING & RIWAYAT PENGIRIMAN (SchedulerLogs) -->
            <div class="bg-cyber-card border border-navy-deep rounded-2xl p-6 sm:p-7 shadow-neon space-y-4">
                <div class="flex items-center justify-between border-b border-navy-deep pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-navy-deep/40 text-cyan-bright flex items-center justify-center border border-cyan-neon/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Log Notifikasi Terbaru</h3>
                            <p class="text-xs text-slate-400">Pencatatan riwayat status pengiriman pesan sukses/gagal secara otomatis.</p>
                        </div>
                    </div>
                    <button type="button" onclick="window.location.reload()" class="px-3 py-1.5 bg-navy-deep border border-slate-700 text-xs text-slate-300 hover:text-white rounded-lg transition">
                        Refresh
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-navy-deep">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-navy-deep/80 text-slate-400 uppercase font-mono text-[10px]">
                            <tr>
                                <th class="px-4 py-3">Waktu Eksekusi</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Penerima</th>
                                <th class="px-4 py-3">Pesan</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Detail Respon</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-navy-deep">
                            @forelse($logs as $log)
                                <tr class="hover:bg-navy-deep/30 transition">
                                    <td class="px-4 py-3 font-mono whitespace-nowrap text-slate-400">
                                        {{ $log->executed_at ? $log->executed_at->format('d/m/Y H:i:s') : $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 font-mono font-bold">
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ $log->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-cyan-bright font-semibold">
                                        {{ $log->recipient }}
                                    </td>
                                    <td class="px-4 py-3 max-w-xs truncate" title="{{ $log->message }}">
                                        {{ $log->message }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($log->status === 'SUCCESS')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-neon/15 text-cyan-bright border border-cyan-neon/40">
                                                SUCCESS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/15 text-red-400 border border-red-500/40">
                                                FAILED
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-[11px] text-slate-400 max-w-xs truncate" title="{{ $log->response_details }}">
                                        {{ $log->response_details ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                                        Belum ada riwayat pengiriman log pesan pada sistem.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL TAMBAH PENERIMA WHATSAPP (SCREENSHOT 2 POPUP) -->
    <div id="addRecipientModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 transition-all duration-200">
        <div class="bg-[#1e293b] border border-slate-700 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4 relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-700 pb-3">
                <h3 class="text-sm font-bold text-white">Tambah Penerima WhatsApp</h3>
                <button type="button" onclick="closeAddRecipientModal()" class="text-slate-400 hover:text-white transition text-lg font-bold">
                    ✕
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('whatsapp-setting.add-recipient') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300">Nama Penerima</label>
                    <input type="text" name="nama" placeholder="Nama Penerima" required
                        class="w-full bg-[#0f172a] border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300">Nomor WhatsApp</label>
                    <input type="text" name="no_hp" placeholder="628xxxxxxxxx" required
                        class="w-full bg-[#0f172a] border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                    <p class="text-[11px] text-slate-400 leading-tight">Nomor harus diawali <code class="text-cyan-bright">62</code>, hanya angka, minimal 10 digit.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-blue-600/30">
                        Simpan Penerima
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC FOR MODAL & TAGS -->
    <script>
        function toggleApiKeyVisibility() {
            const input = document.getElementById('WhatsApp_ApiKey_Input');
            const eyeShow = document.getElementById('eyeIconShow');
            const eyeHide = document.getElementById('eyeIconHide');
            if (!input || !eyeShow || !eyeHide) return;

            if (input.type === 'password') {
                input.type = 'text';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }

        function openAddRecipientModal() {
            document.getElementById('addRecipientModal').classList.remove('hidden');
        }

        function closeAddRecipientModal() {
            document.getElementById('addRecipientModal').classList.add('hidden');
        }

        function updateSelectedPills() {
            const container = document.getElementById('selectedTagsContainer');
            const checkboxes = document.querySelectorAll('input[name="selected_recipients[]"]');
            const recipientsInput = document.getElementById('WhatsApp_Recipients_Input');
            
            if (!container) return;

            container.innerHTML = '';
            const selectedNumbers = [];

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    selectedNumbers.push(cb.value);
                    const nama = cb.getAttribute('data-nama') || cb.value;

                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-700/80 text-slate-200 border border-slate-600 shadow-sm';
                    tag.innerHTML = `${nama} <button type="button" onclick="uncheckRecipient('${cb.value}')" class="text-slate-400 hover:text-white transition font-bold ml-1">✕</button>`;
                    container.appendChild(tag);
                }
            });

            if (selectedNumbers.length === 0) {
                container.innerHTML = '<span class="text-xs text-slate-500 italic">Belum ada penerima yang dipilih</span>';
            }

            if (recipientsInput) {
                recipientsInput.value = selectedNumbers.join(',');
            }
        }

        function uncheckRecipient(phone) {
            const checkboxes = document.querySelectorAll('input[name="selected_recipients[]"]');
            checkboxes.forEach(cb => {
                if (cb.value === phone) {
                    cb.checked = false;
                }
            });
            updateSelectedPills();
        }

        // Initialize tags on DOM load
        document.addEventListener('DOMContentLoaded', function () {
            updateSelectedPills();
        });
    </script>
</x-app-layout>
