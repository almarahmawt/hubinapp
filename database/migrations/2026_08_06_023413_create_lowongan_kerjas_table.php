<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lowongan_kerjas', function (Blueprint $table) {
        $table->id();
        
        // INI ADALAH KUNCI RELASINYA (Menyambungkan ke tabel industris)
        $table->foreignId('industri_id')->constrained('industris')->cascadeOnDelete();
        
        $table->string('posisi_pekerjaan');
        $table->text('deskripsi')->nullable();
        $table->date('batas_lamaran')->nullable();
        $table->boolean('status_aktif')->default(true); // Untuk menandai loker masih buka/tutup
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_kerjas');
    }
};
