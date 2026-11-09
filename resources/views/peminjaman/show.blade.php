@extends('layouts.app')
@section('judul', 'Detail Peminjaman')
@section('deskripsi', 'Rincian data pengajuan peminjaman barang inventaris.')

@section('content')
<div class="mb-4">
    <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke daftar peminjaman</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title fw-bold mb-0">Informasi Pengajuan Tiket</h5>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="30%" class="text-muted">Nomor Transaksi</th>
                        <td class="fw-bold text-primary">{{ $peminjaman->nomor ?? 'PMJ-Otomatis' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Nama Peminjam</th>
                        <td>{{ $peminjaman->user->name ?? 'Pengguna Sistem' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal Pakai</th>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_rencana_pakai)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Batas Kembali</th>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Alasan Peminjaman</th>
                        <td>{{ $peminjaman->alasan }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status Saat Ini</th>
                        <td>
                            @if($peminjaman->status == 'menunggu')
                                <span class="badge bg-warning text-dark px-3 py-2">Menunggu Persetujuan</span>
                            @elseif($peminjaman->status == 'disetujui')
                                <span class="badge bg-success px-3 py-2">Disetujui Petugas</span>
                            @elseif($peminjaman->status == 'ditolak')
                                <span class="badge bg-danger px-3 py-2">Ditolak</span>
                            @elseif($peminjaman->status == 'dikembalikan')
                                <span class="badge bg-secondary px-3 py-2">Selesai / Dikembalikan</span>
                            @else
                                <span class="badge bg-light text-dark border px-3 py-2">{{ ucfirst($peminjaman->status) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title fw-bold mb-0">Rincian Barang Yang Dipinjam</h5>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Barang</th>
                            <th class="text-center pe-4">Jumlah Diminta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman->detailPeminjaman as $index => $detail)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <!-- Ini solusi biar nama barangnya muncul, manggil $detail->barang->nama sesuai database lu -->
                            <td class="fw-semibold text-primary">
                                {{ $detail->barang->nama ?? $detail->barang->data_barang ?? 'Data barang tidak terbaca' }}
                            </td>
                            <td class="text-center pe-4"><span class="badge bg-light text-dark border">{{ $detail->jumlah }} Unit</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Tidak ada rincian barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title fw-bold mb-0">Tindakan Petugas</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peminjaman.persetujuan', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Persetujuan Peminjaman</label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Pilih Tindakan --</option>
                            <option value="disetujui" {{ $peminjaman->status == 'disetujui' ? 'selected' : '' }}>Setujui Peminjaman</option>
                            <option value="ditolak" {{ $peminjaman->status == 'ditolak' ? 'selected' : '' }}>Tolak Peminjaman</option>
                            <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Barang Dikembalikan (Selesai)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-2"></i> Simpan Status</button>
                </form>
            </div>
        </div>
        @else
         <div class="card border-0 shadow-sm bg-primary bg-opacity-10 border-start border-primary border-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle-fill me-2"></i> Informasi Pengajuan</h6>
                <p class="small text-muted mb-0">Pengajuan Anda sedang diproses. Silakan hubungi petugas atau admin Sarpras SMKN 1 jika status belum berubah dalam 1x24 jam.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection