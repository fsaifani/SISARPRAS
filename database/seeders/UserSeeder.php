<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

   class UserSeeder extends Seeder
   {
       public function run(): void
       {
           $akun = [
               ['nama' => 'Admin Sarpras',   'email' => 'admin@sekolah.test',     'password' => 'admin1234','role' => 'admin'],
               ['nama' => 'Petugas Sarpras', 'email' => 'petugas@sisarpras.test', 'password' => 'petugas1234','role' => 'petugas'],
               ['nama' => 'Guru Contoh',     'email' => 'guru@sisarpras.test',    'password' => 'password','role' => 'guru'],
               ['nama' => 'Siswa Contoh',    'email' => 'siswa@sisarpras.test',   'password' => 'password','role' => 'siswa'],
           ];

           foreach ($akun as $a) {
               User::updateOrCreate(
                   ['email' => $a['email']],
                   ['nama' => $a['nama'], 'password' => $a['password'], 'role' => $a['role'], 'is_aktif' => true]
               );
           }
       }
   }