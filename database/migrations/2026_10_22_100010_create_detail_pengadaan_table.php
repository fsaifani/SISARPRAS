<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengadaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengadaan_id')->constrained('pengadaan')->cascadeOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete();
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->nullOnDelete();
            $table->string('nama_barang', 150);
            $table->unsignedInteger('jumlah_diajukan');
            $table->text('alasan')->nullable();
            $table->unsignedInteger('jumlah_diterima_baik')->default(0);
            $table->unsignedInteger('jumlah_diterima_rusak')->default(0);
            $table->date('tanggal_terima')->nullable();
            $table->text('catatan_terima')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengadaan');
    }
};
