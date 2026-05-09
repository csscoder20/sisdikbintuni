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
        Schema::table('gtk', function (Blueprint $table) {
            $table->string('nama_bank_gaji')->nullable()->after('tmt_pangkat_gol_terakhir');
            $table->string('nomor_rekening_gaji')->nullable()->after('nama_bank_gaji');
            $table->string('nama_rekening_gaji')->nullable()->after('nomor_rekening_gaji');
            
            $table->string('nama_bank_tunjangan')->nullable()->after('nama_rekening_gaji');
            $table->string('nomor_rekening_tunjangan')->nullable()->after('nama_bank_tunjangan');
            $table->string('nama_rekening_tunjangan')->nullable()->after('nomor_rekening_tunjangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtk', function (Blueprint $table) {
            $table->dropColumn([
                'nama_bank_gaji',
                'nomor_rekening_gaji',
                'nama_rekening_gaji',
                'nama_bank_tunjangan',
                'nomor_rekening_tunjangan',
                'nama_rekening_tunjangan',
            ]);
        });
    }
};
