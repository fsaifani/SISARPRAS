<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokRuangan extends Model
{
    protected $table = 'stok_ruangan';

    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
