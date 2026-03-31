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
        Schema::create('tbl_gedung_ruang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung_ruang');
            $table->integer('jumlah')->default(0);
            $table->integer('kondisi_baik')->default(0);
            $table->integer('kondisi_rusak')->default(0);
            $table->enum('status_kepemilikan', ['milik', 'pinjam'])->nullable();
            $table->foreignId('id_sekolah')->constrained('tbl_sekolah')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gedung_ruang');
    }
};
