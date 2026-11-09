@extends('layouts.app')
@section('judul', 'Buat Usulan Pengadaan')
@section('deskripsi', 'Formulir pengajuan pembelian barang inventaris baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('pengadaan.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke daftar pengadaan</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-body-custom p-4 p-md-5">
                <form action="{{ route('pengadaan.store') }}" method="POST">
                    @csrf
                    
                    <div id="barang-container">
                        <div class="row align-items-end mb-3 barang-row">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label class="form-label-custom">Pilih Barang <span class="text-danger">*</span></label>
                                <select name="barang_id[]" class="form-select form-control-custom" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_barang ?? $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Jumlah Usulan <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah[]" min="1" class="form-control form-control-custom" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <button type="button" id="btn-tambah" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i> Tambah Barang Lain</button>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label-custom">Alasan Pengadaan <span class="text-danger">*</span></label>
                        <textarea name="alasan" rows="3" class="form-control form-control-custom" placeholder="Jelaskan kenapa barang ini harus dibeli..." required></textarea>
                    </div>
                    
                    <div class="d-flex gap-3 pt-4 border-top">
                        <button type="submit" class="btn btn-custom-primary px-4 py-2"><i class="bi bi-send me-2"></i> Kirim Usulan ke Admin</button>
                        <a href="{{ route('pengadaan.index') }}" class="btn btn-custom-secondary px-4 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btn-tambah').addEventListener('click', function() {
        let row = document.querySelector('.barang-row').cloneNode(true);
        row.querySelector('select').value = '';
        row.querySelector('input').value = '';
        document.getElementById('barang-container').appendChild(row);
    });
</script>
@endsection