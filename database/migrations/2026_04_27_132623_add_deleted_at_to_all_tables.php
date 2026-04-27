<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that now use the SoftDeletes trait.
     *
     * @var array<int, string>
     */
    private array $tables = [
        'users',
        'sekolah',
        'rombel',
        'siswa',
        'mapel',
        'gtk',
        'gtk_keuangan',
        'gtk_pendidikan',
        'gtk_mengajar',
        'gtk_tugas_tambahan',
        'kehadiran_gtk',
        'kelulusan',
        'laporan',
        'laporan_gedung',
        'laporan_gtk',
        'laporan_gtk_kategori',
        'laporan_keuangan',
        'laporan_siswa',
        'laporan_siswa_kategori',
        'laporan_siswa_rekap',
        'notifikasi',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'deleted_at')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'deleted_at')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
