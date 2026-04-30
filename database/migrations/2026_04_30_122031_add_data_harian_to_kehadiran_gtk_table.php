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
        Schema::table('kehadiran_gtk', function (Blueprint $table) {
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->jsonb('data_harian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_gtk', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun', 'data_harian']);
        });
    }
};
