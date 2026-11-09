@extends('layouts.app')
@section('judul', 'Permintaan Barang')
@section('deskripsi', 'Kelola pengajuan permintaan barang (barang habis pakai)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-dark fw-bold">Riwayat Pengajuan</h4>
    @if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'petugas')
    <a href="{{ route('permintaan.create') }}" class="btn btn-primary shadow-sm">+ Buat Pengajuan</a>
    @endif
</div>

<div class="card kartu-stat mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Tanggal</th>
                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                        <th>Pemohon</th>
                        @endif
                        <th>Alasan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $p)
                    <tr>
                        <td class="px-4">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                        <td><strong class="text-dark">{{ $p->user->name }}</strong></td>
                        @endif
                        <td><span class="text-muted d-inline-block text-truncate" style="max-width: 200px;">{{ $p->alasan }}</span></td>
                        <td>
                            @if($p->status == 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">{{ ucwords(str_replace('_', ' ', $p->status)) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('permintaan.show', $p->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data permintaan barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection