<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KerusakanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
});

// hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
});

// hanya untuk pengguna yang sudah login (SEMUA BISA AKSES)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // master data
    // ==========================================

    // kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index')->middleware('role:admin,petugas');
    Route::middleware('role:admin')->group(function () {
        Route::get('/kategori/tambah', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{item}/ubah', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{item}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::patch('/kategori/{item}/status', [KategoriController::class, 'status'])->name('kategori.status');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    });

    // ruangan
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index')->middleware('role:admin,petugas');
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/ruangan/tambah', [RuanganController::class, 'create'])->name('ruangan.create');
        Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
        Route::get('/ruangan/{item}/ubah', [RuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('/ruangan/{item}', [RuanganController::class, 'update'])->name('ruangan.update');
        Route::patch('/ruangan/{item}/status', [RuanganController::class, 'status'])->name('ruangan.status');
    });

    // inventaris barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index')->middleware('role:admin,petugas');
    Route::middleware('role:petugas')->group(function () {
        Route::get('/barang/tambah', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/{barang}/ubah', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
        Route::patch('/barang/{barang}/status', [BarangController::class, 'status'])->name('barang.status');
        Route::post('/barang/{barang}/stok/sesuaikan', [BarangController::class, 'sesuaikan'])->name('barang.sesuaikan');
        Route::post('/barang/{barang}/stok/pindah', [BarangController::class, 'pindah'])->name('barang.pindah');
    });
    Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show')->middleware('role:admin,petugas');

    // daftar barang untuk guru dan siswa (hanya lihat)
    Route::get('/daftar-barang', [BarangController::class, 'daftar'])->name('barang.daftar')->middleware('role:guru,siswa');

    // ==========================================
    // fitur transaksi utama sisarpras
    // ==========================================

    // permintaan barang (semua yang login bisa akses)
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/tambah', [PermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('/permintaan', [PermintaanController::class, 'store'])->name('permintaan.store');

    // khusus petugas nge-acc permintaan
    Route::middleware('role:petugas')->group(function () {
        Route::get('/permintaan/{permintaan}', [PermintaanController::class, 'show'])->name('permintaan.show');
        Route::patch('/permintaan/{permintaan}/proses', [PermintaanController::class, 'proses'])->name('permintaan.proses');
    });

    // pengadaan barang (cuma admin dan petugas)
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index')->middleware('role:admin,petugas');
    Route::middleware('role:petugas')->group(function () {
        Route::get('/pengadaan/tambah', [PengadaanController::class, 'create'])->name('pengadaan.create');
        Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
        Route::post('/pengadaan/{pengadaan}/terima', [PengadaanController::class, 'terima'])->name('pengadaan.terima');
    });
    Route::middleware('role:admin')->group(function () {
        Route::get('/pengadaan/{pengadaan}', [PengadaanController::class, 'show'])->name('pengadaan.show');
        Route::patch('/pengadaan/{pengadaan}/persetujuan', [PengadaanController::class, 'persetujuan'])->name('pengadaan.persetujuan');
    });

    // peminjaman & pengembalian (semua yang login bisa akses)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/tambah', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

    // khusus petugas nge-acc pinjaman
    Route::middleware('role:petugas')->group(function () {
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::patch('/peminjaman/{peminjaman}/persetujuan', [PeminjamanController::class, 'persetujuan'])->name('peminjaman.persetujuan');
        Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');
    });

    // ==========================================
    // kerusakan dan pemeliharaan (SUDAH DIUBAH)
    // ==========================================

    // Semua user (Admin, Petugas, Guru, Siswa) berhak buka halaman lapor dan ngelaporin
    Route::get('/kerusakan', [KerusakanController::class, 'index'])->name('kerusakan.index');
    Route::get('/kerusakan/create', [KerusakanController::class, 'create'])->name('kerusakan.create');
    Route::post('/kerusakan', [KerusakanController::class, 'store'])->name('kerusakan.store');

    // Cuma Petugas / Admin yang berhak ubah status perbaikannya
    Route::middleware('role:admin,petugas')->group(function () {
        Route::patch('/kerusakan/{id}/proses', [KerusakanController::class, 'updateProses'])->name('kerusakan.updateProses');
    });

    // ==========================================
    // fitur sistem
    // ==========================================

    // laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index')->middleware('role:admin,petugas');

    // manajemen pengguna
    Route::middleware('role:admin')->group(function () {
        Route::resource('pengguna', UserController::class)->except(['create', 'store']);
        Route::patch('/pengguna/{user}/status', [UserController::class, 'status'])->name('pengguna.status');
    });
});