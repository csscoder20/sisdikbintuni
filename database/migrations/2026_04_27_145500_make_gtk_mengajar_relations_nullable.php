<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gtk_mengajar')) {
            return;
        }

        DB::statement('ALTER TABLE gtk_mengajar ALTER COLUMN rombel_id DROP NOT NULL');
        DB::statement('ALTER TABLE gtk_mengajar ALTER COLUMN mapel_id DROP NOT NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('gtk_mengajar')) {
            return;
        }

        DB::statement('ALTER TABLE gtk_mengajar ALTER COLUMN rombel_id SET NOT NULL');
        DB::statement('ALTER TABLE gtk_mengajar ALTER COLUMN mapel_id SET NOT NULL');
    }
};
