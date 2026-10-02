<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            // Tambahkan kolom baru di sini
            $table->string('gelar_depan')->nullable()->after('nip');
            $table->string('gelar_belakang')->nullable()->after('nama');
            $table
                ->enum('jenis_kelamin', ['L', 'P'])
                ->nullable()
                ->after('gelar_belakang');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->boolean('is_aktif')->default(true)->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            // Hapus kolom jika suatu saat dilakukan migrate:rollback
            $table->dropColumn([
                'gelar_depan',
                'gelar_belakang',
                'jenis_kelamin',
                'alamat',
                'is_aktif',
            ]);
        });
    }
};
