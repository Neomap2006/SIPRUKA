<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppConfig;
use App\Models\SchedulerLog;
use App\Models\WhatsAppSetting;
use App\Models\User;
use App\Services\WhatsAppService;

class WAConfigController extends Controller
{
    /**
     * Tampilkan Halaman Konfigurasi WhatsApp (Khusus Admin)
     */
    public function index()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola WhatsApp Config.');
        }

        // Ambil data konfigurasi dari tabel AppConfigs
        $configs = [
            'WhatsApp_ApiKey'        => AppConfig::getValue('WhatsApp_ApiKey', ''),
            'WhatsApp_GatewayUrl'     => AppConfig::getValue('WhatsApp_GatewayUrl', 'https://api.fonnte.com/send'),
            'WhatsApp_AlertTemplate' => AppConfig::getValue('WhatsApp_AlertTemplate', "📌 *RINGKASAN PEMINJAMAN RUANGAN HARIAN*\nTanggal: {{tanggal}}\nTotal Agenda: {{total_peminjaman}}\n\n*Daftar Kegiatan:*\n{{daftar_kegiatan}}\n\nSIPRUKA System Notification"),
            'Scheduler_HourTarget'   => AppConfig::getValue('Scheduler_HourTarget', '08:00:00'),
            'WhatsApp_Recipients'    => AppConfig::getValue('WhatsApp_Recipients', ''),
        ];

        // Normalisasi daftar nomor aktif penerima
        $activeNumbers = array_filter(array_map('trim', explode(',', $configs['WhatsApp_Recipients'])));

        // 1. Ambil Pengguna dari database yang memiliki no_hp
        $users = User::select('id', 'nama', 'no_hp', 'role')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->get();

        $recipientsList = [];
        $existingPhones = [];

        foreach ($users as $user) {
            $phone = preg_replace('/[^0-9]/', '', $user->no_hp);
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }
            if (!empty($phone) && !in_array($phone, $existingPhones)) {
                $existingPhones[] = $phone;
                $recipientsList[] = [
                    'nama'     => $user->nama ?? 'User',
                    'no_hp'    => $phone,
                    'selected' => in_array($phone, $activeNumbers) || in_array($user->no_hp, $activeNumbers),
                ];
            }
        }

        // 2. Ambil Penerima Kustom dari AppConfig
        $customRecipientsJson = AppConfig::getValue('WhatsApp_Custom_Recipients', '[]');
        $customRecipients = json_decode($customRecipientsJson, true) ?: [];

        foreach ($customRecipients as $custom) {
            $phone = preg_replace('/[^0-9]/', '', $custom['no_hp'] ?? '');
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }
            if (!empty($phone) && !in_array($phone, $existingPhones)) {
                $existingPhones[] = $phone;
                $recipientsList[] = [
                    'nama'     => $custom['nama'] ?? 'Custom',
                    'no_hp'    => $phone,
                    'selected' => in_array($phone, $activeNumbers),
                ];
            }
        }

        // Model legacy untuk kompatibilitas
        $setting = WhatsAppSetting::firstOrCreate([], [
            'status' => 'belum_dikonfigurasi'
        ]);

        // Ambil riwayat log pengiriman dari tabel SchedulerLogs
        $logs = SchedulerLog::orderBy('executed_at', 'desc')->take(50)->get();

        return view('whatsapp.index', compact('configs', 'setting', 'logs', 'recipientsList'));
    }

    /**
     * Simpan Konfigurasi Parameter Gateway & Scheduler
     */
    public function saveConfig(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

       $request->validate([
            'WhatsApp_ApiKey'        => 'required|string',
            'WhatsApp_GatewayUrl'     => 'required|url',
            'Scheduler_HourTarget'   => 'required|string',
            'WhatsApp_AlertTemplate' => 'required|string',
        ], [
            'WhatsApp_ApiKey.required'        => 'API Key WhatsApp Fonnte wajib diisi.',
            'WhatsApp_GatewayUrl.required'     => 'Gateway URL Endpoint wajib diisi.',
            'WhatsApp_GatewayUrl.url'          => 'Gateway URL harus berupa format URL valid.',
            'Scheduler_HourTarget.required'   => 'Jam eksekusi scheduler wajib diisi.',
            'WhatsApp_AlertTemplate.required' => 'Template pesan alert wajib diisi.',
        ]);

        // Proses array penerima terpilih jika dikirim via checkboxes
        if ($request->has('selected_recipients') && is_array($request->selected_recipients)) {
            $recipientsStr = implode(',', array_filter(array_map('trim', $request->selected_recipients)));
        } else {
            $recipientsStr = trim($request->input('WhatsApp_Recipients', ''));
        }

        AppConfig::setValue('WhatsApp_ApiKey', trim($request->WhatsApp_ApiKey));
        AppConfig::setValue('WhatsApp_GatewayUrl', trim($request->WhatsApp_GatewayUrl));
        AppConfig::setValue('Scheduler_HourTarget', trim($request->Scheduler_HourTarget));
        AppConfig::setValue('WhatsApp_Recipients', $recipientsStr);
        AppConfig::setValue('WhatsApp_AlertTemplate', trim($request->WhatsApp_AlertTemplate));

        // Sync ke model legacy WhatsAppSetting
        $setting = WhatsAppSetting::firstOrCreate([]);
        $setting->access_token = trim($request->WhatsApp_ApiKey);
        $setting->status = 'terhubung';
        $setting->save();

        return redirect()->route('whatsapp-setting.index')
            ->with('success', '✓ Parameter Konfigurasi & Penerima WhatsApp berhasil disimpan!');
    }

    /**
     * Tambah Penerima Kustom Baru via Modal
     */
    public function addRecipient(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'required|string|min:10|max:20',
        ], [
            'nama.required'  => 'Nama penerima wajib diisi.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.min'      => 'Nomor WhatsApp minimal 10 digit.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->no_hp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Ambil daftar kustom yang ada
        $customJson = AppConfig::getValue('WhatsApp_Custom_Recipients', '[]');
        $customList = json_decode($customJson, true) ?: [];

        // Tambah penerima baru jika belum ada
        $exists = false;
        foreach ($customList as $item) {
            if (($item['no_hp'] ?? '') === $phone) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $customList[] = [
                'nama'  => trim($request->nama),
                'no_hp' => $phone,
            ];
            AppConfig::setValue('WhatsApp_Custom_Recipients', json_encode($customList, JSON_UNESCAPED_UNICODE));
        }

        // Otomatis tandai sebagai aktif di WhatsApp_Recipients
        $currentRecipientsStr = AppConfig::getValue('WhatsApp_Recipients', '');
        $currentNumbers = array_filter(array_map('trim', explode(',', $currentRecipientsStr)));
        if (!in_array($phone, $currentNumbers)) {
            $currentNumbers[] = $phone;
            AppConfig::setValue('WhatsApp_Recipients', implode(',', $currentNumbers));
        }

        return redirect()->route('whatsapp-setting.index')
            ->with('success', "✓ Penerima '{$request->nama}' ({$phone}) berhasil ditambahkan ke daftar!");
    }

    /**
     * Fitur Uji Coba Pengiriman Pesan (Test Send)
     */
    public function testSend(Request $request, WhatsAppService $waService)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $recipient = $request->input('TestPhone') ?? $request->input('recipient');
        $message   = $request->input('TestMessage') ?? $request->input('message');

        if (empty($recipient) || empty($message)) {
            return redirect()->back()->with('error', 'Nomor HP Target (TestPhone) dan Isi Pesan (TestMessage) wajib diisi.');
        }

        $result = $waService->sendMessage($recipient, $message, 'TEST');

        if ($result['success']) {
            return redirect()->route('whatsapp-setting.index')->with('success', $result['message']);
        }
        return redirect()->route('whatsapp-setting.index')->with('error', $result['message']);
    }

    /**
     * Uji Coba Koneksi ke Fonnte WA Gateway
     */
    public function testConnection(WhatsAppService $waService)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $result = $waService->testConnection();

        // Update status di WhatsAppSetting
        $setting = WhatsAppSetting::first();
        if ($setting) {
            $setting->update(['status' => $result['success'] ? 'terhubung' : 'tidak_terhubung']);
        }

        if ($result['success']) {
            return redirect()->route('whatsapp-setting.index')->with('success', $result['message']);
        }
        return redirect()->route('whatsapp-setting.index')->with('error', $result['message']);
    }

    /**
     * Alias method untuk sendTest
     */
    public function sendTest(Request $request, WhatsAppService $waService)
    {
        return $this->testSend($request, $waService);
    }
}
