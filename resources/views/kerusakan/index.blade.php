@extends('layouts.app')
@section('judul', 'Pemeliharaan & Kerusakan')
@section('deskripsi', auth()->user()->role == 'admin' || auth()->user()->role == 'petugas' ? 'Pantau dan kelola laporan kerusakan aset dari pengguna.' : 'Laporkan fasilitas sekolah yang mengalami kerusakan.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-dark fw-bold">Daftar Laporan Kerusakan</h4>
    @if(auth()->user()->role == 'guru' || auth()->user()->role == 'siswa')
    <a href="{{ route('kerusakan.create') }}" class="btn btn-custom-primary shadow-sm"><i class="bi bi-plus-lg"></i> Buat Laporan Baru</a>
    @endif
</div>

<div class="card-custom mb-4">
    <div class="card-body-custom p-0 table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-4">Tanggal</th>
                    <th>Barang & Lokasi</th>
                    <th>Deskripsi & Bukti</th>
                    <th>Status</th>
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                    <th class="text-center">Aksi Petugas</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($kerusakan as $k)
                <tr>
                    <td class="px-4">
                        <strong class="text-dark">{{ \Carbon\Carbon::parse($k->tanggal_lapor)->format('d M Y') }}</strong><br>
                        <small class="text-muted"><i class="bi bi-person"></i> Pengguna Sistem</small>
                    </td>
                    <td>
                        <!-- Pake fallback biar nama kolom apapun yang kebaca di DB lu (data_barang atau nama), tetep jalan -->
                        <strong class="text-primary">{{ $k->barang->data_barang ?? $k->barang->nama ?? 'Barang Dihapus' }} ({{ $k->jumlah }} unit)</strong><br>
                        
                        <!-- INI SOLUSI ERRORNYA: Langsung tembak ke model Ruangan pake ID -->
                        <small class="text-muted">
                            <i class="bi bi-geo-alt"></i> 
                            {{ \App\Models\Ruangan::find($k->ruangan_id)->nama_ruangan ?? '-' }}
                        </small>
                    </td>
                    <td>
                        <span class="text-muted small" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $k->deskripsi }}
                        </span>
                        <!-- Tombol untuk melihat foto bukti -->
                        @if($k->foto)
                        <a href="{{ asset('storage/' . $k->foto) }}" target="_blank" class="badge bg-secondary text-decoration-none mt-1"><i class="bi bi-image"></i> Lihat Foto</a>
                        @endif
                    </td>
                    <td>
                        @if($k->status == 'dilaporkan')
                            <span class="badge badge-custom bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Baru Dilaporkan</span>
                        @elseif($k->status == 'diperbaiki')
                            <span class="badge badge-custom bg-info bg-opacity-10 text-info border border-info border-opacity-25">Sedang Diperbaiki</span>
                        @elseif($k->status == 'selesai')
                            <span class="badge badge-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">Selesai Diperbaiki</span>
                        @endif
                    </td>
                    
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $k->id }}">
                            <i class="bi bi-pencil-square"></i> Proses
                        </button>

                        <div class="modal fade text-start" id="updateModal{{ $k->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0">
                                        <h5 class="modal-title fw-bold">Update Status Perbaikan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('kerusakan.updateProses', $k->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body py-0">
                                            <div class="mb-3">
                                                <label class="form-label-custom">Ubah Status</label>
                                                <select name="status" class="form-select form-select-custom" required>
                                                    <option value="dilaporkan" {{ $k->status == 'dilaporkan' ? 'selected' : '' }}>Baru Dilaporkan</option>
                                                    <option value="diperbaiki" {{ $k->status == 'diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki Teknisi</option>
                                                    <option value="selesai" {{ $k->status == 'selesai' ? 'selected' : '' }}>Selesai Diperbaiki (Barang Sehat)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0">
                                            <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-custom-primary">Simpan Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ (auth()->user()->role == 'admin' || auth()->user()->role == 'petugas') ? 5 : 4 }}" class="text-center py-5 text-muted">
                        <i class="bi bi-tools fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        Belum ada laporan kerusakan barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection