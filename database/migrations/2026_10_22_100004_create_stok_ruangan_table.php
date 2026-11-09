<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_ruangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->restrictOnDelete();
            $table->foreignId('ruangan_id')->constrained('ruangan')->restrictOnDelete();
            $table->unsignedInteger('jumlah_tersedia')->default(0);
            $table->unsignedInteger('jumlah_dicadangkan')->default(0);
            $table->unsignedInteger('jumlah_dipinjam')->default(0);
            $table->unsignedInteger('jumlah_rusak')->default(0);
            $table->unsignedInteger('jumlah_diperbaiki')->default(0);
            $table->timestamps();

            $table->unique(['barang_id', 'ruangan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_ruangan');
    }
};
