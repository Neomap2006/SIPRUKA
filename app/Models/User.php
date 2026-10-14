<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'nipd',
        'prodi',
        'jabatan',
        'no_hp',
    ];

    /**
     * Relasi ke pengajuan peminjaman sebagai pemohon (Mahasiswa/User)
     */
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanRuangan::class, 'user_id');
    }

    /**
     * Relasi ke peminjaman yang diverifikasi/disetujui (Admin)
     */
    public function verifikasiPeminjaman()
    {
        return $this->hasMany(PeminjamanRuangan::class, 'admin_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
