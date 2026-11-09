<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Gudang Sarpras' => 'Gedung utama, lantai 1',
            'Lab Komputer' => 'Gedung B, lantai 2',
            'Ruang Guru' => 'Gedung utama, lantai 1',
            'Perpustakaan' => 'Gedung A',
        ];

        foreach ($data as $nama => $lokasi) {
            Ruangan::updateOrCreate(['nama_ruangan' => $nama], ['keterangan_lokasi' => $lokasi, 'is_aktif' => true]);
        }
    }
}
