@extends('layouts.app')
@section('judul', 'Peminjaman Barang')
@section('deskripsi', 'Kelola transaksi peminjaman sementara barang inventaris')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-dark fw-bold">Data Peminjaman</h4>
    @if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'petugas')
    <a href="{{ route('peminjaman.create') }}" class="btn btn-custom-primary shadow-sm"><i class="bi bi-plus-lg"></i> Ajukan Peminjaman</a>
    @endif
</div>

<div class="card-custom mb-4">
    <div class="card-body-custom p-0 table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-4">Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                    <th>Peminjam</th>
                    @endif
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td class="px-4">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                    <td><strong class="text-danger">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}</strong></td>
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                    <td><strong class="text-dark">{{ $p->user->name }}</strong></td>
                    @endif
                    <td>
                        @if($p->status == 'menunggu')
                            <span class="badge badge-custom bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Menunggu</span>
                        @elseif($p->status == 'disetujui')
                            <span class="badge badge-custom bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Dipinjam</span>
                        @elseif($p->status == 'dikembalikan')
                            <span class="badge badge-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">Selesai</span>
                        @else
                            <span class="badge badge-custom bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('peminjaman.show', $p->id) }}" class="btn btn-sm btn-custom-secondary">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        Belum ada data transaksi peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection