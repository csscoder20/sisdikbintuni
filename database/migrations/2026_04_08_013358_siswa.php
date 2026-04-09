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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nisn')->nullable();
            $table->string('nokk')->nullable();
            $table->string('nik')->nullable();
            $table->string('nobpjs')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('agama')->nullable();
            $table->enum('daerah_asal', ['Papua', 'Non Papua'])->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->enum('disabilitas', ['Tidak', 'Tuna Netra', 'Tuna Rungu', 'Tuna Wicara', 'Tuna Daksa', 'Tuna Grahita', 'Tuna Lainnya'])->default('Tidak');
            $table->enum('beasiswa', ['Tidak', 'Beasiswa Pemerintah Pusat', 'Beasiswa Pemerintah Daerah', 'Beasisswa Swasta', 'Beasiswa Khusus', 'Beasiswa Afirmasi', 'Beasiswa Lainnya'])->default('Tidak');
            $table->enum('status', ['aktif', 'mutasi_masuk', 'mutasi_keluar', 'lulus', 'putus_sekolah', 'mengulang'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
