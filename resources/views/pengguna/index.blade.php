@extends('layouts.app')
@section('judul', 'Manajemen Pengguna')
@section('deskripsi', 'Kelola hak akses dan status akun sistem')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-dark fw-bold">Daftar Akun</h4>
</div>

<div class="card-custom mb-4">
    <div class="card-body-custom p-0 table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-4">Nama Pengguna</th>
                    <th>Email</th>
                    <th>Peran (Role)</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td class="px-4"><strong class="text-dark">{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge bg-secondary">{{ strtoupper($u->role) }}</span></td>
                    <td>
                        @if($u->status == 'aktif')
                            <span class="badge badge-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
                        @else
                            <span class="badge badge-custom bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('pengguna.edit', $u->id) }}" class="btn btn-sm btn-custom-secondary me-1"><i class="bi bi-pencil"></i> Edit</a>
                        
                        @php
                            $pesanConfirm = $u->status == 'aktif' ? 'Apakah Anda yakin ingin menonaktifkan akun ini?' : 'Apakah Anda yakin ingin mengaktifkan akun ini?';
                            $btnClass = $u->status == 'aktif' ? 'btn-outline-danger' : 'btn-outline-success';
                            $btnIcon = $u->status == 'aktif' ? 'bi-person-dash' : 'bi-person-check';
                            $btnText = $u->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan';
                        @endphp

                        <form action="{{ route('pengguna.status', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $pesanConfirm }}')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $u->status == 'aktif' ? 'nonaktif' : 'aktif' }}">
                            <button type="submit" class="btn btn-sm {{ $btnClass }}">
                                <i class="bi {{ $btnIcon }}"></i> {{ $btnText }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection