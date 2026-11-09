<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('pengguna.index', compact('users'));
    }

    public function show(User $pengguna)
    {
        return view('pengguna.show', compact('pengguna'));
    }

    public function edit(User $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$pengguna->id,
            'role' => 'required|in:admin,petugas,guru,siswa'
        ]);

        $pengguna->update($request->only(['name', 'email', 'role']));
        return redirect()->route('pengguna.index')->with('success', 'data pengguna berhasil diubah.');
    }

    public function status(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif'
        ]);
        
        $user->update(['status' => $request->status]);
        return back()->with('success', 'status akun berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        $pengguna->delete();
        return back()->with('success', 'pengguna berhasil dihapus.');
    }
}