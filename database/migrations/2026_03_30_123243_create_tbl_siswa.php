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
        Schema::create('tbl_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nisn');
            $table->string('no_bpjs')->nullable();
            $table->string('nama_siswa');
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenkel', ['L', 'P']);
            $table->string('agama')->nullable();
            $table->enum('kategori_papua', ['Papua', 'Non-Papua'])->nullable();
            $table->string('disabilitas')->nullable();
            $table->string('penerima_beasiswa')->nullable();
            $table->foreignId('id_rombel')->constrained('tbl_rombel')->cascadeOnDelete();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_siswa');
    }
};
