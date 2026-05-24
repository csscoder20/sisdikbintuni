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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->enum('status', ['draft', 'submitted', 'verified', 'valid', 'invalid'])->default('draft');
            $table->timestamp('tanggal_submit')->nullable();
            $table->timestamps();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->boolean('is_identitas_sekolah_valid')->default(false);
            $table->boolean('is_nominatif_gtk_valid')->default(false);
            $table->boolean('is_nominatif_siswa_valid')->default(false);
            $table->boolean('is_kondisi_sarpras_valid')->default(false);
            $table->boolean('is_kondisi_gtk_valid')->default(false);
            $table->boolean('is_kondisi_siswa_valid')->default(false);
            $table->boolean('is_sebaran_jam_valid')->default(false);
            $table->boolean('is_rekap_kehadiran_valid')->default(false);
            $table->boolean('is_kelulusan_valid')->default(false);
            $table->boolean('is_siswa_rombel_valid')->default(false);
            $table->boolean('is_siswa_umur_valid')->default(false);
            $table->boolean('is_siswa_agama_valid')->default(false);
            $table->boolean('is_siswa_daerah_valid')->default(false);
            $table->boolean('is_siswa_disabilitas_valid')->default(false);
            $table->boolean('is_siswa_beasiswa_valid')->default(false);
            $table->boolean('is_gtk_agama_valid')->default(false);
            $table->boolean('is_gtk_daerah_valid')->default(false);
            $table->boolean('is_gtk_status_valid')->default(false);
            $table->boolean('is_gtk_umur_valid')->default(false);
            $table->boolean('is_gtk_pendidikan_valid')->default(false);
            $table->softDeletes();

            $table->unique(['sekolah_id', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
