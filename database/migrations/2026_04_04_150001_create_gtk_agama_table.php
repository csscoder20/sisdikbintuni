<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_gtk_agama', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_gtk');
            // Islam
            $table->integer('islam_l')->default(0);
            $table->integer('islam_p')->default(0);
            $table->integer('islam_jml')->default(0);
            // Kristen Protestan
            $table->integer('kristen_protestan_l')->default(0);
            $table->integer('kristen_protestan_p')->default(0);
            $table->integer('kristen_protestan_jml')->default(0);
            // Katolik
            $table->integer('katolik_l')->default(0);
            $table->integer('katolik_p')->default(0);
            $table->integer('katolik_jml')->default(0);
            // Hindu
            $table->integer('hindu_l')->default(0);
            $table->integer('hindu_p')->default(0);
            $table->integer('hindu_jml')->default(0);
            // Budha
            $table->integer('budha_l')->default(0);
            $table->integer('budha_p')->default(0);
            $table->integer('budha_jml')->default(0);
            // Konghucu
            $table->integer('konghucu_l')->default(0);
            $table->integer('konghucu_p')->default(0);
            $table->integer('konghucu_jml')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk_agama');
    }
};
