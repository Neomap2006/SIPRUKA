<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nipd')) {
                $table->string('nipd')->nullable();
            }
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable();
            }
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable();
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->nullable();
            }
        });

        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_ruangan', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('peminjaman_ruangan', 'jenis_peminjam')) {
                $table->enum('jenis_peminjam', ['individu', 'berorganisasi'])->default('individu');
            }
            if (!Schema::hasColumn('peminjaman_ruangan', 'keperluan')) {
                $table->text('keperluan')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_ruangan', 'file_dokumen')) {
                $table->string('file_dokumen')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_ruangan', 'file_sp')) {
                $table->string('file_sp')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_ruangan', 'file_izin_digital')) {
                $table->string('file_izin_digital')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_ruangan', 'nomor_surat_izin')) {
                $table->string('nomor_surat_izin')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_ruangan', 'admin_id')) {
                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            }
            $cols = ['jenis_peminjam', 'keperluan', 'file_dokumen', 'file_sp', 'file_izin_digital', 'nomor_surat_izin'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('peminjaman_ruangan', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $cols = ['nipd', 'prodi', 'jabatan', 'no_hp'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
