@extends('layouts.app')
@section('judul', 'Lapor Kerusakan')
@section('deskripsi', 'Formulir pencatatan fasilitas atau barang sekolah yang rusak')

@section('content')
<div class="card-custom mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('kerusakan.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pilih Barang Yang Rusak</label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        <!-- Panggil pake $b->nama sesuai isi database lu -->
                        @foreach($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi Ruangan</label>
                    <select name="ruangan_id" class="form-select" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jumlah Barang Rusak</label>
                    <input type="number" name="jumlah" class="form-control" min="1" placeholder="Contoh: 2" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Foto Bukti Kerusakan (Wajib Dilampirkan)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Biar petugas gampang ngecek fisiknya. (Format: JPG, PNG)</small>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Deskripsi Detail Kerusakan</label>
                <textarea name="deskripsi_kerusakan" class="form-control" rows="4" placeholder="Contoh: Kaki kursi patah, Proyektor mati total tidak bisa menyala..." required></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Kirim Laporan</button>
                <a href="{{ route('kerusakan.index') }}" class="btn btn-light px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection