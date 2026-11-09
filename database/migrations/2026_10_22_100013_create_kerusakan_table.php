<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->restrictOnDelete();
            $table->foreignId('ruangan_id')->constrained('ruangan')->restrictOnDelete();
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman')->nullOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->text('deskripsi');
            $table->string('foto', 255)->nullable();
            $table->date('tanggal_lapor');
            $table->enum('status', ['dilaporkan', 'diperbaiki', 'selesai'])->default('dilaporkan');
            $table->enum('hasil_akhir', ['layak', 'tidak_layak'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kerusakan');
    }
};
