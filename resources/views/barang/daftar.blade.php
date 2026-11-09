@extends('layouts.app')

@section('title', 'Daftar Barang')
@section('judul', 'Daftar Barang')
@section('deskripsi', 'Barang milik sekolah yang dapat dilihat ketersediaannya.')

@section('content')
    @php $siswa = auth()->user()->isSiswa(); @endphp

    <div class="card kartu-stat">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-12 col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama barang...">
                </div>
                <div class="col-6 col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-auto">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                    <a href="{{ route('barang.daftar') }}" class="btn btn-link">Reset</a>
                </div>
            </form>

            @if ($data->isEmpty())
                <div class="text-center text-muted py-5">
                    <div class="fw-semibold mb-1">Belum ada barang yang tercatat.</div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Nama Barang</th><th>Kategori</th><th>Ruangan</th><th class="text-end">Tersedia</th><th>Keterangan</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok->map(fn ($s) => $s->ruangan->nama_ruangan)->unique()->implode(', ') ?: '-' }}</td>
                                    <td class="text-end">{{ (int) $item->tersedia_sum }}</td>
                                    <td>
                                        @if ($item->khusus_guru)
                                            <span class="badge text-bg-info">Khusus Guru</span>
                                            @if ($siswa) <small class="text-muted">tidak dapat dipinjam Siswa</small> @endif
                                        @else
                                            <span class="text-muted small">-</span>
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
