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
    Schema::create('kelas', function (Blueprint $table) {
        $table->id();
        // Foreign Key ke tabel kompetensi_keahlians
        $table->foreignId('kompetensi_id')->constrained('kompetensi_keahlians')->cascadeOnDelete();
        $table->string('nama');
        $table->integer('tingkat');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
