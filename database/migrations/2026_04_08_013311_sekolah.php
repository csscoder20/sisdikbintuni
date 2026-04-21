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
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('npsn')->unique();
            $table->string('nss')->nullable();
            $table->string('npwp')->nullable();
            $table->enum('jenjang', ['sma', 'smk'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->string('nomor_sk_pendirian')->nullable();
            $table->date('tanggal_sk_pendirian')->nullable();
            $table->string('nama_yayasan')->nullable();
            $table->text('alamat_yayasan')->nullable();
            $table->string('nomor_sk_yayasan')->nullable();
            $table->date('tanggal_sk_yayasan')->nullable();
            $table->string('akreditasi')->nullable();
            $table->enum('status_tanah', ['shm','hgb','ulayat'])->nullable();
            $table->integer('luas_tanah')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
