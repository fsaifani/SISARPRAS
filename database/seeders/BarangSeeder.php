<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\StokRuangan;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // [kode, nama, kategori, khusus_guru, ruangan, jumlah]
        $data = [
            ['BRG-001', 'Proyektor', 'Elektronik', true, 'Gudang Sarpras', 5],
            ['BRG-002', 'Laptop', 'Elektronik', true, 'Lab Komputer', 10],
            ['BRG-003', 'Kursi Lipat', 'Furnitur', false, 'Gudang Sarpras', 30],
            ['BRG-004', 'Bola Voli', 'Alat Olahraga', false, 'Gudang Sarpras', 8],
            ['BRG-005', 'Mikroskop', 'Alat Laboratorium', false, 'Perpustakaan', 6],
            ['BRG-006', 'Speaker Portable', 'Elektronik', false, 'Ruang Guru', 3],
        ];

        foreach ($data as [$kode, $nama, $kategori, $khusus, $ruangan, $jumlah]) {
            $barang = Barang::updateOrCreate(
                ['kode_barang' => $kode],
                [
                    'nama' => $nama,
                    'kategori_id' => Kategori::where('nama', $kategori)->value('id'),
                    'khusus_guru' => $khusus,
                    'is_aktif' => true,
                ]
            );

            StokRuangan::updateOrCreate(
                ['barang_id' => $barang->id, 'ruangan_id' => Ruangan::where('nama_ruangan', $ruangan)->value('id')],
                ['jumlah_tersedia' => $jumlah]
            );
        }
    }
}
