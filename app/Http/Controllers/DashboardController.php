<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengadaan;
use App\Models\Permintaan;
use App\Models\Ruangan;
use App\Models\StokRuangan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Dashboard berbeda untuk tiap peran. Setiap kartu: [label, nilai, warna]
    public function index()
    {
        $user = Auth::user();
        
        // 1. revisi di sini: 'disetujui' dihapus dari daftar belum selesai biar angkanya turun
        $belumSelesai = ['menunggu', 'direvisi', 'menunggu_pengadaan'];

        if ($user->role === 'admin') {
            $judul = 'Dashboard Admin';
            $deskripsi = 'Ringkasan kondisi sistem sarana dan prasarana sekolah.';
            $stat = [
                ['Total Barang', Barang::count(), 'primary'],
                ['Kategori', Kategori::count(), 'primary'],
                ['Ruangan', Ruangan::count(), 'primary'],
                ['Pengguna Aktif', User::where('is_aktif', true)->count(), 'success'],
                ['Permintaan Belum Selesai', Permintaan::whereIn('status', $belumSelesai)->count(), 'warning'],
                ['Pengadaan Menunggu Keputusan', Pengadaan::where('status', 'diteruskan')->count(), 'warning'],
            ];
        } elseif ($user->role === 'petugas') {
            $terlambat = Peminjaman::where(function ($q) {
                $q->where('status', 'terlambat')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'dipinjam')
                            ->whereDate('tanggal_jatuh_tempo', '<', now()->toDateString());
                    });
            })->count();

            $judul = 'Dashboard Petugas';
            $deskripsi = 'Pekerjaan operasional sarana dan prasarana hari ini.';
            $stat = [
                ['Jenis Barang', Barang::count(), 'primary'],
                ['Stok Tersedia', (int) StokRuangan::sum('jumlah_tersedia'), 'success'],
                ['Barang Dipinjam', (int) StokRuangan::sum('jumlah_dipinjam'), 'primary'],
                ['Rusak / Diperbaiki', (int) StokRuangan::sum('jumlah_rusak') + (int) StokRuangan::sum('jumlah_diperbaiki'), 'danger'],
                ['Permintaan Baru', Permintaan::where('status', 'menunggu')->count(), 'warning'],
                ['Peminjaman Menunggu', Peminjaman::where('status', 'menunggu')->count(), 'warning'],
                ['Terlambat Dikembalikan', $terlambat, 'danger'],
            ];
        } else {
            $judul = 'Dashboard ' . ($user->role === 'guru' ? 'Guru' : 'Siswa');
            $deskripsi = 'Pantau permintaan dan peminjaman milik Anda.';
            $stat = [
                ['Permintaan Barang', Permintaan::where('pemohon_id', $user->id)->count(), 'primary'],
                ['Peminjaman Aktif', Peminjaman::where('peminjam_id', $user->id)->where('status', 'dipinjam')->count(), 'success'],
                ['Pengajuan Diproses', Peminjaman::where('peminjam_id', $user->id)->whereIn('status', ['menunggu', 'disetujui'])->count()
                    + Permintaan::where('pemohon_id', $user->id)->whereIn('status', $belumSelesai)->count(), 'warning'],
                ['Transaksi Selesai', Peminjaman::where('peminjam_id', $user->id)->where('status', 'dikembalikan')->count()
                    + Permintaan::where('pemohon_id', $user->id)->where('status', 'selesai')->count(), 'secondary'],
            ];
        }

        $aktivitas = in_array($user->role, ['admin', 'petugas'], true)
            ? LogAktivitas::with('user')->latest('created_at')->limit(8)->get()
            : collect();

        // 2. revisi di sini: nambahin query buat grafik narik data dari tabel kategori
        $kategori_grafik = Kategori::withCount('barang')->get();
        $label_grafik = $kategori_grafik->pluck('nama_kategori');
        $data_grafik = $kategori_grafik->pluck('barang_count');

        // 3. masukin variabel grafiknya ke compact biar kebaca di view blade
        return view('dashboard', compact('judul', 'deskripsi', 'stat', 'aktivitas', 'label_grafik', 'data_grafik'));
    }
}