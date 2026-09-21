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
        Schema::create('jurnal_pkls', function (Blueprint $table) {
        $table->id();
        // Terhubung ke data penempatan siswa
        $table->foreignId('penempatan_pkl_id')->constrained('penempatan_pkls')->cascadeOnDelete();
        $table->date('tanggal');

        // 1. Absensi & GPS
        $table->enum('status_kehadiran', ['Hadir', 'Sakit', 'Izin'])->default('Hadir');
        $table->string('bukti_kehadiran')->nullable(); // Foto surat sakit / surat izin
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();

        // 2. Pertanyaan Panduan / Refleksi Harian
        $table->string('orang_disapa')->nullable(); // Siapa orang yang disapa hari ini
        $table->boolean('persiapan_alat')->default(false); // Apakah persiapan alat dilakukan?
        $table->boolean('membereskan_alat')->default(false); // Apakah membereskan alat?

        // 3. Jurnal Pekerjaan & Bukti Visual
        $table->text('deskripsi_kegiatan')->nullable();
        $table->string('foto_kegiatan')->nullable(); // Foto/dokumen praktik

        // 4. Validasi Pembimbing
        $table->enum('status_validasi', ['Menunggu', 'Disetujui', 'Revisi'])->default('Menunggu');
        $table->text('catatan_pembimbing')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_pkls');
    }
};
