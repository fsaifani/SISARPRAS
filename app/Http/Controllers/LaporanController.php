<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Permintaan;
use App\Models\Pengadaan;
use App\Models\Kerusakan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->jenis ?? 'inventaris';
        $data = [];
        
        if ($jenis == 'inventaris') {
            $data = Barang::with(['kategori', 'ruangan'])->get();
        } elseif ($jenis == 'peminjaman') {
            $data = Peminjaman::with(['user', 'detailPeminjaman.barang'])->latest()->get();
        } elseif ($jenis == 'permintaan') {
            $data = Permintaan::with(['user', 'detailPermintaan.barang'])->latest()->get();
        } elseif ($jenis == 'pengadaan') {
            $data = Pengadaan::with(['user', 'detailPengadaan.barang'])->latest()->get();
        } elseif ($jenis == 'kerusakan') {
            $data = Kerusakan::with(['barang', 'ruangan'])->latest()->get();
        }

        return view('laporan.index', compact('data', 'jenis'));
    }
}