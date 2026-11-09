<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Elektronik' => 'Perangkat elektronik seperti proyektor dan laptop',
            'Furnitur' => 'Meja, kursi, dan lemari',
            'Alat Olahraga' => 'Perlengkapan olahraga',
            'Alat Laboratorium' => 'Peralatan praktikum',
        ];

        foreach ($data as $nama => $deskripsi) {
            Kategori::updateOrCreate(['nama' => $nama], ['deskripsi' => $deskripsi, 'is_aktif' => true]);
        }
    }
}
