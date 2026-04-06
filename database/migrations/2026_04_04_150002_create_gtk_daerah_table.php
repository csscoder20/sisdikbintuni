<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_gtk_daerah', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_gtk');
            // Papua
            $table->integer('papua_l')->default(0);
            $table->integer('papua_p')->default(0);
            $table->integer('papua_jml')->default(0);
            // Non Papua
            $table->integer('non_papua_l')->default(0);
            $table->integer('non_papua_p')->default(0);
            $table->integer('non_papua_jml')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk_daerah');
    }
};
