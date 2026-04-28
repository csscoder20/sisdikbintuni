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
        Schema::create('laporan_siswa_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_siswa_id')->constrained('laporan_siswa')->cascadeOnDelete();
            $table->enum('jenis_kategori', ['umur','agama','asal_daerah','disabilitas','beasiswa']);
            $table->string('sub_kategori');
            $table->integer('laki_laki');
            $table->integer('perempuan');
            $table->integer('total');
            $table->timestamps();
            
            $table->unique([
                'laporan_siswa_id',
                'jenis_kategori',
                'sub_kategori'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_siswa_kategori');
    }
};
