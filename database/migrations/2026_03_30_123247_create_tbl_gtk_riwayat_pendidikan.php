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
        Schema::create('tbl_gtk_riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_gtk')->constrained('tbl_gtk')->cascadeOnDelete();
            $table->year('thn_tamat_sd')->nullable();
            $table->year('thn_tamat_smp')->nullable();
            $table->year('thn_tamat_sma')->nullable();
            $table->year('thn_tamat_d1')->nullable();
            $table->string('jurusan_d1')->nullable();
            $table->year('thn_tamat_d2')->nullable();
            $table->string('jurusan_d2')->nullable();
            $table->year('thn_tamat_d3')->nullable();
            $table->string('jurusan_d3')->nullable();
            $table->year('thn_tamat_s1')->nullable();
            $table->string('jurusan_s1')->nullable();
            $table->year('thn_tamat_s2')->nullable();
            $table->string('jurusan_s2')->nullable();
            $table->year('thn_tamat_s3')->nullable();
            $table->string('jurusan_s3')->nullable();
            $table->year('thn_akta_1')->nullable();
            $table->string('jurusan_akta_1')->nullable();
            $table->year('thn_akta_2')->nullable();
            $table->string('jurusan_akta_2')->nullable();
            $table->year('thn_akta_3')->nullable();
            $table->string('jurusan_akta_3')->nullable();
            $table->year('thn_akta_4')->nullable();
            $table->string('jurusan_akta_4')->nullable();
            $table->string('nama_perguruan_tinggi')->nullable();
            $table->string('gelar_akademik')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk_riwayat_pendidikan');
    }
};
