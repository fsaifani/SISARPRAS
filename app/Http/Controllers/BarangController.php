<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\RiwayatStok;
use App\Models\Ruangan;
use App\Models\StokRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BarangController extends Controller
{
    // Inventaris untuk Admin (hanya lihat) dan Petugas (kelola)
    public function index(Request $request)
    {
        $data = $this->queryDaftar($request)->paginate(10)->withQueryString();
        $kategori = Kategori::orderBy('nama')->get();
        $ruangan = Ruangan::orderBy('nama_ruangan')->get();

        return view('barang.index', compact('data', 'kategori', 'ruangan'));
    }

    // Daftar barang untuk Guru dan Siswa (hanya lihat, hanya barang aktif)
    public function daftar(Request $request)
    {
        $data = $this->queryDaftar($request)->where('is_aktif', true)->paginate(10)->withQueryString();
        $kategori = Kategori::where('is_aktif', true)->orderBy('nama')->get();

        return view('barang.daftar', compact('data', 'kategori'));
    }

    private function queryDaftar(Request $request)
    {
        return Barang::with(['kategori', 'stok.ruangan'])
            ->withSum('stok as tersedia_sum', 'jumlah_tersedia')
            ->withSum('stok as dicadangkan_sum', 'jumlah_dicadangkan')
            ->withSum('stok as dipinjam_sum', 'jumlah_dipinjam')
            ->withSum('stok as rusak_sum', 'jumlah_rusak')
            ->withSum('stok as diperbaiki_sum', 'jumlah_diperbaiki')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('nama', 'like', "%{$v}%")->orWhere('kode_barang', 'like', "%{$v}%")))
            ->when($request->kategori_id, fn ($q, $v) => $q->where('kategori_id', $v))
            ->when($request->ruangan_id, fn ($q, $v) => $q->whereHas('stok', fn ($s) => $s->where('ruangan_id', $v)))
            ->when($request->kondisi === 'baik', fn ($q) => $q->whereHas('stok', fn ($s) => $s->where('jumlah_tersedia', '>', 0)))
            ->when($request->kondisi === 'rusak', fn ($q) => $q->whereHas('stok', fn ($s) => $s->where(fn ($x) => $x->where('jumlah_rusak', '>', 0)->orWhere('jumlah_diperbaiki', '>', 0))))
            ->when($request->status === 'aktif', fn ($q) => $q->where('is_aktif', true))
            ->when($request->status === 'nonaktif', fn ($q) => $q->where('is_aktif', false))
            ->orderBy('nama');
    }

    public function create()
    {
        return view('barang.form', [
            'item' => null,
            'kategori' => Kategori::where('is_aktif', true)->orderBy('nama')->get(),
            'ruangan' => Ruangan::where('is_aktif', true)->orderBy('nama_ruangan')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kode_barang' => ['nullable', 'string', 'max:50', 'unique:barang,kode_barang'],
            'kategori_id' => ['required', Rule::exists('kategori', 'id')->where('is_aktif', true)],
            'ruangan_id' => ['required', Rule::exists('ruangan', 'id')->where('is_aktif', true)],
            'jumlah_awal' => ['required', 'integer', 'min:1', 'max:100000'],
            'kondisi' => ['required', 'in:baik,rusak'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], $this->pesan());

        $barang = null;
        DB::transaction(function () use ($data, $request, &$barang) {
            $barang = Barang::create([
                'nama' => $data['nama'],
                'kode_barang' => $data['kode_barang'] ?? null,
                'kategori_id' => $data['kategori_id'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'khusus_guru' => $request->boolean('khusus_guru'),
                'is_aktif' => true,
            ]);

            if (! $barang->kode_barang) {
                $kode = 'BRG-' . str_pad((string) $barang->id, 3, '0', STR_PAD_LEFT);
                while (Barang::where('kode_barang', $kode)->exists()) {
                    $kode .= 'A';
                }
                $barang->update(['kode_barang' => $kode]);
            }

            $baik = $data['kondisi'] === 'baik';
            StokRuangan::create([
                'barang_id' => $barang->id,
                'ruangan_id' => $data['ruangan_id'],
                'jumlah_tersedia' => $baik ? $data['jumlah_awal'] : 0,
                'jumlah_rusak' => $baik ? 0 : $data['jumlah_awal'],
            ]);

            RiwayatStok::create([
                'barang_id' => $barang->id,
                'ruangan_id' => $data['ruangan_id'],
                'user_id' => auth()->id(),
                'jenis' => 'masuk',
                'jumlah' => $data['jumlah_awal'],
                'alasan' => 'Stok awal (kondisi ' . $data['kondisi'] . ')',
                'referensi_tipe' => 'barang',
                'referensi_id' => $barang->id,
            ]);

            $this->catat('tambah_barang', "Menambah barang {$barang->nama} sebanyak {$data['jumlah_awal']}");
        });

        return redirect()->route('barang.show', $barang)->with('sukses', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'stok.ruangan']);
        $riwayat = RiwayatStok::with(['ruangan', 'user'])
            ->where('barang_id', $barang->id)->latest('created_at')->latest('id')->limit(15)->get();
        $ruangan = Ruangan::where('is_aktif', true)->orderBy('nama_ruangan')->get();

        return view('barang.show', compact('barang', 'riwayat', 'ruangan'));
    }

    public function edit(Barang $barang)
    {
        return view('barang.form', [
            'item' => $barang,
            'kategori' => Kategori::where('is_aktif', true)->orWhere('id', $barang->kategori_id)->orderBy('nama')->get(),
            'ruangan' => collect(),
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kode_barang' => ['required', 'string', 'max:50', Rule::unique('barang', 'kode_barang')->ignore($barang->id)],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], $this->pesan());

        $barang->update($data + ['khusus_guru' => $request->boolean('khusus_guru')]);
        $this->catat('ubah_barang', "Mengubah data barang {$barang->nama}");

        return redirect()->route('barang.show', $barang)->with('sukses', 'Data barang berhasil diperbarui.');
    }

    // Nonaktifkan / aktifkan kembali barang (riwayat tetap tersimpan)
    public function status(Barang $barang)
    {
        if ($barang->is_aktif) {
            $terpakai = (int) $barang->stok()->sum('jumlah_dipinjam') + (int) $barang->stok()->sum('jumlah_dicadangkan');
            if ($terpakai > 0) {
                return back()->with('gagal', 'Barang tidak dapat dinonaktifkan karena masih dipinjam atau dicadangkan.');
            }
        }

        $barang->update(['is_aktif' => ! $barang->is_aktif]);
        $this->catat('status_barang', ($barang->is_aktif ? 'Mengaktifkan' : 'Menonaktifkan') . " barang {$barang->nama}");

        return back()->with('sukses', 'Barang berhasil ' . ($barang->is_aktif ? 'diaktifkan kembali.' : 'dinonaktifkan.'));
    }

    // Penyesuaian stok tersedia (wajib disertai alasan)
    public function sesuaikan(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'ruangan_sesuai' => ['required', Rule::exists('ruangan', 'id')->where('is_aktif', true)],
            'arah' => ['required', 'in:tambah,kurangi'],
            'jumlah_sesuai' => ['required', 'integer', 'min:1', 'max:100000'],
            'alasan_sesuai' => ['required', 'string', 'max:255'],
        ], [
            'ruangan_sesuai.required' => 'Ruangan wajib dipilih.',
            'ruangan_sesuai.exists' => 'Ruangan tidak valid.',
            'arah.required' => 'Jenis penyesuaian wajib dipilih.',
            'jumlah_sesuai.required' => 'Jumlah wajib diisi.',
            'jumlah_sesuai.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah_sesuai.min' => 'Jumlah minimal 1.',
            'alasan_sesuai.required' => 'Alasan penyesuaian wajib diisi.',
        ]);

        DB::transaction(function () use ($data, $barang) {
            StokRuangan::firstOrCreate(['barang_id' => $barang->id, 'ruangan_id' => $data['ruangan_sesuai']]);
            $stok = StokRuangan::where('barang_id', $barang->id)->where('ruangan_id', $data['ruangan_sesuai'])->lockForUpdate()->first();

            $delta = $data['arah'] === 'tambah' ? $data['jumlah_sesuai'] : -$data['jumlah_sesuai'];
            if ($stok->jumlah_tersedia + $delta < 0) {
                throw ValidationException::withMessages(['jumlah_sesuai' => 'Stok tersedia di ruangan ini tidak mencukupi untuk dikurangi.']);
            }

            $stok->jumlah_tersedia += $delta;
            $stok->save();

            RiwayatStok::create([
                'barang_id' => $barang->id,
                'ruangan_id' => $data['ruangan_sesuai'],
                'user_id' => auth()->id(),
                'jenis' => 'penyesuaian',
                'jumlah' => $delta,
                'alasan' => $data['alasan_sesuai'],
                'referensi_tipe' => 'barang',
                'referensi_id' => $barang->id,
            ]);

            $this->catat('sesuaikan_stok', "Menyesuaikan stok {$barang->nama} ({$delta}): {$data['alasan_sesuai']}");
        });

        return back()->with('sukses', 'Stok berhasil disesuaikan.');
    }

    // Pindah barang antar ruangan: stok asal berkurang, tujuan bertambah, total tetap
    public function pindah(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'ruangan_asal' => ['required', 'exists:ruangan,id'],
            'ruangan_tujuan' => ['required', Rule::exists('ruangan', 'id')->where('is_aktif', true), 'different:ruangan_asal'],
            'jumlah_pindah' => ['required', 'integer', 'min:1', 'max:100000'],
        ], [
            'ruangan_asal.required' => 'Ruangan asal wajib dipilih.',
            'ruangan_tujuan.required' => 'Ruangan tujuan wajib dipilih.',
            'ruangan_tujuan.different' => 'Ruangan tujuan harus berbeda dengan ruangan asal.',
            'jumlah_pindah.required' => 'Jumlah wajib diisi.',
            'jumlah_pindah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah_pindah.min' => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () use ($data, $barang) {
            $asal = StokRuangan::where('barang_id', $barang->id)->where('ruangan_id', $data['ruangan_asal'])->lockForUpdate()->first();
            if (! $asal || $asal->jumlah_tersedia < $data['jumlah_pindah']) {
                throw ValidationException::withMessages(['jumlah_pindah' => 'Stok tersedia di ruangan asal tidak mencukupi.']);
            }

            StokRuangan::firstOrCreate(['barang_id' => $barang->id, 'ruangan_id' => $data['ruangan_tujuan']]);
            $tujuan = StokRuangan::where('barang_id', $barang->id)->where('ruangan_id', $data['ruangan_tujuan'])->lockForUpdate()->first();

            $asal->jumlah_tersedia -= $data['jumlah_pindah'];
            $asal->save();
            $tujuan->jumlah_tersedia += $data['jumlah_pindah'];
            $tujuan->save();

            $namaAsal = Ruangan::find($data['ruangan_asal'])->nama_ruangan;
            $namaTujuan = Ruangan::find($data['ruangan_tujuan'])->nama_ruangan;

            RiwayatStok::create([
                'barang_id' => $barang->id, 'ruangan_id' => $data['ruangan_asal'], 'user_id' => auth()->id(),
                'jenis' => 'pindah', 'jumlah' => -$data['jumlah_pindah'], 'alasan' => "Pindah ke {$namaTujuan}",
                'referensi_tipe' => 'barang', 'referensi_id' => $barang->id,
            ]);
            RiwayatStok::create([
                'barang_id' => $barang->id, 'ruangan_id' => $data['ruangan_tujuan'], 'user_id' => auth()->id(),
                'jenis' => 'pindah', 'jumlah' => $data['jumlah_pindah'], 'alasan' => "Pindah dari {$namaAsal}",
                'referensi_tipe' => 'barang', 'referensi_id' => $barang->id,
            ]);

            $this->catat('pindah_barang', "Memindahkan {$data['jumlah_pindah']} {$barang->nama} dari {$namaAsal} ke {$namaTujuan}");
        });

        return back()->with('sukses', 'Barang berhasil dipindahkan.');
    }

    private function pesan(): array
    {
        return [
            'nama.required' => 'Nama barang wajib diisi.',
            'nama.max' => 'Nama barang maksimal 150 karakter.',
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'ruangan_id.required' => 'Ruangan penyimpanan wajib dipilih.',
            'ruangan_id.exists' => 'Ruangan tidak valid.',
            'jumlah_awal.required' => 'Jumlah awal wajib diisi.',
            'jumlah_awal.integer' => 'Jumlah awal harus berupa angka bulat.',
            'jumlah_awal.min' => 'Jumlah awal minimal 1.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'deskripsi.max' => 'Keterangan maksimal 500 karakter.',
        ];
    }

    private function catat(string $aksi, string $deskripsi): void
    {
        LogAktivitas::create(['user_id' => auth()->id(), 'aksi' => $aksi, 'deskripsi' => $deskripsi]);
    }
}
