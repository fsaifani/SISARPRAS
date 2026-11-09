@extends('layouts.app')
@section('judul', 'Laporan Sistem')
@section('deskripsi', 'Ringkasan data dan aktivitas sarana prasarana sekolah')

@section('content')
<div class="card-custom mb-4">
    <div class="card-body-custom p-4">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6 col-lg-4">
                <label class="form-label-custom">Pilih Jenis Laporan</label>
                <select name="jenis" class="form-select form-select-custom">
                    <option value="inventaris" {{ $jenis == 'inventaris' ? 'selected' : '' }}>Laporan Inventaris Barang</option>
                    <option value="peminjaman" {{ $jenis == 'peminjaman' ? 'selected' : '' }}>Laporan Peminjaman</option>
                    <option value="permintaan" {{ $jenis == 'permintaan' ? 'selected' : '' }}>Laporan Permintaan Barang</option>
                    <option value="pengadaan" {{ $jenis == 'pengadaan' ? 'selected' : '' }}>Laporan Pengadaan</option>
                    <option value="kerusakan" {{ $jenis == 'kerusakan' ? 'selected' : '' }}>Laporan Kerusakan & Pemeliharaan</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-8">
                <button type="submit" class="btn btn-custom-primary px-4"><i class="bi bi-funnel"></i> Tampilkan Laporan</button>
                <button type="button" onclick="window.print()" class="btn btn-custom-secondary px-4 ms-2"><i class="bi bi-printer"></i> Cetak PDF</button>
            </div>
        </form>
    </div>
</div>

<div class="card-custom mb-4">
    <div class="card-header-custom bg-light">
        <h5 class="card-title-custom mb-0 text-uppercase fs-6">Data {{ str_replace('_', ' ', $jenis) }}</h5>
    </div>
    <div class="card-body-custom p-0 table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-4" style="width: 5%">No</th>
                    <th>Rincian Data</th>
                    <th>Keterangan / Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr>
                    <td class="px-4">{{ $index + 1 }}</td>
                    <td>
                        @if($jenis == 'inventaris')
                            <strong class="text-dark">{{ $item->nama_barang }}</strong><br>
                            <span class="text-muted small">Kategori: {{ $item->kategori->nama_kategori ?? '-' }}</span>
                        @elseif($jenis == 'kerusakan')
                            <strong class="text-dark">{{ $item->barang->nama_barang }}</strong> ({{ $item->jumlah }} unit)<br>
                            <span class="text-muted small">Ruangan: {{ $item->ruangan->nama_ruangan ?? '-' }}</span>
                        @else
                            <strong class="text-dark">Pemohon/Peminjam: {{ $item->user->name }}</strong><br>
                            <span class="text-muted small">Tanggal: {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($jenis == 'inventaris')
                            <span class="text-muted">Kondisi: {{ ucfirst($item->kondisi) }}</span>
                        @else
                            <span class="badge badge-custom bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                {{ ucfirst(str_replace('_', ' ', $item->status ?? '-')) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                        <i class="bi bi-folder-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        Tidak ada data untuk laporan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection