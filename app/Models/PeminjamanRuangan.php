<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    protected $table = 'peminjaman_ruangan';

    protected $fillable = [
        'user_id',
        'admin_id',
        'nama_peminjam',
        'jenis_peminjam',
        'ruangan_id',
        'tanggal_mulai',
        'waktu_mulai',
        'tanggal_selesai',
        'waktu_selesai',
        'durasi',
        'keperluan',
        'catatan_peminjam',
        'file_dokumen',
        'file_sp',
        'file_izin_digital',
        'nomor_surat_izin',
        'status',
        'alasan_penolakan',
        'notified_completed_at'
    ];

    /**
     * Accessor untuk nomor pengajuan publik (Contoh: PR-2026-0001)
     */
    public function getNomorPengajuanAttribute(): string
    {
        $year = $this->created_at ? $this->created_at->format('Y') : date('Y');
        return 'PR-' . $year . '-' . str_pad((string)$this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Terbitkan Izin Digital resmi saat pengajuan disetujui (Class Diagram / Sequence Diagram)
     */
    public function terbitkanIzinDigital(?User $admin = null): string
    {
        $year = $this->created_at ? $this->created_at->format('Y') : date('Y');
        $month = $this->created_at ? $this->created_at->format('m') : date('m');
        $nomorSurat = 'SIP/' . $year . '/' . $month . '/' . str_pad((string)$this->id, 4, '0', STR_PAD_LEFT);
        
        $this->update([
            'status' => 'disetujui',
            'admin_id' => $admin ? $admin->id : auth()->id(),
            'nomor_surat_izin' => $nomorSurat,
            'file_izin_digital' => 'izin_digital_' . $this->id . '_' . time() . '.pdf',
            'alasan_penolakan' => null,
        ]);

        return $nomorSurat;
    }

    /**
     * Hitung status riil secara dinamis berdasarkan waktu saat ini (Asia/Jakarta)
     */
    public function getComputedStatusAttribute(): string
    {
        $dbStatus = strtolower($this->status);

        // Status pending, ditolak, dibatalkan TIDAK pernah berubah otomatis berdasarkan waktu
        if (in_array($dbStatus, ['pending', 'ditolak', 'dibatalkan'])) {
            return $dbStatus;
        }

        $start = \Carbon\Carbon::parse($this->tanggal_mulai . ' ' . $this->waktu_mulai);
        $end   = \Carbon\Carbon::parse($this->tanggal_selesai . ' ' . $this->waktu_selesai);
        $now   = \Carbon\Carbon::now();

        if ($now->lessThan($start)) {
            return 'disetujui';
        } elseif ($now->greaterThanOrEqualTo($start) && $now->lessThan($end)) {
            return 'berlangsung';
        } else {
            return 'selesai';
        }
    }

    /**
     * Sinkronkan status di database jika sudah memasuki waktu berlangsung atau selesai
     */
    public function syncLifecycleStatus(): self
    {
        $computed = $this->computed_status;
        $currentDb = strtolower($this->status);

        if (in_array($currentDb, ['disetujui', 'berlangsung']) && $currentDb !== $computed) {
            $this->status = $computed;
            $this->save();
        }

        return $this;
    }

    // Relasi ke pemohon (User / Mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke admin reviewer
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relasi ke ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}
