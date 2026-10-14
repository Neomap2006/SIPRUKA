<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom status pada tabel peminjaman_ruangan menjadi string/varchar agar mendukung 'berlangsung', 'selesai', 'dibatalkan'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE peminjaman_ruangan MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('peminjaman_ruangan', function (Blueprint $table) {
                $table->string('status', 50)->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE peminjaman_ruangan MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('peminjaman_ruangan', function (Blueprint $table) {
                $table->string('status', 50)->default('pending')->change();
            });
        }
    }
};

