@extends('layouts.app')
@section('judul', 'Buat Permintaan Barang (ATK)')
@section('deskripsi', 'Formulir pengajuan permintaan barang habis pakai seperti spidol, kertas, dll.')

@section('content')
<div class="mb-4">
    <a href="{{ route('permintaan.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke daftar permintaan</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom border-0 shadow-sm">
            <div class="card-body-custom p-4 p-md-5">
                <form action="{{ route('permintaan.store') }}" method="POST">
                    @csrf
                    
                    <div id="barang-container">
                        <div class="row mb-3 align-items-end barang-row bg-light p-3 rounded-3 border mb-3">
                            <div class="col-md-7 mb-2 mb-md-0">
                                <label class="form-label-custom fw-semibold">Pilih Barang <span class="text-danger">*</span></label>
                                <select name="barang_id[]" class="form-select form-select-custom" required>
                                    <option value="" disabled selected>-- Pilih Barang Habis Pakai --</option>
                                    @foreach($barang as $b)
                                    <!-- Variabel sudah disesuaikan jadi $b->nama -->
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

                    <div class="mb-5">
                        <label class="form-label-custom fw-semibold">Alasan Permintaan / Keterangan <span class="text-danger">*</span></label>
                        <textarea name="alasan" rows="3" class="form-control form-control-custom" placeholder="Contoh: Untuk keperluan kelas, spidol habis..." required></textarea>
                    </div>
                    
                    <div class="d-flex gap-3 pt-4 border-top">
                        <button type="submit" class="btn btn-custom-primary px-4 py-2"><i class="bi bi-send me-2"></i> Kirim Permintaan</button>
                        <a href="{{ route('permintaan.index') }}" class="btn btn-custom-secondary px-4 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card-custom border-0 shadow-sm bg-success bg-opacity-10 border-start border-success border-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-success mb-3"><i class="bi bi-info-circle-fill me-2"></i> Informasi Permintaan</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2">Form ini khusus untuk barang habis pakai (sekali pakai) seperti ATK, spidol, dan kertas.</li>
                    <li class="mb-2">Barang yang sudah disetujui dan diberikan tidak perlu dikembalikan ke Sarpras.</li>
                    <li>Pastikan jumlah yang diminta sesuai dengan kebutuhan aktual kelas atau kegiatan.</li>
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