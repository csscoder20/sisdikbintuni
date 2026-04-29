<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('laporan_gtk_kategori') || DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE laporan_gtk_kategori DROP CONSTRAINT IF EXISTS laporan_gtk_kategori_jenis_kategori_check');
    }

    public function down(): void
    {
        //
    }
};
