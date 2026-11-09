<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    protected $table = 'pengadaan';

    protected $guarded = [];

    protected $casts = ['tanggal_keputusan' => 'datetime'];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    // nama relasi disamakan dengan yang dipanggil di controller ('user')
    public function user()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function pemutus()
    {
        return $this->belongsTo(User::class, 'keputusan_oleh');
    }

    // nama relasi disamakan dengan yang dipanggil di controller ('detailPengadaan')
    public function detailPengadaan()
    {
        return $this->hasMany(DetailPengadaan::class, 'pengadaan_id');
    }
}