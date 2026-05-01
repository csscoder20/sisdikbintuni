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
        Schema::create('gtk_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtk_id')->constrained('gtk')->cascadeOnDelete();
            $table->foreignId('laporan_id')->nullable()->constrained('laporan')->nullOnDelete();
            $table->date('tgl_presensi');
            $table->enum('presensi', ['H', 'I', 'S', 'A']);
            $table->timestamps();

            $table->unique(['gtk_id', 'tgl_presensi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtk_kehadiran');
    }
};
