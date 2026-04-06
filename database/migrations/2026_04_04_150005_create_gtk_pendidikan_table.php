<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_gtk_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_gtk');
            $table->integer('slta')->default(0);
            $table->integer('di')->default(0);
            $table->integer('dii')->default(0);
            $table->integer('diii')->default(0);
            $table->integer('s1')->default(0);
            $table->integer('s2')->default(0);
            $table->integer('s3')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk_pendidikan');
    }
};
