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
    Schema::create('periode_pkls', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('tahun_ajaran');
        $table->date('tanggal_mulai');
        $table->date('tanggal_selesai');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_pkls');
    }
};
