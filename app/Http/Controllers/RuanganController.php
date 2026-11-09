<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    // Daftar ruangan dengan pencarian, filter status, dan halaman
    public function index(Request $request)
    {
        $data = Ruangan::query()
            ->when($request->q, fn ($q, $v) => $q->where('nama_ruangan', 'like', "%{$v}%"))
            ->when($request->status === 'aktif', fn ($q) => $q->where('is_aktif', true))
            ->when($request->status === 'nonaktif', fn ($q) => $q->where('is_aktif', false))
            ->orderBy('nama_ruangan')
            ->paginate(10)
            ->withQueryString();

        return view('ruangan.index', compact('data'));
    }

    public function create()
    {
        return view('ruangan.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:100', Rule::unique('ruangan', 'nama_ruangan')],
            'keterangan_lokasi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.max' => 'Nama maksimal 100 karakter.',
            'nama_ruangan.unique' => 'Nama sudah digunakan.',
            'keterangan_lokasi.max' => 'Keterangan lokasi maksimal 255 karakter.',
        ]);

        $item = Ruangan::create($data + ['is_aktif' => true]);
        $this->catat('tambah_ruangan', "Menambah ruangan {$item->nama_ruangan}");

        return redirect()->route('ruangan.index')->with('sukses', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Ruangan $item)
    {
        return view('ruangan.form', ['item' => $item]);
    }

    public function update(Request $request, Ruangan $item)
    {
        $data = $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:100', Rule::unique('ruangan', 'nama_ruangan')->ignore($item->id)],
            'keterangan_lokasi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.max' => 'Nama maksimal 100 karakter.',
            'nama_ruangan.unique' => 'Nama sudah digunakan.',
            'keterangan_lokasi.max' => 'Keterangan lokasi maksimal 255 karakter.',
        ]);

        $item->update($data);
        $this->catat('ubah_ruangan', "Mengubah ruangan {$item->nama_ruangan}");

        return redirect()->route('ruangan.index')->with('sukses', 'Data ruangan berhasil diperbarui.');
    }

    // Nonaktifkan / aktifkan kembali (data tidak dihapus agar riwayat tetap utuh)
    public function status(Ruangan $item)
    {
        $item->update(['is_aktif' => ! $item->is_aktif]);
        $kata = $item->is_aktif ? 'mengaktifkan' : 'menonaktifkan';
        $this->catat('status_ruangan', ucfirst($kata) . " ruangan {$item->nama_ruangan}");

        return redirect()->route('ruangan.index')
            ->with('sukses', 'Ruangan berhasil ' . ($item->is_aktif ? 'diaktifkan kembali.' : 'dinonaktifkan.'));
    }

    private function catat(string $aksi, string $deskripsi): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
        ]);
    }
}
