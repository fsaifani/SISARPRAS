@extends('layouts.app')

@section('title', $barang->nama)
@section('judul', 'Detail Barang')
@section('deskripsi', 'Informasi, stok per ruangan, dan riwayat perubahan stok.')

@section('content')
    @php $bisaKelola = auth()->user()->isPetugas(); @endphp

    @if (session('gagal'))
        <div class="alert alert-danger">{{ session('gagal') }}</div>
    @endif

    <div class="card kartu-stat mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div>
                    <h2 class="h5 mb-1">{{ $barang->nama }}
                        @if ($barang->khusus_guru) <span class="badge text-bg-info">Khusus Guru</span> @endif
                        @if ($barang->is_aktif) <span class="badge text-bg-success">Aktif</span> @else <span class="badge text-bg-secondary">Nonaktif</span> @endif
                    </h2>
                    <div class="text-muted small">Kode: {{ $barang->kode_barang ?: '-' }} | Kategori: {{ $barang->kategori->nama ?? '-' }}</div>
                    <div class="mt-2">{{ $barang->deskripsi ?: 'Tidak ada keterangan.' }}</div>
                </div>
                <div>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                    @if ($bisaKelola)
                        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card kartu-stat mb-3">
        <div class="card-header bg-white fw-semibold">Stok per Ruangan</div>
        <div class="card-body">
            @if ($barang->stok->isEmpty())
                <div class="text-muted text-center py-3">Belum ada stok tercatat.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Ruangan</th><th class="text-end">Tersedia</th><th class="text-end">Dicadangkan</th><th class="text-end">Dipinjam</th><th class="text-end">Rusak</th><th class="text-end">Diperbaiki</th><th class="text-end">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($barang->stok as $s)
                                <tr>
                                    <td>{{ $s->ruangan->nama_ruangan }}</td>
                                    <td class="text-end">{{ $s->jumlah_tersedia }}</td>
                                    <td class="text-end">{{ $s->jumlah_dicadangkan }}</td>
                                    <td class="text-end">{{ $s->jumlah_dipinjam }}</td>
                                    <td class="text-end">{{ $s->jumlah_rusak }}</td>
                                    <td class="text-end">{{ $s->jumlah_diperbaiki }}</td>
                                    <td class="text-end fw-semibold">{{ $s->jumlah_tersedia + $s->jumlah_dicadangkan + $s->jumlah_dipinjam + $s->jumlah_rusak + $s->jumlah_diperbaiki }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if ($bisaKelola)
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-6">
                <div class="card kartu-stat h-100">
                    <div class="card-header bg-white fw-semibold">Penyesuaian Stok</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('barang.sesuaikan', $barang) }}" novalidate>
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Ruangan</label>
                                <select name="ruangan_sesuai" class="form-select @error('ruangan_sesuai') is-invalid @enderror" required>
                                    <option value="">Pilih ruangan</option>
                                    @foreach ($ruangan as $r)
                                        <option value="{{ $r->id }}" @selected(old('ruangan_sesuai') == $r->id)>{{ $r->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                                @error('ruangan_sesuai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small">Jenis</label>
                                    <select name="arah" class="form-select @error('arah') is-invalid @enderror">
                                        <option value="tambah" @selected(old('arah', 'tambah') === 'tambah')>Tambah stok</option>
                                        <option value="kurangi" @selected(old('arah') === 'kurangi')>Kurangi stok</option>
                                    </select>
                                    @error('arah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Jumlah</label>
                                    <input type="number" name="jumlah_sesuai" min="1" value="{{ old('jumlah_sesuai') }}" class="form-control @error('jumlah_sesuai') is-invalid @enderror" required>
                                    @error('jumlah_sesuai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Alasan (wajib)</label>
                                <input type="text" name="alasan_sesuai" value="{{ old('alasan_sesuai') }}" class="form-control @error('alasan_sesuai') is-invalid @enderror" placeholder="Contoh: hasil stok opname" required>
                                @error('alasan_sesuai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Penyesuaian</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card kartu-stat h-100">
                    <div class="card-header bg-white fw-semibold">Pindah Antar Ruangan</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('barang.pindah', $barang) }}" novalidate>
                            @csrf
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small">Ruangan asal</label>
                                    <select name="ruangan_asal" class="form-select @error('ruangan_asal') is-invalid @enderror" required>
                                        <option value="">Pilih</option>
                                        @foreach ($barang->stok as $s)
                                            <option value="{{ $s->ruangan_id }}" @selected(old('ruangan_asal') == $s->ruangan_id)>{{ $s->ruangan->nama_ruangan }} (tersedia {{ $s->jumlah_tersedia }})</option>
                                        @endforeach
                                    </select>
                                    @error('ruangan_asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Ruangan tujuan</label>
                                    <select name="ruangan_tujuan" class="form-select @error('ruangan_tujuan') is-invalid @enderror" required>
                                        <option value="">Pilih</option>
                                        @foreach ($ruangan as $r)
                                            <option value="{{ $r->id }}" @selected(old('ruangan_tujuan') == $r->id)>{{ $r->nama_ruangan }}</option>
                                        @endforeach
                                    </select>
                                    @error('ruangan_tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Jumlah dipindahkan</label>
                                <input type="number" name="jumlah_pindah" min="1" value="{{ old('jumlah_pindah') }}" class="form-control @error('jumlah_pindah') is-invalid @enderror" required>
                                @error('jumlah_pindah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Pindahkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card kartu-stat">
        <div class="card-header bg-white fw-semibold">Riwayat Perubahan Stok (15 terakhir)</div>
        <div class="card-body">
            @if ($riwayat->isEmpty())
                <div class="text-muted text-center py-3">Belum ada riwayat.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Waktu</th><th>Ruangan</th><th>Jenis</th><th class="text-end">Jumlah</th><th>Alasan</th><th>Oleh</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $r)
                                <tr>
                                    <td class="text-nowrap">{{ $r->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $r->ruangan->nama_ruangan ?? '-' }}</td>
                                    <td><span class="badge text-bg-light border">{{ $r->jenis }}</span></td>
                                    <td class="text-end">{{ $r->jumlah > 0 ? '+' . $r->jumlah : $r->jumlah }}</td>
                                    <td>{{ $r->alasan ?: '-' }}</td>
                                    <td>{{ $r->user->nama ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
