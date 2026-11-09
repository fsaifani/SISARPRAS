@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <a href="{{ route('pengguna.index') }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">&larr; kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">edit pengguna</h1>
    
    <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
        <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">nama lengkap</label>
                <input type="text" name="name" value="{{ $pengguna->name }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">email</label>
                <input type="email" name="email" value="{{ $pengguna->email }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm text-gray-700 mb-1">peran (role)</label>
                <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
                    <option value="admin" {{ $pengguna->role == 'admin' ? 'selected' : '' }}>admin</option>
                    <option value="petugas" {{ $pengguna->role == 'petugas' ? 'selected' : '' }}>petugas</option>
                    <option value="guru" {{ $pengguna->role == 'guru' ? 'selected' : '' }}>guru</option>
                    <option value="siswa" {{ $pengguna->role == 'siswa' ? 'selected' : '' }}>siswa</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-800 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-blue-700">simpan perubahan</button>
        </form>
    </div>
</div>
@endsection