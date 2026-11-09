<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique();
            $table->foreignId('pemohon_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('alasan');
            $table->date('tanggal_dibutuhkan');
            $table->enum('status', ['menunggu', 'direvisi', 'disetujui', 'menunggu_pengadaan', 'ditolak', 'dibatalkan', 'selesai'])->default('menunggu');
            $table->text('catatan_petugas')->nullable();
            $table->dateTime('tanggal_serah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan');
    }
};
