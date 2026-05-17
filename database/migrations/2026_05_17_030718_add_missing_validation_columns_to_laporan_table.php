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
        Schema::table('laporan', function (Blueprint $table) {
            $table->boolean('is_mapel_valid')->default(false);
            $table->boolean('is_keuangan_valid')->default(false);
            $table->boolean('is_rekening_npwp_valid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['is_mapel_valid', 'is_keuangan_valid', 'is_rekening_npwp_valid']);
        });
    }
};
