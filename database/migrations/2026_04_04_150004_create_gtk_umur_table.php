<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_gtk_umur', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_gtk');

            // Umur 13-23 dengan sub kolom L, P, JML
            for ($age = 13; $age <= 23; $age++) {
                $table->integer('umur_' . $age . '_l')->default(0);
                $table->integer('umur_' . $age . '_p')->default(0);
                $table->integer('umur_' . $age . '_jml')->default(0);
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_gtk_umur');
    }
};
