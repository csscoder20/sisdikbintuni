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
        Schema::create('tbl_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            $table->string('npwp')->nullable();
            $table->text('alamat')->nullable();
            $table->unsignedBigInteger('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->string('nomor_sk_pendirian')->nullable();
            $table->date('tgl_sk_pendirian')->nullable();
            $table->enum('status_sekolah', ['Negeri', 'Swasta'])->nullable();
            $table->string('nama_penyelenggara_yayasan')->nullable();
            $table->text('alamat_penyelenggara_yayasan')->nullable();
            $table->string('sk_pendirian_yayasan')->nullable();
            $table->string('gedung_sekolah')->nullable();
            $table->string('akreditasi_sekolah')->nullable();
            $table->string('status_tanah_sekolah')->nullable();
            $table->decimal('luas_tanah_sekolah', 12, 2)->nullable();
            $table->string('email_sekolah')->nullable();
            $table->timestamps();

            // foreign key (optional jika tbl_wilayah ada)
            // $table->foreign('desa')->references('id')->on('tbl_wilayah')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_sekolah');
    }
};
