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
        Schema::create('siswa_beasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_beasiswa');
            $table->integer('penerima_l')->default(0);
            $table->integer('penerima_p')->default(0);
            $table->integer('penerima_jml')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_beasiswa');
    }
};
