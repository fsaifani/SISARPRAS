<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'is_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_aktif' => 'boolean',
        ];
    }

    // Agar kode bawaan Laravel/Breeze yang memakai $user->name tetap jalan
    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isPetugas(): bool { return $this->role === 'petugas'; }
    public function isGuru(): bool { return $this->role === 'guru'; }
    public function isSiswa(): bool { return $this->role === 'siswa'; }

    public function permintaan()
    {
        return $this->hasMany(Permintaan::class, 'pemohon_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'peminjam_id');
    }

    public function pengadaan()
    {
        return $this->hasMany(Pengadaan::class, 'pengaju_id');
    }

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStok::class);
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
}
