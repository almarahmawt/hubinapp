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
        Schema::create('pendaftaran_pkls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('lowongan_pkl_id')->constrained('lowongan_pkls')->cascadeOnDelete();
            
            $table->integer('nilai_pra_pkl')->nullable(); // Nilai prasyarat sebelum PKL
            $table->string('status')->default('Menunggu'); // Menunggu, Disetujui, Ditolak
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pkls');
    }
};
