<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $guarded = [];

    protected $casts = ['is_aktif' => 'boolean'];

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
