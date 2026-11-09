@extends('layouts.app')

@section('title', 'Inventaris Barang')
@section('judul', 'Inventaris Barang')
@section('deskripsi', 'Seluruh barang milik sekolah beserta stok di setiap ruangan.')

@section('content')
    @php $bisaKelola = auth()->user()->isPetugas(); @endphp

    @if (session('gagal'))
        <div class="alert alert-danger">{{ session('gagal') }}</div>
    @endif

    <div class="card kartu-stat">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-12 col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau kode barang...">
                </div>
                <div class="col-6 col-md-2">
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="ruangan_id" class="form-select">
                        <option value="">Semua ruangan</option>
                        @foreach ($ruangan as $r)
                            <option value="{{ $r->id }}" @selected(request('ruangan_id') == $r->id)>{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="kondisi" class="form-select">
                        <option value="">Semua kondisi</option>
                        <option value="baik" @selected(request('kondisi') === 'baik')>Ada stok baik</option>
                        <option value="rusak" @selected(request('kondisi') === 'rusak')>Ada yang rusak</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-link">Reset</a>
                    @if ($bisaKelola)
                        <a href="{{ route('barang.create') }}" class="btn btn-primary float-md-end">Tambah Barang</a>
                    @endif
                </div>
            </form>

            @if ($data->isEmpty())
                <div class="text-center text-muted py-5">
                    <div class="fw-semibold mb-1">Belum ada barang yang tercatat.</div>
                    @if ($bisaKelola)
                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm mt-2">Tambah Barang</a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Ruangan</th>
                                <th class="text-end">Total</th><th class="text-end">Tersedia</th><th class="text-end">Dipinjam</th>
                                <th class="text-end">Rusak / Diperbaiki</th><th>Kondisi</th><th>Status</th><th style="width:230px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                @php
                                    $tersedia = (int) $item->tersedia_sum;
                                    $dicadangkan = (int) $item->dicadangkan_sum;
                                    $dipinjam = (int) $item->dipinjam_sum;
                                    $rusak = (int) $item->rusak_sum + (int) $item->diperbaiki_sum;
                                    $total = $tersedia + $dicadangkan + $dipinjam + $rusak;
                                @endphp
                                <tr>
                                    <td class="text-nowrap">{{ $item->kode_barang ?: '-' }}</td>
                                    <td class="fw-semibold">
                                        {{ $item->nama }}
                                        @if ($item->khusus_guru)
                                            <span class="badge text-bg-info">Khusus Guru</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok->map(fn ($s) => $s->ruangan->nama_ruangan)->unique()->implode(', ') ?: '-' }}</td>
                                    <td class="text-end">{{ $total }}</td>
                                    <td class="text-end">{{ $tersedia }}</td>
                                    <td class="text-end">{{ $dipinjam }}@if ($dicadangkan > 0) <small class="text-muted">(+{{ $dicadangkan }} dicadangkan)</small>@endif</td>
                                    <td class="text-end">{{ $rusak }}</td>
                                    <td>
                                        @if ($rusak > 0)
                                            <span class="badge text-bg-warning">Ada yang rusak</span>
                                        @else
                                            <span class="badge text-bg-success">Baik</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->is_aktif)
                                            <span class="badge text-bg-success">Aktif</span>
                                        @else
                                            <span class="badge text-bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('barang.show', $item) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                        @if ($bisaKelola)
                                            <a href="{{ route('barang.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form method="POST" action="{{ route('barang.status', $item) }}" class="d-inline"
                                                  onsubmit="return confirm('{{ $item->is_aktif ? 'Nonaktifkan' : 'Aktifkan kembali' }} barang ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $item->is_aktif ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    {{ $item->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $data->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
@endsection
