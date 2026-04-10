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
        Schema::create('laporan_siswa_rekap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_siswa_id')->constrained('laporan_siswa')->cascadeOnDelete();
            $table->enum('kategori', ['awal_bulan', 'mutasi_masuk', 'mutasi_keluar', 'putus_sekolah', 'mengulang', 'akhir_bulan',]);
            $table->integer('laki_laki');
            $table->integer('perempuan');
            $table->integer('total');
            $table->timestamps();

            $table->unique(['laporan_siswa_id', 'kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_siswa_rekap');
    }
};
