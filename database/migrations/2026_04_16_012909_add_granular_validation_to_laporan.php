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
            // Siswa Granular
            $table->boolean('is_siswa_rombel_valid')->default(false);
            $table->boolean('is_siswa_umur_valid')->default(false);
            $table->boolean('is_siswa_agama_valid')->default(false);
            $table->boolean('is_siswa_daerah_valid')->default(false);
            $table->boolean('is_siswa_disabilitas_valid')->default(false);
            $table->boolean('is_siswa_beasiswa_valid')->default(false);

            // GTK Granular
            $table->boolean('is_gtk_agama_valid')->default(false);
            $table->boolean('is_gtk_daerah_valid')->default(false);
            $table->boolean('is_gtk_status_valid')->default(false);
            $table->boolean('is_gtk_umur_valid')->default(false);
            $table->boolean('is_gtk_pendidikan_valid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn([
                'is_siswa_rombel_valid',
                'is_siswa_umur_valid',
                'is_siswa_agama_valid',
                'is_siswa_daerah_valid',
                'is_siswa_disabilitas_valid',
                'is_siswa_beasiswa_valid',
                'is_gtk_agama_valid',
                'is_gtk_daerah_valid',
                'is_gtk_status_valid',
                'is_gtk_umur_valid',
                'is_gtk_pendidikan_valid',
            ]);
        });
    }
};
