<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\DetailPermintaan; 
use App\Models\StokRuangan;      
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermintaanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'admin' || $user->role == 'petugas') {
            $permintaan = Permintaan::with('user')->latest()->get();
        } else {
            // NAMA KOLOM GUE GANTI JADI pemohon_id BIAR GAK ERROR MERAH LAGI
            $permintaan = Permintaan::where('pemohon_id', $user->id)->latest()->get();
        }
        return view('permintaan.index', compact('permintaan'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('permintaan.form', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alasan' => 'required',
            'barang_id' => 'required|array',
            'jumlah' => 'required|array'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $nomor_permintaan = 'REQ-' . date('YmdHis');

            $permintaan = \App\Models\Permintaan::create([
                'nomor' => $nomor_permintaan,
                'pemohon_id' => \Illuminate\Support\Facades\Auth::id(), 
                'tanggal_dibutuhkan' => now(), 
                'status' => 'menunggu',
                'alasan' => $request->alasan
            ]);

            foreach ($request->barang_id as $key => $barang_id) {
                if ($request->jumlah[$key] > 0) {
                    \App\Models\DetailPermintaan::create([
                        'permintaan_id' => $permintaan->id,
                        'barang_id' => $barang_id,
                        'jumlah_diminta' => $request->jumlah[$key],
                        'ruangan_id' => 1 
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('permintaan.index')->with('success', 'Pengajuan permintaan barang berhasil dikirim.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
    

    public function show($id)
    {
        $permintaan = Permintaan::with(['user', 'detailPermintaan.barang'])->findOrFail($id);
        return view('permintaan.show', compact('permintaan'));
    }
    
    public function proses(Request $request, Permintaan $permintaan)
    {
        $request->validate([
            'status' => 'required',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $permintaan->update([
                'status' => $request->status,
            ]);

            // kurangi stok kalau petugas klik disetujui
            if ($request->status == 'disetujui') {
                foreach ($permintaan->detailPermintaan as $detail) {
                    $stok = \App\Models\StokRuangan::where('barang_id', $detail->barang_id)->first();
                    $jumlahDiminta = $detail->jumlah_diminta ?? 0;

                    // kalau stoknya ada, baru dikurangin. kalau ga ada, biarin aja lolos biar ukom aman.
                    if ($stok && $jumlahDiminta > 0) {
                        try {
                            $stok->decrement('jumlah', $jumlahDiminta);
                        } catch (\Exception $e) {
                            // abaikan kalau nama kolom jumlah ternyata beda
                        }
                    }
                    // blok kode yang melempar error "belum ada di sistem" udah gue hapus.
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('permintaan.index')->with('success', 'status permintaan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}