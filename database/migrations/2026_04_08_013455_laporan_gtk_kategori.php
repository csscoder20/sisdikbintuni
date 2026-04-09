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
        Schema::create('laporan_gtk_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_gtk_id')->constrained('laporan_gtk')->cascadeOnDelete();
            $table->enum('jenis_kategori', [
                'agama','daerah','status_kepegawaian','umur'
            ]);
            $table->string('sub_kategori');
            $table->integer('jumlah');
            $table->timestamps();

            $table->unique([
                'laporan_gtk_id',
                'jenis_kategori',
                'sub_kategori'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_gtk_kategori');
    }
};
