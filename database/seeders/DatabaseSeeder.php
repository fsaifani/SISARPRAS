<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. kategori barang
        $kategori = [
            ['id' => 1, 'nama' => 'Elektronik & IT', 'is_aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nama' => 'Mebel / Furniture', 'is_aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nama' => 'Alat Praktik Kejuruan', 'is_aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'nama' => 'Barang Habis Pakai (ATK)', 'is_aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'nama' => 'Alat Kebersihan', 'is_aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('kategori')->insertOrIgnore($kategori);

        // 2. data ruangan yang banyak dan detail
        $ruangan = [
            ['nama_ruangan' => 'Gedung A - Ruang Kepala Sekolah'],
            ['nama_ruangan' => 'Gedung A - Ruang Tata Usaha'],
            ['nama_ruangan' => 'Gedung B - Ruang Guru'],
            ['nama_ruangan' => 'Gedung B - Perpustakaan Utama'],
            ['nama_ruangan' => 'Gedung C - Aula Sekolah'],
            ['nama_ruangan' => 'Gedung D - Gudang Sarpras'],
            ['nama_ruangan' => 'Gedung E - Ruang Kelas X RPL 1'],
            ['nama_ruangan' => 'Gedung E - Ruang Kelas X RPL 2'],
            ['nama_ruangan' => 'Gedung E - Ruang Kelas XI RPL 1'],
            ['nama_ruangan' => 'Gedung E - Ruang Kelas XII RPL 1'],
            ['nama_ruangan' => 'Lab RPL 1 (Software)'],
            ['nama_ruangan' => 'Lab RPL 2 (Hardware & Jaringan)'],
            ['nama_ruangan' => 'Lab Multimedia 1'],
            ['nama_ruangan' => 'Lab TKJ 1'],
            ['nama_ruangan' => 'Lab Mesin TKR'],
            ['nama_ruangan' => 'Bengkel TBSM (Motor)'],
            ['nama_ruangan' => 'RPS Mesin CNC'],
            ['nama_ruangan' => 'Pos Satpam Utama'],
            ['nama_ruangan' => 'Masjid Sekolah'],
            ['nama_ruangan' => 'UKS'],
        ];
        DB::table('ruangan')->insertOrIgnore($ruangan);

        // 3. data barang yang banyak, disesuaikan murni dengan struktur kolom lu
        $barang = [
            ['kode_barang' => 'BRG-007', 'nama' => 'PC Client Lab (Core i5)', 'kategori_id' => 1, 'deskripsi' => 'PC untuk praktikum siswa', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-008', 'nama' => 'PC Server', 'kategori_id' => 1, 'deskripsi' => 'Server CBT dan database', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-009', 'nama' => 'Switch Hub Cisco 24 Port', 'kategori_id' => 1, 'deskripsi' => 'Switch untuk lab jaringan', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-010', 'nama' => 'Kabel UTP Cat6 (Roll)', 'kategori_id' => 4, 'deskripsi' => 'Kabel jaringan', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-011', 'nama' => 'Tang Crimping', 'kategori_id' => 3, 'deskripsi' => 'Alat potong dan crimp RJ45', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-012', 'nama' => 'Meja Guru', 'kategori_id' => 2, 'deskripsi' => 'Meja kayu standar guru', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-013', 'nama' => 'Kursi Guru', 'kategori_id' => 2, 'deskripsi' => 'Kursi busa untuk guru', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-014', 'nama' => 'Papan Tulis (Whiteboard)', 'kategori_id' => 2, 'deskripsi' => 'Ukuran 2x1 meter', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-015', 'nama' => 'Spidol Hitam Boardmarker', 'kategori_id' => 4, 'deskripsi' => 'Spidol whiteboard', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-016', 'nama' => 'Kertas HVS A4 80gr', 'kategori_id' => 4, 'deskripsi' => 'Kertas print laporan', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-017', 'nama' => 'Tinta Printer Epson', 'kategori_id' => 4, 'deskripsi' => 'Tinta botol', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-018', 'nama' => 'Printer Epson L3110', 'kategori_id' => 1, 'deskripsi' => 'Printer warna', 'khusus_guru' => 1, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-019', 'nama' => 'Mesin Las Listrik', 'kategori_id' => 3, 'deskripsi' => 'Mesin las bengkel', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-020', 'nama' => 'Jangka Sorong (Caliper)', 'kategori_id' => 3, 'deskripsi' => 'Alat ukur presisi', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-021', 'nama' => 'Multimeter Digital', 'kategori_id' => 3, 'deskripsi' => 'Alat ukur listrik', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-022', 'nama' => 'Sapu Ijuk', 'kategori_id' => 5, 'deskripsi' => 'Alat kebersihan kelas', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-023', 'nama' => 'AC Daikin 2 PK', 'kategori_id' => 1, 'deskripsi' => 'Pendingin ruangan lab', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-024', 'nama' => 'Kipas Angin Dinding', 'kategori_id' => 1, 'deskripsi' => 'Kipas angin kelas', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-025', 'nama' => 'Obeng Set Plus Minus', 'kategori_id' => 3, 'deskripsi' => 'Set obeng lengkap', 'khusus_guru' => 0, 'is_aktif' => 1],
            ['kode_barang' => 'BRG-026', 'nama' => 'Lemari Arsip Besi', 'kategori_id' => 2, 'deskripsi' => 'Penyimpanan dokumen', 'khusus_guru' => 1, 'is_aktif' => 1],
        ];
        DB::table('barang')->insertOrIgnore($barang);
    }
}