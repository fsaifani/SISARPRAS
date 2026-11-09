@extends('layouts.app')
@section('judul', 'Kategori Barang')
@section('deskripsi', 'Kelola pengelompokan jenis barang inventaris sekolah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-dark fw-bold">Daftar Kategori</h4>
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('kategori.create') }}" class="btn btn-custom-primary shadow-sm"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
    @endif
</div>

<div class="card-custom mb-4">
    <div class="card-body-custom p-0 table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-4">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    @if(auth()->user()->role == 'admin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $index => $item)
                <tr>
                    <td class="px-4">{{ $index + 1 }}</td>
                    <td><strong class="text-dark">{{ $item->nama }}</strong></td>
                    <td><span class="text-muted">{{ $item->deskripsi ?? '-' }}</span></td>
                    <td>
                        @if($item->is_aktif)
                            <span class="badge badge-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
                        @else
                            <span class="badge badge-custom bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Nonaktif</span>
                        @endif
                    </td>
                    @if(auth()->user()->role == 'admin')
                    <td class="text-center">
                        <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-sm btn-custom-secondary me-1"><i class="bi bi-pencil"></i> Edit</a>
                        
                        <form method="POST" action="{{ route('kategori.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-tags fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        Belum ada data kategori barang yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection