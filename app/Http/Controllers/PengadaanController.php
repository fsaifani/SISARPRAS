<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\DetailPengadaan;
use App\Models\Barang;
use App\Models\StokRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaan = Pengadaan::with('user')->latest()->get();
        return view('pengadaan.index', compact('pengadaan'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('pengadaan.form', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|array',
            'jumlah' => 'required|array',
            'alasan' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $pengadaan = Pengadaan::create([
                'nomor' => 'PGD-' . time(),
                'pengaju_id' => Auth::id(),
                'status' => 'diusulkan',
                'catatan_admin' => $request->alasan
            ]);

            foreach ($request->barang_id as $key => $barang_id) {
                if ($request->jumlah[$key] > 0) {
                    // Cari nama barangnya dulu dari tabel barang biar bisa disave
                    $barang = \App\Models\Barang::find($barang_id);
                    $namaBarang = $barang ? ($barang->nama_barang ?? $barang->nama) : 'Barang tidak diketahui';

                    DetailPengadaan::create([
                        'pengadaan_id' => $pengadaan->id,
                        'barang_id' => $barang_id,
                        'nama_barang' => $namaBarang, // Isi kolom wajib ini
                        'jumlah_diajukan' => $request->jumlah[$key] // Sesuaikan dengan nama kolom di database
                    ]);
                }
            }
            
            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'Usulan pengadaan berhasil dikirim ke admin.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show(Pengadaan $pengadaan)
    {
        $pengadaan->load(['detailPengadaan.barang', 'user']);
        return view('pengadaan.show', compact('pengadaan'));
    }

    public function persetujuan(Request $request, Pengadaan $pengadaan)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,dikembalikan',
        ]);

        $pengadaan->update([
            'status' => $request->status,
        ]);

        return redirect()->route('pengadaan.show', $pengadaan->id)->with('success', 'status usulan pengadaan berhasil diperbarui.');
    }

    public function terima(Request $request, Pengadaan $pengadaan)
    {
        DB::beginTransaction();
        try {
            foreach ($pengadaan->detailPengadaan as $detail) {
                $stok = StokRuangan::where('barang_id', $detail->barang_id)->first();
                
                if ($stok) {
                    $stok->increment('jumlah', $detail->jumlah);
                } else {
                    StokRuangan::create([
                        'barang_id' => $detail->barang_id,
                        'ruangan_id' => 1, 
                        'jumlah' => $detail->jumlah
                    ]);
                }
            }

            $pengadaan->update([
                'status' => 'selesai',
            ]);

            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'barang pengadaan telah diterima, stok inventaris otomatis bertambah.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'gagal memproses penerimaan barang.');
        }
    }
}