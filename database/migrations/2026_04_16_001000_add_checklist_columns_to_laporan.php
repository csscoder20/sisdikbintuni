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
        Schema::table('laporan', function (Blueprint $table) {
            $table->boolean('is_identitas_sekolah_valid')->default(false);
            $table->boolean('is_nominatif_gtk_valid')->default(false);
            $table->boolean('is_nominatif_siswa_valid')->default(false);
            $table->boolean('is_kondisi_sarpras_valid')->default(false);
            $table->boolean('is_kondisi_gtk_valid')->default(false);
            $table->boolean('is_kondisi_siswa_valid')->default(false);
            $table->boolean('is_sebaran_jam_valid')->default(false);
            $table->boolean('is_rekap_kehadiran_valid')->default(false);
            $table->boolean('is_kelulusan_valid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn([
                'is_identitas_sekolah_valid',
                'is_nominatif_gtk_valid',
                'is_nominatif_siswa_valid',
                'is_kondisi_sarpras_valid',
                'is_kondisi_gtk_valid',
                'is_kondisi_siswa_valid',
                'is_sebaran_jam_valid',
                'is_rekap_kehadiran_valid',
                'is_kelulusan_valid',
            ]);
        });
    }
};
