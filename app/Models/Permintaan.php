<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $table = 'permintaan';

    protected $guarded = [];

    protected $casts = [
        'tanggal_dibutuhkan' => 'date',
        'tanggal_serah' => 'datetime',
    ];

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPermintaan::class, 'permintaan_id');
    }

    public function pengadaan()
    {
        return $this->hasMany(Pengadaan::class, 'permintaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaan::class, 'permintaan_id');
    }
}