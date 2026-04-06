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
        Schema::create('siswa_agama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rombel')->nullable()->constrained('tbl_rombel')->cascadeOnDelete();
            $table->string('nama_rombel')->nullable();
            // Agama: Islam, Kristen Protestan, Katolik, Hindu, Budha, Konghucu
            $table->integer('islam_l')->default(0);
            $table->integer('islam_p')->default(0);
            $table->integer('islam_jml')->default(0);
            $table->integer('kristen_protestan_l')->default(0);
            $table->integer('kristen_protestan_p')->default(0);
            $table->integer('kristen_protestan_jml')->default(0);
            $table->integer('katolik_l')->default(0);
            $table->integer('katolik_p')->default(0);
            $table->integer('katolik_jml')->default(0);
            $table->integer('hindu_l')->default(0);
            $table->integer('hindu_p')->default(0);
            $table->integer('hindu_jml')->default(0);
            $table->integer('budha_l')->default(0);
            $table->integer('budha_p')->default(0);
            $table->integer('budha_jml')->default(0);
            $table->integer('konghucu_l')->default(0);
            $table->integer('konghucu_p')->default(0);
            $table->integer('konghucu_jml')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_agama');
    }
};
