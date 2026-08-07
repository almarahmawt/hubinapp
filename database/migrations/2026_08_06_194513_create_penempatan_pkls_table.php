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
        Schema::create('penempatan_pkls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('industri_id')->constrained('industris')->cascadeOnDelete();
            
            // Guru bisa nullable jika belum ditugaskan pembimbing saat awal penempatan
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->nullOnDelete(); 
            
            $table->string('jawaban_industri')->nullable(); // Bisa berisi keterangan atau nama file surat balasan
            $table->string('status')->default('Aktif'); // Aktif, Selesai, Batal
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_pkls');
    }
};
