<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $guarded = [];

    protected $casts = [
        'khusus_guru' => 'boolean',
        'is_aktif' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function stok()
    {
        return $this->hasMany(StokRuangan::class);
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatStok::class);
    }

    public function kerusakan()
    {
        return $this->hasMany(Kerusakan::class);
    }

    public function ruangan()
    {
        return $this->belongsToMany(Ruangan::class, 'stok_ruangan')
            ->withPivot('jumlah_tersedia', 'jumlah_dicadangkan', 'jumlah_dipinjam', 'jumlah_rusak', 'jumlah_diperbaiki');
    }

    // Total stok siap pakai di semua ruangan
    public function getTotalTersediaAttribute(): int
    {
        return (int) $this->stok()->sum('jumlah_tersedia');
    }
}
