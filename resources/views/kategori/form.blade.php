@extends('layouts.app')
@section('judul', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')
@section('deskripsi', 'Formulir pengelolaan data kelompok barang inventaris')

@section('content')
<div class="mb-4">
    <a href="{{ route('kategori.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke daftar kategori</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-body-custom p-4 p-md-5">
                <form action="{{ isset($kategori) ? route('kategori.update', $kategori->id) : route('kategori.store') }}" method="POST">
                    @csrf
                    @if(isset($kategori))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-custom" value="{{ old('nama', $kategori->nama ?? '') }}" required placeholder="Contoh: Elektronik, Mebel, Alat Olahraga">
                        <div class="form-text small text-muted mt-2">Nama kategori tidak boleh kosong dan sebaiknya spesifik.</div>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label-custom">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" rows="4" class="form-control form-control-custom" placeholder="Keterangan tambahan mengenai barang-barang di kategori ini...">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-3 pt-4 border-top">
                        <button type="submit" class="btn btn-custom-primary px-4 py-2"><i class="bi bi-save me-2"></i> Simpan Kategori</button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-custom-secondary px-4 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection