<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_gtk_status_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_gtk');
            $table->integer('pns')->default(0);
            $table->integer('pppk')->default(0);
            $table->integer('honorer_sekolah')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk_status_kepegawaian');
    }
};
