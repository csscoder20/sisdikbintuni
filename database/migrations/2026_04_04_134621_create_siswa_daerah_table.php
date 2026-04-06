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
        Schema::create('siswa_daerah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rombel')->nullable()->constrained('tbl_rombel')->cascadeOnDelete();
            $table->string('nama_rombel')->nullable();
            // Daerah: Papua, Non-Papua
            $table->integer('papua_l')->default(0);
            $table->integer('papua_p')->default(0);
            $table->integer('papua_jml')->default(0);
            $table->integer('non_papua_l')->default(0);
            $table->integer('non_papua_p')->default(0);
            $table->integer('non_papua_jml')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_daerah');
    }
};
