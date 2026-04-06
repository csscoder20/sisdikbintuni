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
        Schema::create('siswa_disabilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rombel')->nullable()->constrained('tbl_rombel')->cascadeOnDelete();
            $table->string('nama_rombel')->nullable();
            // Disabilitas: Tuna Rungu, Netra, Wicara, Daksa, Grahita, Lainnya
            $table->integer('tuna_rungu_l')->default(0);
            $table->integer('tuna_rungu_p')->default(0);
            $table->integer('tuna_rungu_jml')->default(0);
            $table->integer('netra_l')->default(0);
            $table->integer('netra_p')->default(0);
            $table->integer('netra_jml')->default(0);
            $table->integer('wicara_l')->default(0);
            $table->integer('wicara_p')->default(0);
            $table->integer('wicara_jml')->default(0);
            $table->integer('daksa_l')->default(0);
            $table->integer('daksa_p')->default(0);
            $table->integer('daksa_jml')->default(0);
            $table->integer('grahita_l')->default(0);
            $table->integer('grahita_p')->default(0);
            $table->integer('grahita_jml')->default(0);
            $table->integer('lainnya_l')->default(0);
            $table->integer('lainnya_p')->default(0);
            $table->integer('lainnya_jml')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_disabilitas');
    }
};
