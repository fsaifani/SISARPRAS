<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengadaan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique();
            $table->foreignId('permintaan_id')->nullable()->constrained('permintaan')->nullOnDelete();
            $table->foreignId('pengaju_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('keputusan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['diusulkan', 'diteruskan', 'disetujui', 'ditolak', 'revisi', 'selesai'])->default('diusulkan');
            $table->text('catatan_admin')->nullable();
            $table->dateTime('tanggal_keputusan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaan');
    }
};
