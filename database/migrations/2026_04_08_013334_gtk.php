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
        Schema::create('gtk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('nokarpeg')->nullable();
            $table->string('nuptk')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->enum('daerah_asal', ['Papua', 'Non Papua'])->nullable();
            $table->enum('jenis_gtk', ['Kepala Sekolah', 'Guru', 'Tenaga Administrasi'])->nullable();
            $table->enum('status_kepegawaian', ['PNS', 'CPNS', 'PPPK', 'GTY/PTY', 'Kontrak','Honorer Sekolah'])->nullable();
            $table->date('tmt_pns')->nullable();
            $table->string('pangkat_gol_terakhir')->nullable();
            $table->date('tmt_pangkat_gol_terakhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtk');
    }
};
