<?php

namespace App\Services;

use App\Models\AppConfig;
use App\Models\PeminjamanRuangan;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DailySchedulerService
{
    /**
     * Jalankan eksekusi pengiriman notifikasi harian
     */
    public function runDailyJob(): int
    {
        $recipientsStr = AppConfig::getValue('WhatsApp_Recipients', '');
        $template      = AppConfig::getValue('WhatsApp_AlertTemplate', '');

        if (empty($recipientsStr)) {
            Log::info("DailySchedulerService Skipped: WhatsApp_Recipients belum diisi.");
            return 0;
        }

        if (empty($template)) {
            $template = "📌 *RINGKASAN PEMINJAMAN RUANGAN HARIAN*\nTanggal: {{tanggal}}\nTotal Agenda: {{total_peminjaman}}\n\n*Daftar Kegiatan:*\n{{daftar_kegiatan}}\n\nSIPRUKA System Notification";
        }

        $recipients = array_filter(array_map('trim', explode(',', $recipientsStr)));
        if (empty($recipients)) {
            return 0;
        }

        $today = Carbon::today()->format('Y-m-d');

        // Query peminjaman ruangan yang disetujui untuk hari ini
        $peminjamanHariIni = PeminjamanRuangan::where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->with(['user', 'ruangan'])
            ->get();

        $daftarKegiatan = "";
        if ($peminjamanHariIni->isEmpty()) {
            $daftarKegiatan = "Tidak ada agenda peminjaman ruangan untuk hari ini.";
        } else {
            foreach ($peminjamanHariIni as $index => $item) {
                $num = $index + 1;
                $namaUser = $item->user ? ($item->user->nama ?? $item->user->name) : 'Pengguna';
                $namaRuangan = $item->ruangan ? $item->ruangan->nama_ruangan : 'Ruangan';
                $jamMulai = substr($item->waktu_mulai ?? '08:00', 0, 5);
                $jamSelesai = substr($item->waktu_selesai ?? '17:00', 0, 5);

                $daftarKegiatan .= "{$num}. Ruangan: *{$namaRuangan}*\n";
                $daftarKegiatan .= "   Peminjam: {$namaUser}\n";
                $daftarKegiatan .= "   Waktu: {$jamMulai} - {$jamSelesai} WIB\n\n";
            }
        }

        // Replace placeholders
        $pesan = str_replace(
            ['{{tanggal}}', '{{total_peminjaman}}', '{{daftar_kegiatan}}'],
            [Carbon::today()->translatedFormat('l, d F Y'), $peminjamanHariIni->count(), trim($daftarKegiatan)],
            $template
        );

        $waService = new WhatsAppService();
        $sentCount = 0;

        foreach ($recipients as $target) {
            if (!empty($target)) {
                $res = $waService->sendMessage($target, $pesan, 'DAILY_SCHEDULER');
                if ($res['success']) {
                    $sentCount++;
                }
            }
        }

        return $sentCount;
    }
}
