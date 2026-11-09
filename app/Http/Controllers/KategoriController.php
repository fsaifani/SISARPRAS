<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::query()->withCount('barang')
            ->when($request->q, fn ($q, $v) => $q->where('nama', 'like', "%{$v}%"))
            ->when($request->status === 'aktif', fn ($q) => $q->where('is_aktif', true))
            ->when($request->status === 'nonaktif', fn ($q) => $q->where('is_aktif', false))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        // nama variabel disamakan jadi 'kategori' biar nyambung sama blade lu
        return view('kategori.form', ['kategori' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('kategori', 'nama')],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'nama.unique' => 'Nama sudah digunakan.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
        ]);

        $kategori = Kategori::create($data + ['is_aktif' => true]);
        $this->catat('tambah_kategori', "Menambah kategori {$kategori->nama}");

        return redirect()->route('kategori.index')->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $item)
    {
        return view('kategori.form', ['kategori' => $item]);
    }

    public function update(Request $request, Kategori $item)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('kategori', 'nama')->ignore($item->id)],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'nama.unique' => 'Nama sudah digunakan.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
        ]);

        $item->update($data);
        $this->catat('ubah_kategori', "Mengubah kategori {$item->nama}");

        return redirect()->route('kategori.index')->with('sukses', 'Data kategori berhasil diperbarui.');
    }

    public function status(Kategori $item)
    {
        $item->update(['is_aktif' => ! $item->is_aktif]);
        $kata = $item->is_aktif ? 'mengaktifkan' : 'menonaktifkan';
        $this->catat('status_kategori', ucfirst($kata) . " kategori {$item->nama}");

        return redirect()->route('kategori.index')
            ->with('sukses', 'Kategori berhasil ' . ($item->is_aktif ? 'diaktifkan kembali.' : 'dinonaktifkan.'));
    }

    public function destroy(Kategori $kategori)
    {
        try {
            // simpan nama sebelum dihapus buat di log
            $namaKategori = $kategori->nama;
            
            $kategori->delete();
            $this->catat('hapus_kategori', "Menghapus kategori {$namaKategori}");
            
            return redirect()->route('kategori.index')->with('sukses', 'Kategori berhasil dihapus permanen.');
        } catch (\Exception $e) {
            // ini pelindung kalau kategorinya udah kepakai di tabel barang, biar ga meledak error merah
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak bisa dihapus karena sudah ada barang di dalamnya.');
        }
    }

    private function catat(string $aksi, string $deskripsi): void
    {
        LogAktivitas::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
        ]);
    }
}