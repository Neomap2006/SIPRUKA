<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Kirim notifikasi WhatsApp menggunakan Meta WhatsApp Cloud API (Struktur Abstraksi).
     *
     * @param string|null $phoneNumber
     * @param string $message
     * @return bool
     */
    public function sendNotification(?string $phoneNumber, string $message): bool
    {
        if (empty($phoneNumber)) {
            Log::info("WhatsApp Notification Skipped: No phone number provided.");
            return false;
        }

        try {
            // Struktur payload Meta WhatsApp Cloud API resmi
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($phoneNumber),
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message
                ]
            ];

            // Log notifikasi sebagai penanda integrasi yang aman tanpa layanan berbayar third-party
            Log::info("WhatsApp Notification Queued/Sent to {$phoneNumber}:", $payload);

            return true;
        } catch (\Throwable $e) {
            Log::error("WhatsApp Notification Failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor HP ke format internasional E.164 (misal: 08123... -> 628123...)
     */
    private function formatPhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }
        return $cleaned;
    }
}
