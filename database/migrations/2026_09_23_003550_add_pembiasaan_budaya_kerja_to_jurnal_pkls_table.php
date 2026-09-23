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
        Schema::table('jurnal_pkls', function (Blueprint $table) {
            $table->string('kedisiplinan')->nullable()->after('orang_disapa');
            $table->string('sopan_santun_komunikasi')->nullable()->after('kedisiplinan');
            $table->string('tanggung_jawab_etos_kerja')->nullable()->after('sopan_santun_komunikasi');
            $table->string('kepatuhan_keselamatan_kerja')->nullable()->after('tanggung_jawab_etos_kerja');
            $table->json('budaya_kerja_5r')->nullable()->after('kepatuhan_keselamatan_kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_pkls', function (Blueprint $table) {
            $table->dropColumn([
                'kedisiplinan',
                'sopan_santun_komunikasi',
                'tanggung_jawab_etos_kerja',
                'kepatuhan_keselamatan_kerja',
                'budaya_kerja_5r',
            ]);
        });
    }
};
