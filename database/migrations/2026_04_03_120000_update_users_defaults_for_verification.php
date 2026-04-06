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
        Schema::table('users', function (Blueprint $table) {
            // Change is_verified to default true
            $table->boolean('is_verified')->default(true)->change();
            
            // Remove the default for role so it doesn't automatically become 'operator' for CLI users
            $table->string('role')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->change();
            $table->enum('role', ['admin', 'operator'])->default('operator')->change();
        });
    }
};
