@extends('layouts.app')
@section('judul', 'Ajukan Peminjaman Barang')
@section('deskripsi', 'Isi formulir di bawah ini untuk meminjam barang inventaris sekolah.')

@section('content')
<div class="mb-4">
    <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke daftar peminjaman</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom border-0 shadow-sm">
            <div class="card-body-custom p-4 p-md-5">
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    
                    <div id="barang-container">
                        <div class="row mb-3 align-items-end barang-row bg-light p-3 rounded-3 border mb-3">
                            <div class="col-md-7 mb-2 mb-md-0">
                                <label class="form-label-custom fw-semibold">Pilih Barang <span class="text-danger">*</span></label>
                                <select name="barang_id[]" class="form-select form-select-custom" required>
                                    <option value="" disabled selected>-- Pilih Barang --</option>
                                    @foreach($barang as $b)
                                    <!-- INI YANG GUE UBAH JADI $b->nama -->
                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="form-label-custom fw-semibold">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah[]" min="1" class="form-control form-control-custom" required placeholder="Jml">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 btn-hapus d-none"><i class="bi bi-trash"></i> Hapus</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btn-tambah" class="btn btn-sm btn-outline-primary mb-4"><i class="bi bi-plus-circle"></i> Tambah Barang Lain</button>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label-custom fw-semibold">Batas Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali" class="form-control form-control-custom" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <small class="text-muted">Peminjaman maksimal 3 hari.</small>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label-custom fw-semibold">Alasan Peminjaman <span class="text-danger">*</span></label>
                        <textarea name="alasan" rows="3" class="form-control form-control-custom" placeholder="Jelaskan secara singkat untuk keperluan apa barang ini dipinjam..." required></textarea>
                    </div>
                    
                    <div class="d-flex gap-3 pt-4 border-top">
                        <button type="submit" class="btn btn-custom-primary px-4 py-2"><i class="bi bi-send me-2"></i> Kirim Pengajuan</button>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-custom-secondary px-4 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card-custom border-0 shadow-sm bg-primary bg-opacity-10 border-start border-primary border-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle-fill me-2"></i> Informasi Peminjaman</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2">Pastikan barang yang Anda pilih tersedia dan layak pakai.</li>
                    <li class="mb-2">Pengajuan akan ditinjau oleh Petugas Sarpras sebelum barang dapat diambil.</li>
                    <li class="mb-2">Harap kembalikan barang tepat waktu sesuai dengan tanggal batas kembali yang Anda ajukan.</li>
                    <li>Keterlambatan pengembalian akan dicatat dalam riwayat akun Anda.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btn-tambah').addEventListener('click', function() {
        let container = document.getElementById('barang-container');
        let row = container.querySelector('.barang-row').cloneNode(true);
        
        row.querySelector('select').value = '';
        row.querySelector('input').value = '';
        
        let btnHapus = row.querySelector('.btn-hapus');
        btnHapus.classList.remove('d-none');
        btnHapus.addEventListener('click', function() {
            row.remove();
        });
        
        container.appendChild(row);
    });
</script>
@endsection