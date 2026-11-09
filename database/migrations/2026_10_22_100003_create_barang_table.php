<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->nullable()->unique();
            $table->string('nama', 150);
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->text('deskripsi')->nullable();
            $table->boolean('khusus_guru')->default(false);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
