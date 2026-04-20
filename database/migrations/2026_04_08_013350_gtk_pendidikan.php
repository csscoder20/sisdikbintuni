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
        Schema::create('gtk_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtk_id')->constrained('gtk')->cascadeOnDelete();
            
            $table->string('thn_tamat_sd')->nullable();
            $table->string('thn_tamat_smp')->nullable();
            $table->string('thn_tamat_sma')->nullable();

            $table->string('thn_tamat_d1')->nullable();
            $table->string('jurusan_d1')->nullable();
            $table->string('perguruan_tinggi_d1')->nullable();
            
            $table->string('thn_tamat_d2')->nullable();
            $table->string('jurusan_d2')->nullable();
            $table->string('perguruan_tinggi_d2')->nullable();

            $table->string('thn_tamat_d3')->nullable();
            $table->string('jurusan_d3')->nullable();
            $table->string('perguruan_tinggi_d3')->nullable();

            $table->string('thn_tamat_s1')->nullable();
            $table->string('jurusan_s1')->nullable();
            $table->string('perguruan_tinggi_s1')->nullable();

            $table->string('thn_tamat_s2')->nullable();
            $table->string('jurusan_s2')->nullable();
            $table->string('perguruan_tinggi_s2')->nullable();

            $table->string('thn_tamat_s3')->nullable();
            $table->string('jurusan_s3')->nullable();
            $table->string('perguruan_tinggi_s3')->nullable();

            $table->string('thn_akta4')->nullable();
            $table->string('jurusan_akta4')->nullable();
            $table->string('perguruan_tinggi_akta4')->nullable();   

            $table->string('gelar_depan')->nullable();   
            $table->string('gelar_belakang')->nullable();   

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtk_pendidikan');
    }
};
