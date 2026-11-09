@extends('layouts.app')
@section('judul', 'Pengadaan Barang')
@section('deskripsi', 'Daftar usulan pengadaan barang baru untuk inventaris')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-dark fw-bold">Usulan Pengadaan</h4>
    @if(auth()->user()->role == 'petugas')
    <a href="{{ route('pengadaan.create') }}" class="btn btn-custom-primary shadow-sm"><i class="bi bi-plus-lg"></i> Buat Usulan</a>
    @endif
</div>

<div class="card-custom mb-4">
    <div class="card-body-custom p-0 table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-4">Tanggal Pengajuan</th>
                    <th>Petugas Pengaju</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengadaan as $p)
                <tr>
                    {{-- Ubah tanggal_pengajuan jadi created_at biar ga error --}}
                    <td class="px-4">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</td>
                    <td><strong class="text-dark">{{ $p->user->name ?? 'User Tidak Ditemukan' }}</strong></td>
                    <td>
                        {{-- Logika status disesuaikan dengan nilai ENUM di database --}}
                        @if($p->status == 'diusulkan' || $p->status == 'menunggu')
                            <span class="badge badge-custom bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Menunggu Admin</span>
                        @elseif($p->status == 'disetujui')
                            <span class="badge badge-custom bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Disetujui / Menunggu Barang</span>
                        @elseif($p->status == 'selesai')
                            <span class="badge badge-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">Selesai (Barang Diterima)</span>
                        @elseif($p->status == 'ditolak')
                            <span class="badge badge-custom bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Ditolak</span>
                        @else
                            <span class="badge badge-custom bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('pengadaan.show', $p->id) }}" class="btn btn-sm btn-custom-secondary">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-bag-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        Belum ada data usulan pengadaan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection