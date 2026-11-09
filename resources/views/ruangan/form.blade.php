@extends('layouts.app')

@section('title', $item ? 'Ubah Ruangan' : 'Tambah Ruangan')
@section('judul', $item ? 'Ubah Ruangan' : 'Tambah Ruangan')
@section('deskripsi', 'Isi data dengan benar, lalu simpan.')

@section('content')
    <div class="card kartu-stat" style="max-width: 640px;">
        <div class="card-body">
            <form method="POST" action="{{ $item ? route('ruangan.update', $item) : route('ruangan.store') }}" novalidate>
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                    <input type="text" id="nama_ruangan" name="nama_ruangan" value="{{ old('nama_ruangan', $item->nama_ruangan ?? '') }}"
                           class="form-control @error('nama_ruangan') is-invalid @enderror" required autofocus>
                    @error('nama_ruangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan_lokasi" class="form-label">Keterangan Lokasi (opsional)</label>
                    <input type="text" id="keterangan_lokasi" name="keterangan_lokasi" value="{{ old('keterangan_lokasi', $item->keterangan_lokasi ?? '') }}" class="form-control @error('keterangan_lokasi') is-invalid @enderror">
                    @error('keterangan_lokasi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('ruangan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
