@extends('layouts.app')
@section('judul', 'Proses Perbaikan')
@section('deskripsi', 'Perbarui status penanganan barang rusak')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card kartu-stat mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Detail Laporan</h5>
                <p class="mb-1 text-muted small">Barang & Jumlah</p>
                <p class="fw-semibold">{{ $kerusakan->barang->nama_barang }} ({{ $kerusakan->jumlah }} unit)</p>
                
                <p class="mb-1 text-muted small mt-3">Lokasi</p>
                <p class="fw-semibold">{{ $kerusakan->ruangan->nama_ruangan }}</p>
                
                <p class="mb-1 text-muted small mt-3">Deskripsi</p>
                <p class="fw-semibold">{{ $kerusakan->deskripsi }}</p>
                
                <p class="mb-1 text-muted small mt-3">Status Saat Ini</p>
                <p class="fw-bold text-primary text-uppercase">{{ str_replace('_', ' ', $kerusakan->status) }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card kartu-stat">
            <div class="card-body p-4">
                <form action="{{ route('kerusakan.update', $kerusakan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Update Status</label>
                        <select name="status" class="form-select" required>
                            <option value="dilaporkan" {{ $kerusakan->status == 'dilaporkan' ? 'selected' : '' }}>Dilaporkan (Belum Diproses)</option>
                            <option value="sedang_diperbaiki" {{ $kerusakan->status == 'sedang_diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki</option>
                            <option value="selesai" {{ $kerusakan->status == 'selesai' ? 'selected' : '' }}>Selesai Diperiksa/Diperbaiki</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hasil Pemeriksaan Akhir</label>
                        <textarea name="hasil_pemeriksaan" rows="2" class="form-control" placeholder="Contoh: Berhasil diservis, atau rusak total.">{{ $kerusakan->hasil_pemeriksaan }}</textarea>
                        <small class="text-muted">Isi jika status sudah selesai.</small>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="layak_digunakan" value="1" id="layakCheck">
                        <label class="form-check-label text-muted" for="layakCheck">
                            Centang ini jika barang layak digunakan kembali (stok akan dipulihkan otomatis).
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary px-4">Simpan Pembaruan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection