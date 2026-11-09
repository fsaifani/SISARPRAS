<?php

namespace App\Http\Controllers;

use App\Models\Kerusakan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KerusakanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'admin' || $user->role == 'petugas') {
            $kerusakan = Kerusakan::with('barang.ruangan')->latest()->get();
        } else {
            $kerusakan = Kerusakan::with('barang.ruangan')->where('petugas_id', $user->id)->latest()->get();
        }
        return view('kerusakan.index', compact('kerusakan'));
    }

    public function create()
    {
        // Ambil data barang dan ruangan untuk ditampilkan di dropdown form
        $barang = Barang::all();
        $ruangan = \App\Models\Ruangan::all(); // Ini yang tadi kelupaan dikirim
        
        // Kirim dua variabel itu ke tampilan form
        return view('kerusakan.form', compact('barang', 'ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'ruangan_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'deskripsi_kerusakan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // validasi foto
        ]);

        // proses upload foto
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('bukti_rusak', 'public');
        }

        Kerusakan::create([
            'petugas_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'ruangan_id' => $request->ruangan_id,
            'jumlah' => $request->jumlah,
            'tanggal_lapor' => now(),
            'deskripsi' => $request->deskripsi_kerusakan,
            'foto' => $pathFoto, // masukin nama fotonya ke db
            'status' => 'dilaporkan'
        ]);

        return redirect()->route('kerusakan.index')->with('success', 'laporan kerusakan dan foto bukti berhasil dikirim.');
    }

    public function updateProses(Request $request, $id)
    {
        $kerusakan = Kerusakan::findOrFail($id);
        $kerusakan->update([
            'status' => $request->status
        ]);

        // Logika ngubah kondisi barang gue hapus total biar gak error

        return back()->with('success', 'Status perbaikan berhasil diupdate.');
    }
}