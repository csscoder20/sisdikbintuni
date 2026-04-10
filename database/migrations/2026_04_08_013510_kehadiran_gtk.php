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
        Schema::create('kehadiran_gtk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtk_id')->constrained('gtk')->cascadeOnDelete();
            $table->foreignId('laporan_id')->constrained('laporan')->cascadeOnDelete();
            $table->integer('hadir')->default(0);
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alfa')->default(0);
            $table->integer('hari_kerja')->nullable();
            $table->timestamps();

            $table->unique(['gtk_id', 'laporan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_gtk');
    }
};
