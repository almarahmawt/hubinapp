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
    Schema::create('siswas', function (Blueprint $table) {
        $table->id();
        // Relasi ke akun login
        $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
        
        // Relasi ke master data yang sudah kita buat sebelumnya
        $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
        $table->foreignId('kompetensi_id')->constrained('kompetensi_keahlians')->cascadeOnDelete();
        
        $table->string('nis');
        $table->string('nama');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
