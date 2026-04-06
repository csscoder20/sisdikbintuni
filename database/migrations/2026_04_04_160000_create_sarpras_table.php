<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_sarpras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung_ruang');
            $table->integer('jumlah')->default(0);
            $table->integer('baik')->default(0);
            $table->integer('rusak')->default(0);
            $table->string('status_kepemilikan')->default('Milik Sekolah');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_sarpras');
    }
};
