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
        Schema::create('tbl_gtk', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nip')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nama_gtk');
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_gtk', ['Kepsek', 'Guru', 'Tenaga Kependidikan']);
            $table->enum('jenkel', ['L', 'P']);
            $table->string('agama')->nullable();
            $table->enum('kategori_papua', ['Papua', 'Non-Papua'])->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('golongan_pegawai')->nullable();
            $table->date('tmt_pegawai')->nullable();
            $table->date('tgl_penempatan_sk_terakhir')->nullable();
            $table->string('npwp')->nullable();
            $table->string('no_rekening')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk');
    }
};
