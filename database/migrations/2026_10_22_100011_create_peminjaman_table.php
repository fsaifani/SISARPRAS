<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique();
            $table->foreignId('peminjam_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('alasan');
            $table->date('tanggal_rencana_pakai');
            $table->date('tanggal_rencana_kembali');
            $table->dateTime('tanggal_serah')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->dateTime('tanggal_kembali')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dibatalkan', 'dipinjam', 'dikembalikan', 'terlambat', 'hilang'])->default('menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
