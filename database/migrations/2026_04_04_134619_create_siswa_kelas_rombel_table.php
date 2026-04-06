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
        Schema::create('siswa_kelas_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rombel')->nullable()->constrained('tbl_rombel')->cascadeOnDelete();
            $table->string('nama_rombel')->nullable();
            // Awal Bulan
            $table->integer('awal_bulan_l')->default(0);
            $table->integer('awal_bulan_p')->default(0);
            $table->integer('awal_bulan_jml')->default(0);
            // Mutasi
            $table->integer('mutasi_l')->default(0);
            $table->integer('mutasi_p')->default(0);
            $table->integer('mutasi_jml')->default(0);
            // Akhir Bulan
            $table->integer('akhir_bulan_l')->default(0);
            $table->integer('akhir_bulan_p')->default(0);
            $table->integer('akhir_bulan_jml')->default(0);
            // Pindah Sekolah
            $table->integer('pindah_sekolah_l')->default(0);
            $table->integer('pindah_sekolah_p')->default(0);
            $table->integer('pindah_sekolah_jml')->default(0);
            // Mengulang
            $table->integer('mengulang_l')->default(0);
            $table->integer('mengulang_p')->default(0);
            $table->integer('mengulang_jml')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_kelas_rombel');
    }
};
