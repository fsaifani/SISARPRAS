@extends('layouts.app')
@section('judul', 'Detail Pengadaan')
@section('deskripsi', 'Rincian usulan pengadaan barang inventaris')

@section('content')
<div class="mb-4">
    <a href="{{ route('pengadaan.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke daftar pengadaan</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom mb-4">
            <div class="card-body-custom p-4">
                <h5 class="fw-bold mb-4">Informasi Pengajuan</h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="200" class="text-muted">Nomor Usulan</td>
                        <td>: <strong class="text-dark">{{ $pengadaan->nomor }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Petugas Pengaju</td>
                        <td>: <strong class="text-dark">{{ $pengadaan->user->name ?? 'User Tidak Ditemukan' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Usulan</td>
                        <td>: <strong class="text-dark">{{ \Carbon\Carbon::parse($pengadaan->created_at)->format('d M Y H:i') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alasan Pengadaan</td>
                        <td>: <strong class="text-dark">{{ $pengadaan->catatan_admin ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>: 
                            @if($pengadaan->status == 'diusulkan' || $pengadaan->status == 'menunggu')
                                <span class="badge badge-custom bg-warning bg-opacity-10 text-warning border border-warning">Menunggu Admin</span>
                            @elseif($pengadaan->status == 'disetujui')
                                <span class="badge badge-custom bg-primary bg-opacity-10 text-primary border border-primary">Disetujui</span>
                            @elseif($pengadaan->status == 'selesai')
                                <span class="badge badge-custom bg-success bg-opacity-10 text-success border border-success">Selesai (Diterima)</span>
                            @else
                                <span class="badge badge-custom bg-danger bg-opacity-10 text-danger border border-danger">{{ ucfirst($pengadaan->status) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-body-custom p-0 table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Diajukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaan->detailPengadaan as $index => $detail)
                        <tr>
                            <td class="px-4">{{ $index + 1 }}</td>
                            <td><strong class="text-dark">{{ $detail->nama_barang }}</strong></td>
                            <td>{{ $detail->jumlah_diajukan }} Unit</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada rincian barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mt-4 mt-lg-0">
        {{-- Formulir Keputusan khusus untuk Admin --}}
        @if(auth()->user()->role == 'admin' && ($pengadaan->status == 'diusulkan' || $pengadaan->status == 'menunggu'))
        <div class="card-custom">
            <div class="card-body-custom p-4">
                <h5 class="fw-bold mb-3">Tindak Lanjut Admin</h5>
                <form action="{{ route('pengadaan.persetujuan', $pengadaan->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label-custom">Keputusan</label>
                        <select name="status" class="form-select form-control-custom" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="disetujui">Setujui Usulan</option>
                            <option value="ditolak">Tolak Usulan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-custom-primary w-100"><i class="bi bi-save me-2"></i> Simpan Keputusan</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Formulir Penerimaan Barang khusus untuk Petugas setelah disetujui admin --}}
        @if(auth()->user()->role == 'petugas' && $pengadaan->status == 'disetujui')
        <div class="card-custom">
            <div class="card-body-custom p-4">
                <h5 class="fw-bold mb-3">Penerimaan Barang</h5>
                <p class="text-muted small mb-3">Barang telah disetujui admin. Klik tombol di bawah jika fisik barang sudah sampai untuk menambah stok inventaris secara otomatis.</p>
                <form action="{{ route('pengadaan.terima', $pengadaan->id) }}" method="POST" onsubmit="return confirm('Yakin barang sudah diterima dan ingin menambahkan ke inventaris?')">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-box-seam me-2"></i> Barang Sudah Diterima</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection