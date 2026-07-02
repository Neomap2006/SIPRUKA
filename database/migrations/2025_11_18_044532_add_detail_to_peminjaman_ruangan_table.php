<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            $table->string('nama_peminjam')->after('user_id');
            $table->time('waktu_mulai')->after('tanggal_mulai');
            $table->time('waktu_selesai')->after('tanggal_selesai');
            $table->integer('durasi')->nullable()->after('waktu_selesai'); // dalam menit
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->dropColumn(['nama_peminjam', 'waktu_mulai', 'waktu_selesai', 'durasi']);
        });
    }
};
