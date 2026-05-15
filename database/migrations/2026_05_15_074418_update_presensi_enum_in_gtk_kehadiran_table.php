<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to update the check constraint
        if (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE gtk_kehadiran DROP CONSTRAINT IF EXISTS gtk_kehadiran_presensi_check');
            DB::statement("ALTER TABLE gtk_kehadiran ADD CONSTRAINT gtk_kehadiran_presensi_check CHECK (presensi::text IN ('H', 'I', 'S', 'A', 'L'))");
        } else {
            Schema::table('gtk_kehadiran', function (Blueprint $table) {
                $table->enum('presensi', ['H', 'I', 'S', 'A', 'L'])->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE gtk_kehadiran DROP CONSTRAINT IF EXISTS gtk_kehadiran_presensi_check');
            DB::statement("ALTER TABLE gtk_kehadiran ADD CONSTRAINT gtk_kehadiran_presensi_check CHECK (presensi::text IN ('H', 'I', 'S', 'A'))");
        } else {
            Schema::table('gtk_kehadiran', function (Blueprint $table) {
                $table->enum('presensi', ['H', 'I', 'S', 'A'])->change();
            });
        }
    }
};
