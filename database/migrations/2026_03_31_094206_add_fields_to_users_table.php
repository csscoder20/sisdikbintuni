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
            $table->string('nohp')->nullable();
            $table->unsignedBigInteger('sekolah_id')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('role', ['admin', 'operator'])->default('operator');

            $table->foreign('sekolah_id')->references('id')->on('tbl_sekolah')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
            $table->dropColumn(['nohp', 'sekolah_id', 'is_verified', 'role']);
        });
    }
};
