<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_ruangan', 'catatan_peminjam')) {
                $table->text('catatan_peminjam')->nullable()->after('keperluan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_ruangan', 'catatan_peminjam')) {
                $table->dropColumn('catatan_peminjam');
            }
        });
    }
};
