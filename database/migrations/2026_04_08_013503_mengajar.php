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
        Schema::create('mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtk_id')->constrained('gtk')->cascadeOnDelete();
            $table->foreignId('rombel_id')->constrained('rombel')->cascadeOnDelete();
            $table->string('mata_pelajaran');
            $table->integer('jumlah_jam');
            $table->enum('semester', ['ganjil','genap'])->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->foreignId('laporan_id')->nullable()->constrained('laporan')->nullOnDelete();
            $table->timestamps();

            $table->unique([
                'gtk_id',
                'rombel_id',
                'mata_pelajaran'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mengajar');
    }
};
