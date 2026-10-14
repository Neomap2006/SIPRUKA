<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppConfig;
use App\Services\DailySchedulerService;

class RunDailyWhatsAppScheduler extends Command
{
    /**
     * Nama dan signature dari artisan command
     */
    protected $signature = 'sipruka:wa-daily-scheduler';

    /**
     * Deskripsi artisan command
     */
    protected $description = 'Menjalankan scheduler notifikasi harian WhatsApp via Fonnte Gateway';

    /**
     * Eksekusi command
     */
    public function handle(DailySchedulerService $schedulerService): int
    {
        $targetTime = AppConfig::getValue('Scheduler_HourTarget', '08:00:00');
        $currentTime = now()->format('H:i:00');

        $this->info("Menjalankan pengecekan WhatsApp Daily Scheduler (Jam Target: {$targetTime}, Jam Saat Ini: {$currentTime})...");

        // Periksa apakah jam saat ini (H:i) cocok dengan jam target
        if (substr($currentTime, 0, 5) === substr($targetTime, 0, 5)) {
            $sentCount = $schedulerService->runDailyJob();
            $this->info("✓ Daily WhatsApp Scheduler berhasil dieksekusi. Pesan terkirim ke {$sentCount} penerima.");
            return Command::SUCCESS;
        }

        $this->info("Pengiriman dilewati karena jam saat ini tidak cocok dengan jam target.");
        return Command::SUCCESS;
    }
}
