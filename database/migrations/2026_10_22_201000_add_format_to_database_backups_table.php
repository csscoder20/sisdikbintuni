<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('database_backups', function (Blueprint $table): void {
            $table->string('format', 20)->default('custom')->after('database');
        });
    }

    public function down(): void
    {
        Schema::table('database_backups', function (Blueprint $table): void {
            $table->dropColumn('format');
        });
    }
};
