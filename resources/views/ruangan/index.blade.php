@extends('layouts.app')

@section('title', 'Data Ruangan')
@section('judul', 'Data Ruangan')
@section('deskripsi', 'Lokasi penyimpanan barang di sekolah.')

@section('content')
    @php $bisaKelola = true; @endphp

    <div class="card kartu-stat">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-12 col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama ruangan...">
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-6 col-md-auto">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                    <a href="{{ route('ruangan.index') }}" class="btn btn-link">Reset</a>
                </div>
                @if ($bisaKelola)
                    <div class="col-12 col-md text-md-end">
                        <a href="{{ route('ruangan.create') }}" class="btn btn-primary">Tambah Ruangan</a>
                    </div>
                @endif
            </form>

            @if ($data->isEmpty())
                <div class="text-center text-muted py-5">
                    <div class="fw-semibold mb-1">Belum ada ruangan yang tercatat.</div>
                    @if ($bisaKelola)
                        <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-sm mt-2">Tambah Ruangan</a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th style="width:50px">No</th><th>Nama Ruangan</th><th>Keterangan Lokasi</th><th>Status</th><th style="width:210px">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $item)
                                <tr>
                                    <td>{{ $data->firstItem() + $i }}</td>
                                    <td class="fw-semibold">{{ $item->nama_ruangan }}</td>
                                    <td>{{ $item->keterangan_lokasi ?: '-' }}</td>
                                    <td>
                                        @if ($item->is_aktif)
                                            <span class="badge text-bg-success">Aktif</span>
                                        @else
                                            <span class="badge text-bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($bisaKelola)
                                            <a href="{{ route('ruangan.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form method="POST" action="{{ route('ruangan.status', $item) }}" class="d-inline"
                                                  onsubmit="return confirm('{{ $item->is_aktif ? 'Nonaktifkan' : 'Aktifkan kembali' }} ruangan ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $item->is_aktif ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    {{ $item->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Hanya lihat</span>
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
