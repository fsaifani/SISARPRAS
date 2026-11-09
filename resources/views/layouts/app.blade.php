<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SISARPRAS') - SMKN 1 Kota Bekasi</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    @php
        $user = auth()->user();
        $role = $user->role ?? 'tamu';
        $labelPeran = ucfirst($role);
    @endphp

    <div class="wrapper">
        <!-- SIDEBAR -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>SISARPRAS</h3>
                <button type="button" class="btn-close btn-close-white d-md-none" id="dismiss-sidebar" aria-label="Close"></button>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>

                <!-- MENU ADMIN -->
                @if($role === 'admin')
                    <p>Master Data</p>
                    <li>
                        <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> Kategori Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ruangan.index') }}" class="{{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                            <i class="bi bi-door-open"></i> Data Ruangan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Inventaris Barang
                        </a>
                    </li>

                    <p>Pengawasan Transaksi</p>
                    <li>
                        <a href="{{ route('permintaan.index') }}" class="{{ request()->routeIs('permintaan.*') ? 'active' : '' }}">
                            <i class="bi bi-cart"></i> Permintaan Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-left-right"></i> Peminjaman
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengadaan.index') }}" class="{{ request()->routeIs('pengadaan.*') ? 'active' : '' }}">
                            <i class="bi bi-bag-plus"></i> Usulan Pengadaan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kerusakan.index') }}" class="{{ request()->routeIs('kerusakan.*') ? 'active' : '' }}">
                            <i class="bi bi-tools"></i> Kerusakan & Perbaikan
                        </a>
                    </li>

                    <p>Sistem</p>
                    <li>
                        <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengguna.index') }}" class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> Manajemen Pengguna
                        </a>
                    </li>
                @endif

                <!-- MENU PETUGAS -->
                @if($role === 'petugas')
                    <p>Master Data</p>
                    <li>
                        <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> Kategori Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ruangan.index') }}" class="{{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                            <i class="bi bi-door-open"></i> Data Ruangan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Inventaris Barang
                        </a>
                    </li>

                    <p>Operasional</p>
                    <li>
                        <a href="{{ route('permintaan.index') }}" class="{{ request()->routeIs('permintaan.*') ? 'active' : '' }}">
                            <i class="bi bi-cart"></i> Permintaan Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-left-right"></i> Peminjaman & Kembali
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengadaan.index') }}" class="{{ request()->routeIs('pengadaan.*') ? 'active' : '' }}">
                            <i class="bi bi-bag-plus"></i> Usulan Pengadaan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kerusakan.index') }}" class="{{ request()->routeIs('kerusakan.*') ? 'active' : '' }}">
                            <i class="bi bi-tools"></i> Lapor Kerusakan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-muted"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</a>
                    </li>
                @endif

                <!-- MENU GURU & SISWA -->
                @if($role === 'guru' || $role === 'siswa')
                    <p>Layanan</p>
                    <li>
                        <a href="{{ route('barang.daftar') }}" class="{{ request()->routeIs('barang.daftar') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i> Daftar Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('permintaan.index') }}" class="{{ request()->routeIs('permintaan.*') ? 'active' : '' }}">
                            <i class="bi bi-cart-plus"></i> Permintaan Baru
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar2-week"></i> Peminjaman Barang
                        </a>
                    </li>
                    <!-- INI MENU LAPOR KERUSAKAN YANG BARU DITAMBAHKAN -->
                    <li>
                        <a href="{{ route('kerusakan.index') }}" class="{{ request()->routeIs('kerusakan.*') ? 'active' : '' }}">
                            <i class="bi bi-tools"></i> Lapor Kerusakan
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-user-box mt-auto">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    </div>
                    <div class="flex-grow-1 ms-3 overflow-hidden">
                        <h6 class="mb-0 text-white text-truncate">{{ $user->name ?? 'User' }}</h6>
                        <small class="text-info">{{ $labelPeran }}</small>
                    </div>
                </div>
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 d-none d-sm-inline">{{ $user->name ?? 'User' }}</span>
                                <i class="bi bi-person-circle fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="container-fluid p-4 p-md-5">
                <!-- Header Konten (Judul & Deskripsi) -->
                <div class="mb-4">
                    <h2 class="fw-bold text-dark">@yield('judul', 'Dashboard')</h2>
                    <p class="text-muted">@yield('deskripsi')</p>
                </div>

                <!-- Alert Notifikasi -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Yield Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS & Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const dismissSidebar = document.getElementById('dismiss-sidebar');

            sidebarCollapse.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });

            if (dismissSidebar) {
                dismissSidebar.addEventListener('click', function() {
                    sidebar.classList.add('active');
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>