<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPeminjaman;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $guarded = [];

    protected $casts = [
        'tanggal_rencana_pakai' => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_serah' => 'datetime',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'datetime',
    ];

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    public function kerusakan()
    {
        return $this->hasMany(Kerusakan::class, 'peminjaman_id');
    }

    // Terlambat jika sedang dipinjam dan sudah lewat jatuh tempo
    public function isTerlambat(): bool
    {
        return $this->status === 'dipinjam'
            && $this->tanggal_jatuh_tempo !== null
            && now()->startOfDay()->gt($this->tanggal_jatuh_tempo);
    }

    public function user()
    {
        // ini ngasih tau laravel buat nyari nama user berdasarkan kolom peminjam_id
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function detailPeminjaman()
    {
        // ini ngasih tau laravel kalau satu peminjaman punya banyak detail barang
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }
}
