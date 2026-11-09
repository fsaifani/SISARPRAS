@extends('layouts.app')
@section('judul', 'Detail Permintaan')
@section('deskripsi', 'Rincian pengajuan barang dan status persetujuan')

@section('content')
<div class="mb-3">
    <a href="{{ route('permintaan.index') }}" class="text-decoration-none text-muted">&larr; Kembali ke daftar</a>
</div>

<div class="row">
    <div class="col-md-7 mb-4">
        <div class="card kartu-stat h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Pengajuan</h5>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Pemohon</div>
                    <div class="col-sm-8 fw-semibold">{{ $permintaan->user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Tanggal</div>
                    <div class="col-sm-8 fw-semibold">{{ \Carbon\Carbon::parse($permintaan->tanggal_pengajuan)->format('d M Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Alasan</div>
                    <div class="col-sm-8">{{ $permintaan->alasan }}</div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted small">Status</div>
                    <div class="col-sm-8">
                        @if($permintaan->status == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @elseif($permintaan->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">{{ ucwords(str_replace('_', ' ', $permintaan->status)) }}</span>
                        @endif
                    </div>
                </div>

                <h6 class="fw-bold mb-3 border-top pt-4">Daftar Barang yang Diminta:</h6>
                <ul class="list-group list-group-flush">
                    @foreach($permintaan->detailPermintaan as $detail)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $detail->jumlah }} Unit</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @if(auth()->user()->role == 'petugas' && $permintaan->status == 'menunggu')
    <div class="col-md-5 mb-4">
        <div class="card kartu-stat border-primary h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold text-primary mb-3">Tindakan Petugas</h5>
                <p class="text-muted small mb-4">Pilih keputusan untuk pengajuan ini. Jika disetujui, stok barang akan otomatis berkurang dari sistem.</p>
                
                <form action="{{ route('permintaan.proses', $permintaan->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keputusan</label>
                        <select name="status" class="form-select border-primary" required>
                            <option value="">-- Pilih Tindakan --</option>
                            <option value="disetujui">Setujui & Potong Stok</option>
                            <option value="ditolak">Tolak Pengajuan</option>
                            <option value="usul_pengadaan">Stok Kosong (Usulkan Pengadaan)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Simpan Keputusan</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection