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
        // 1. Drop the existing check constraint first
        DB::statement('ALTER TABLE gtk DROP CONSTRAINT IF EXISTS gtk_jenis_kelamin_check');

        // 2. Update existing data
        DB::table('gtk')->where('jenis_kelamin', 'L')->update(['jenis_kelamin' => 'Laki-laki']);
        DB::table('gtk')->where('jenis_kelamin', 'P')->update(['jenis_kelamin' => 'Perempuan']);

        // 3. Add the new check constraint
        DB::statement("ALTER TABLE gtk ADD CONSTRAINT gtk_jenis_kelamin_check CHECK (jenis_kelamin IN ('Laki-laki', 'Perempuan'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Update data back to initials
        DB::table('gtk')->where('jenis_kelamin', 'Laki-laki')->update(['jenis_kelamin' => 'L']);
        DB::table('gtk')->where('jenis_kelamin', 'Perempuan')->update(['jenis_kelamin' => 'P']);

        // 2. Revert the check constraint
        DB::statement('ALTER TABLE gtk DROP CONSTRAINT IF EXISTS gtk_jenis_kelamin_check');
        DB::statement("ALTER TABLE gtk ADD CONSTRAINT gtk_jenis_kelamin_check CHECK (jenis_kelamin IN ('L', 'P'))");
    }
};
