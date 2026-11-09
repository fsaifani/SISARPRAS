@extends('layouts.app')
@section('judul', $judul)
@section('deskripsi', $deskripsi)

@section('content')
@php
    $role = auth()->user()->role;
    $nama = auth()->user()->name;

    // logic notifikasi otomatis (ganti 'peminjaman' dan 'tanggal_kembali' sesuai nama di database lu)
    $jumlahTelat = \Illuminate\Support\Facades\DB::table('peminjaman')
        ->where('tanggal_kembali', '<=', now()->toDateString()) // pake '<=' biar otomatis ke-trigger kalau pinjam hari ini
        ->where('status', 'dipinjam') // sesuaikan status belum kembali di tabel lu
        ->count();
@endphp

<!-- NOTIFIKASI KETERLAMBATAN -->
@if($jumlahTelat > 0)
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="background-color: #fef2f2; border: 1px solid #f87171; border-left: 5px solid #ef4444;">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger me-3"></i>
        <div>
            <strong class="text-danger">Peringatan Sistem: Keterlambatan Pengembalian!</strong><br>
            <span class="small text-dark">
                Sistem mendeteksi terdapat <b>{{ $jumlahTelat }} peminjaman</b> yang telah mencapai atau melewati batas waktu pengembalian. Harap segera diproses!
            </span>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- ======================================================= -->
<!-- BAGIAN ATAS: KARTU STATISTIK (UNTUK SEMUA ROLE) -->
<!-- ======================================================= -->

<!-- ======================================================= -->
<!-- BAGIAN ATAS: KARTU STATISTIK (UNTUK SEMUA ROLE) -->
<!-- ======================================================= -->
<div class="row g-4 mb-4">
    @foreach($stat as $item)
        @php
            $label = $item[0];
            $nilai = $item[1];
            $warna = $item[2]; 
            
            $icon = 'bi-info-circle';
            if(stripos($label, 'barang') !== false || stripos($label, 'stok') !== false) $icon = 'bi-box-seam';
            elseif(stripos($label, 'pengguna') !== false) $icon = 'bi-people';
            elseif(stripos($label, 'ruangan') !== false) $icon = 'bi-door-open';
            elseif(stripos($label, 'kategori') !== false) $icon = 'bi-tags';
            elseif(stripos($label, 'permintaan') !== false || stripos($label, 'pengadaan') !== false) $icon = 'bi-cart-check';
            elseif(stripos($label, 'peminjaman') !== false || stripos($label, 'transaksi') !== false) $icon = 'bi-calendar2-week';
            elseif(stripos($label, 'rusak') !== false || stripos($label, 'terlambat') !== false) $icon = 'bi-exclamation-triangle';
            elseif(stripos($label, 'selesai') !== false) $icon = 'bi-check-circle';
        @endphp
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom h-100 border-0 shadow-sm transition-hover border-kiri-{{ $warna }}">
                <div class="card-body-custom stat-card p-4 d-flex align-items-center">
                    <div class="stat-icon {{ $warna }} bg-{{ $warna }} bg-opacity-10 text-{{ $warna }} fs-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="stat-details ms-3">
                        <p class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem;">{{ $label }}</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $nilai }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- ======================================================= -->
<!-- BAGIAN BAWAH: ADMIN & PETUGAS (GRAFIK & LOG AKTIVITAS)-->
<!-- ======================================================= -->
@if($role === 'admin' || $role === 'petugas')
<div class="row g-4">
    <!-- Kiri: Grafik Statistik -->
    <div class="col-lg-7">
        <div class="card-custom border-0 shadow-sm h-100">
            <div class="card-header-custom bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title-custom fw-bold mb-0">Grafik Aktivitas Sistem</h5>
                <small class="text-muted">Overview</small>
            </div>
            <div class="card-body-custom p-4">
                <canvas id="transaksiChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Kanan: Log Aktivitas Terbaru -->
    <div class="col-lg-5">
        <div class="card-custom border-0 shadow-sm h-100">
            <div class="card-header-custom bg-white border-bottom p-4">
                <h5 class="card-title-custom fw-bold mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body-custom p-0">
                <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                    @forelse($aktivitas as $log)
                    <div class="list-group-item p-3 d-flex align-items-start border-bottom">
                        <div class="bg-light p-2 rounded-circle me-3 text-secondary mt-1">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-dark small fw-medium">{{ $log->deskripsi ?? 'Melakukan aktivitas sistem' }}</p>
                            <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $log->user->name ?? 'Sistem' }} &bull; {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-clipboard-x fs-1 d-block mb-3 opacity-50"></i>
                        <small>Belum ada log aktivitas tercatat.</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@php 
    $chartLabels = [];
    $chartData = [];
    foreach($stat as $item) {
        $chartLabels[] = $item[0];
        $chartData[] = $item[1];
    }
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('transaksiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: JSON.parse('{!! json_encode($chartLabels) !!}'),
                datasets: [{
                    label: 'Total',
                    data: JSON.parse('{!! json_encode($chartData) !!}'),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    });
</script>
@endif
<!-- ======================================================= -->
<!-- BAGIAN BAWAH: GURU & SISWA (MENU JALAN PINTAS) -->
<!-- ======================================================= -->
@if($role === 'guru' || $role === 'siswa')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
            <div class="card-body-custom p-4 p-md-5 text-white position-relative">
                <div style="position: relative; z-index: 2;">
                    <h3 class="fw-bold mb-2">Halo, {{ $nama }}!</h3>
                    <p class="mb-4 opacity-75" style="max-width: 600px;">Selamat datang di portal layanan Sarana dan Prasarana. Anda dapat mencari barang inventaris, mengajukan permintaan barang (ATK), atau melakukan peminjaman aset (Proyektor, Laptop) dengan mudah.</p>
                    <a href="{{ route('barang.daftar') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-search me-2"></i> Cari Aset Sekolah
                    </a>
                </div>
                <i class="bi bi-boxes position-absolute" style="font-size: 12rem; opacity: 0.1; right: -20px; bottom: -40px; z-index: 1;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card-custom border-0 shadow-sm h-100 p-4 transition-hover">
            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-cart-plus text-primary me-2"></i> Minta Barang Habis Pakai</h5>
            <p class="text-muted small mb-3">Ajukan kebutuhan ATK, spidol, kertas, atau kebutuhan kelas lainnya yang bersifat habis pakai.</p>
            <a href="{{ route('permintaan.create') }}" class="btn btn-sm btn-custom-primary rounded-pill px-3">Buat Pengajuan Permintaan &rarr;</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom border-0 shadow-sm h-100 p-4 transition-hover">
            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-calendar2-week text-info me-2"></i> Pinjam Aset Sementara</h5>
            <p class="text-muted small mb-3">Pinjam laptop, proyektor, mic, atau alat praktik dari ruang sarpras yang harus dikembalikan.</p>
            <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">Buat Peminjaman Aset &rarr;</a>
        </div>
    </div>
</div>
@endif

<style>
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important; }
    
    .border-kiri-primary { border-left: 4px solid var(--primary-main) !important; }
    .border-kiri-success { border-left: 4px solid var(--success) !important; }
    .border-kiri-warning { border-left: 4px solid var(--warning) !important; }
    .border-kiri-danger { border-left: 4px solid var(--danger) !important; }
    .border-kiri-secondary { border-left: 4px solid #6c757d !important; }
</style>
@endsection