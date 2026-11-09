@extends('layouts.app')

@section('title', $item ? 'Ubah Barang' : 'Tambah Barang')
@section('judul', $item ? 'Ubah Barang' : 'Tambah Barang')
@section('deskripsi', $item ? 'Ubah informasi barang. Stok diatur dari halaman detail.' : 'Isi data barang beserta stok awalnya.')

@section('content')
    <div class="card kartu-stat" style="max-width: 720px;">
        <div class="card-body">
            <form method="POST" action="{{ $item ? route('barang.update', $item) : route('barang.store') }}" novalidate>
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $item->nama ?? '') }}"
                               class="form-control @error('nama') is-invalid @enderror" required autofocus>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="kode_barang" class="form-label">Kode Barang {{ $item ? '' : '(opsional)' }}</label>
                        <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang ?? '') }}"
                               class="form-control @error('kode_barang') is-invalid @enderror" placeholder="{{ $item ? '' : 'Kosongkan = otomatis' }}">
                        @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}" @selected(old('kategori_id', $item->kategori_id ?? '') == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @unless ($item)
                        <div class="col-12 col-md-6">
                            <label for="ruangan_id" class="form-label">Ruangan Penyimpanan</label>
                            <select id="ruangan_id" name="ruangan_id" class="form-select @error('ruangan_id') is-invalid @enderror" required>
                                <option value="">Pilih ruangan</option>
                                @foreach ($ruangan as $r)
                                    <option value="{{ $r->id }}" @selected(old('ruangan_id') == $r->id)>{{ $r->nama_ruangan }}</option>
                                @endforeach
                            </select>
                            @error('ruangan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-6">
                            <label for="jumlah_awal" class="form-label">Jumlah Awal</label>
                            <input type="number" id="jumlah_awal" name="jumlah_awal" min="1" value="{{ old('jumlah_awal') }}"
                                   class="form-control @error('jumlah_awal') is-invalid @enderror" required>
                            @error('jumlah_awal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-6">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <select id="kondisi" name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                <option value="baik" @selected(old('kondisi', 'baik') === 'baik')>Baik</option>
                                <option value="rusak" @selected(old('kondisi') === 'rusak')>Rusak</option>
                            </select>
                            @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endunless

                    <div class="col-12">
                        <label for="deskripsi" class="form-label">Keterangan (opsional)</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="khusus_guru" name="khusus_guru" value="1"
                                   @checked(old('khusus_guru', $item->khusus_guru ?? false))>
                            <label class="form-check-label" for="khusus_guru">Khusus Guru (tidak dapat dipinjam Siswa)</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ $item ? route('barang.show', $item) : route('barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
