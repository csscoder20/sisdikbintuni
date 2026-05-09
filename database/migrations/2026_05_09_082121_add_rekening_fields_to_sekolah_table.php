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
        Schema::table('sekolah', function (Blueprint $table) {
            $table->string('nama_rekening_bop')->nullable();
            $table->string('nomor_rekening_bop')->nullable();
            $table->string('nama_bank_bop')->nullable();
            
            $table->string('nama_rekening_bosp')->nullable();
            $table->string('nomor_rekening_bosp')->nullable();
            $table->string('nama_bank_bosp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'nama_rekening_bop',
                'nomor_rekening_bop',
                'nama_bank_bop',
                'nama_rekening_bosp',
                'nomor_rekening_bosp',
                'nama_bank_bosp',
            ]);
        });
    }
};
