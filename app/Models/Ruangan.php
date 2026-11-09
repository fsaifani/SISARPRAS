<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $guarded = [];

    protected $casts = ['is_aktif' => 'boolean'];

    public function stok()
    {
        return $this->hasMany(StokRuangan::class);
    }

    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'stok_ruangan')
            ->withPivot('jumlah_tersedia', 'jumlah_dicadangkan', 'jumlah_dipinjam', 'jumlah_rusak', 'jumlah_diperbaiki');
    }
}
