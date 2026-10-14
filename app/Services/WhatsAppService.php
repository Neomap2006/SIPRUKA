<?php

namespace App\Services;

use App\Models\AppConfig;
use App\Models\SchedulerLog;
use App\Models\WhatsAppLog;
use App\Models\WhatsAppSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Normalisasi nomor telepon ke format E.164 (misal: 08123456789 -> 628123456789)
     */
    public function normalizePhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Dapatkan API Key Fonnte dari AppConfig (atau fallback ke WhatsAppSetting)
     */
    public function getApiKey(): ?string
    {
        $apiKey = AppConfig::getValue('WhatsApp_ApiKey');
        if (!empty($apiKey)) {
            return trim($apiKey);
        }

        $setting = WhatsAppSetting::first();
        return $setting ? $setting->decrypted_access_token : null;
    }

    /**
     * Dapatkan Gateway URL Fonnte
     */
    public function getGatewayUrl(): string
    {
        return AppConfig::getValue('WhatsApp_GatewayUrl', 'https://api.fonnte.com/send');
    }

    /**
     * Test Koneksi Perangkat ke Fonnte WA Gateway API
     */
    public function testConnection(): array
    {
        $apiKey = $this->getApiKey();

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => '✕ Konfigurasi API Key Fonnte belum diisi. Harap masukkan WhatsApp_ApiKey.'
            ];
        }

        try {
            // Endpoint Fonnte Device Check
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->timeout(10)->post('https://api.fonnte.com/device');

            $resBody = $response->json();

            if ($response->successful() && isset($resBody['status']) && $resBody['status'] === true) {
                $deviceStatus = $resBody['device_status'] ?? 'CONNECT';
                $devicePhone  = $resBody['device'] ?? '';

                return [
                    'success' => true,
                    'message' => "✓ Terhubung dengan Fonnte WA Gateway (Nomor Device: {$devicePhone}, Status: {$deviceStatus})."
                ];
            } else {
                $errorMsg = $resBody['reason'] ?? $resBody['message'] ?? ('HTTP Status ' . $response->status());
                Log::error("Fonnte API Test Connection Error: " . json_encode($resBody));

                return [
                    'success' => false,
                    'message' => '✕ Koneksi Fonnte gagal: ' . $errorMsg
                ];
            }
        } catch (\Throwable $e) {
            Log::error("Fonnte Connection Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '✕ Gagal terhubung ke server Fonnte: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kirim Pesan Teks via Fonnte WA Gateway API
     */
    public function sendMessage(string $recipient, string $message, string $type = 'TEST'): array
    {
        $apiKey     = $this->getApiKey();
        $gatewayUrl = $this->getGatewayUrl();
        $formattedPhone = $this->normalizePhoneNumber($recipient);

        if (empty($apiKey)) {
            $this->logResult($type, $formattedPhone, $message, 'FAILED', 'API Key WhatsApp Fonnte belum diatur di sistem.');

            return [
                'success' => false,
                'message' => '✕ Gagal mengirim: API Key WhatsApp Fonnte belum diatur.'
            ];
        }

        $payload = [
            'target'      => $formattedPhone,
            'message'     => $message,
            'countryCode' => '62',
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->timeout(15)->post($gatewayUrl, $payload);

            $resBody = $response->json();

            if ($response->successful() && isset($resBody['status']) && $resBody['status'] === true) {
                $msgId = $resBody['id'][0] ?? null;
                $details = json_encode($resBody, JSON_UNESCAPED_UNICODE);

                $this->logResult($type, $formattedPhone, $message, 'SUCCESS', $details, $msgId);

                return [
                    'success' => true,
                    'message' => '✓ Pesan WhatsApp berhasil dikirim via Fonnte ke ' . $formattedPhone
                ];
            } else {
                $errorDetail = $resBody['reason'] ?? $resBody['message'] ?? ('HTTP Status ' . $response->status());
                $details = json_encode($resBody, JSON_UNESCAPED_UNICODE);

                $this->logResult($type, $formattedPhone, $message, 'FAILED', $details);
                Log::error("Fonnte WA Gateway Send Error: " . $details);

                return [
                    'success' => false,
                    'message' => '✕ Pesan WhatsApp gagal dikirim: ' . $errorDetail
                ];
            }
        } catch (\Throwable $e) {
            $this->logResult($type, $formattedPhone, $message, 'FAILED', 'System Exception: ' . $e->getMessage());
            Log::error("Fonnte WA Gateway Send Exception: " . $e->getMessage());

            return [
                'success' => false,
                'message' => '✕ Terjadi kesalahan koneksi saat memanggil Fonnte API.'
            ];
        }
    }

    /**
     * Pencatatan log ganda ke SchedulerLog & WhatsAppLog
     */
    protected function logResult(string $type, string $recipient, string $message, string $status, ?string $details = null, ?string $messageId = null): void
    {
        SchedulerLog::create([
            'type'             => strtoupper($type),
            'recipient'        => $recipient,
            'message'          => $message,
            'status'           => $status,
            'response_details' => $details,
            'executed_at'      => now(),
        ]);

        try {
            WhatsAppLog::create([
                'recipient'  => $recipient,
                'type'       => strtolower($type),
                'message'    => $message,
                'status'     => $status,
                'message_id' => $messageId,
                'detail'     => $details,
                'sent_at'    => now(),
            ]);
        } catch (\Throwable $e) {
            // Ignore if WhatsAppLog fails
        }
    }
}
