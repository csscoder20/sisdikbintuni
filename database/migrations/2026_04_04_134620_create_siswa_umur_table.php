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
        Schema::create('siswa_umur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rombel')->nullable()->constrained('tbl_rombel')->cascadeOnDelete();
            $table->string('nama_rombel')->nullable();
            // Kelompok umur 13-23 tahun dengan sub L|P|JML
            $table->integer('umur_13_l')->default(0);
            $table->integer('umur_13_p')->default(0);
            $table->integer('umur_13_jml')->default(0);
            $table->integer('umur_14_l')->default(0);
            $table->integer('umur_14_p')->default(0);
            $table->integer('umur_14_jml')->default(0);
            $table->integer('umur_15_l')->default(0);
            $table->integer('umur_15_p')->default(0);
            $table->integer('umur_15_jml')->default(0);
            $table->integer('umur_16_l')->default(0);
            $table->integer('umur_16_p')->default(0);
            $table->integer('umur_16_jml')->default(0);
            $table->integer('umur_17_l')->default(0);
            $table->integer('umur_17_p')->default(0);
            $table->integer('umur_17_jml')->default(0);
            $table->integer('umur_18_l')->default(0);
            $table->integer('umur_18_p')->default(0);
            $table->integer('umur_18_jml')->default(0);
            $table->integer('umur_19_l')->default(0);
            $table->integer('umur_19_p')->default(0);
            $table->integer('umur_19_jml')->default(0);
            $table->integer('umur_20_l')->default(0);
            $table->integer('umur_20_p')->default(0);
            $table->integer('umur_20_jml')->default(0);
            $table->integer('umur_21_l')->default(0);
            $table->integer('umur_21_p')->default(0);
            $table->integer('umur_21_jml')->default(0);
            $table->integer('umur_22_l')->default(0);
            $table->integer('umur_22_p')->default(0);
            $table->integer('umur_22_jml')->default(0);
            $table->integer('umur_23_l')->default(0);
            $table->integer('umur_23_p')->default(0);
            $table->integer('umur_23_jml')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_umur');
    }
};
