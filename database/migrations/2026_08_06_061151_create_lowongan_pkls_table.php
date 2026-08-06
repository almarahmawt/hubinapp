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
    Schema::create('lowongan_pkls', function (Blueprint $table) {
        $table->id();
        // Foreign Key ke tabel industri dan periode
        $table->foreignId('industri_id')->constrained('industris')->cascadeOnDelete();
        $table->foreignId('periode_id')->constrained('periode_pkls')->cascadeOnDelete();
        $table->integer('kuota');
        $table->text('syarat_khusus')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_pkls');
    }
};
