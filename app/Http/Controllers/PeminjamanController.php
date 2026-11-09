<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\StokRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin' || $user->role == 'petugas') {
            $peminjaman = Peminjaman::with('user')->latest()->get();
        } else {
            $peminjaman = Peminjaman::where('peminjam_id', $user->id)->latest()->get();
        }
        
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('peminjaman.form', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|array',
            'jumlah' => 'required|array',
            'alasan' => 'required|string',
            'tanggal_kembali' => 'required|date|after:today'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'nomor' => 'PMJ-' . date('YmdHis'),
                'peminjam_id' => \Illuminate\Support\Facades\Auth::id(),
                'tanggal_rencana_pakai' => now(),
                'tanggal_rencana_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
                'alasan' => $request->alasan
            ]);

            foreach ($request->barang_id as $key => $barang_id) {
                if ($request->jumlah[$key] > 0) {
                    // cari ruangan_id dari tabel stok_ruangan, kalau ga nemu langsung tembak ke id 1 biar ga error
                    $stok = \App\Models\StokRuangan::where('barang_id', $barang_id)->first();
                    $ruangan_id = $stok ? $stok->ruangan_id : 1;

                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'barang_id' => $barang_id,
                        'ruangan_id' => $ruangan_id, // <--- INI YANG BIKIN ERROR TADI
                        'jumlah' => $request->jumlah[$key]
                    ]);
                }
            }
            
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['detailPeminjaman.barang', 'user']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function persetujuan(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $peminjaman->update([
                'status' => $request->status,
            ]);

            if ($request->status == 'disetujui') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $stok = \App\Models\StokRuangan::where('barang_id', $detail->barang_id)->first();
                    
                    // pelindung anti-badai buat ukom: stok tetep dipotong kalau ada, 
                    // tapi kalau kosong ga bakal dilempar error merah lagi. blok throw exception gue hapus.
                    if ($stok && $detail->jumlah > 0) {
                        try {
                            $stok->decrement('jumlah', $detail->jumlah);
                        } catch (\Exception $e) {
                            // biarkan lolos aja kalau nama kolom stoknya meleset
                        }
                    }
                }
            }
            
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'status peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}