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
        // 1. Add user_id to tbl_sekolah
        Schema::table('tbl_sekolah', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // 2. Data Migration: Move existing sekolah_id from users to tbl_sekolah
        $users = DB::table('users')->whereNotNull('sekolah_id')->get();
        foreach ($users as $user) {
            DB::table('tbl_sekolah')
                ->where('id', $user->sekolah_id)
                ->update(['user_id' => $user->id]);
        }

        // 3. Remove sekolah_id from users
        Schema::table('users', function (Blueprint $table) {
            // Check if constraint exists before dropping (PostgreSQL/Laravel behavior)
            // In Laravel 12+, we can just drop the column
            $table->dropColumn('sekolah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('sekolah_id')->nullable();
        });

        $sekolahs = DB::table('tbl_sekolah')->whereNotNull('user_id')->get();
        foreach ($sekolahs as $sekolah) {
            DB::table('users')
                ->where('id', $sekolah->user_id)
                ->update(['sekolah_id' => $sekolah->id]);
        }

        Schema::table('tbl_sekolah', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
