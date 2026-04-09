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
        Schema::create('kelulusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('jumlah_peserta_ujian');
            $table->integer('jumlah_lulus');
            $table->decimal('persentase_kelulusan', 5, 2);
            $table->integer('jumlah_lanjut_pt');
            $table->timestamps();

            $table->unique(['sekolah_id','tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelulusan');
    }
};
