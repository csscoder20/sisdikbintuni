<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('laporan_gtk_kategori')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE laporan_gtk_kategori ALTER COLUMN jenis_kategori TYPE VARCHAR(255)");

            return;
        }

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE laporan_gtk_kategori MODIFY jenis_kategori VARCHAR(255) NOT NULL");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('laporan_gtk_kategori')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE laporan_gtk_kategori ALTER COLUMN jenis_kategori TYPE VARCHAR(255)");

            return;
        }

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE laporan_gtk_kategori MODIFY jenis_kategori ENUM('agama', 'daerah', 'status_kepegawaian', 'umur') NOT NULL");
        }
    }
};
