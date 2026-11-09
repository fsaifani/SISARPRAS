<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengadaan extends Model
{
    protected $table = 'detail_pengadaan';

    protected $guarded = [];

    protected $casts = ['tanggal_terima' => 'date'];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
