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
    Schema::create('gurus', function (Blueprint $table) {
        $table->id();
        // Relasi ke akun login (bisa dikosongkan dulu jika belum dibuatkan akun)
        $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
        $table->string('nip')->nullable();
        $table->string('nama');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
