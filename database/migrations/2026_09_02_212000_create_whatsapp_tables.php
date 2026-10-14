<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Konfigurasi Meta WhatsApp Cloud API
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_version')->default('v22.0');
            $table->text('access_token')->nullable(); // Disimpan terenkripsi (Crypt::encryptString)
            $table->string('phone_number_id')->nullable();
            $table->string('business_account_id')->nullable();
            $table->string('status')->default('belum_dikonfigurasi'); // belum_dikonfigurasi, terhubung, tidak_terhubung, konfigurasi_tidak_valid
            $table->timestamps();
        });

        // 2. Tabel Template Pesan SIPRUKA
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // pengajuan_baru, pengajuan_disetujui, pengajuan_ditolak, pengingat_peminjaman, peminjaman_selesai
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        // 3. Tabel Pengaturan Scheduler Notifikasi
        Schema::create('whatsapp_schedulers', function (Blueprint $table) {
            $table->id();
            $table->json('recipient_user_ids')->nullable(); // Array ID User penerima
            $table->time('target_time')->default('08:00:00');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Tabel Log Notifikasi WhatsApp
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient');
            $table->string('type')->default('test'); // test, pengajuan_baru, disetujui, ditolak, pengingat, selesai
            $table->text('message');
            $table->string('status')->default('PENDING'); // SUCCESS, FAILED, PENDING
            $table->string('message_id')->nullable(); // wamid.xxx dari Meta Cloud API
            $table->text('detail')->nullable(); // Detail log atau pesan error aman
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
        Schema::dropIfExists('whatsapp_schedulers');
        Schema::dropIfExists('whatsapp_templates');
        Schema::dropIfExists('whatsapp_settings');
    }
};
