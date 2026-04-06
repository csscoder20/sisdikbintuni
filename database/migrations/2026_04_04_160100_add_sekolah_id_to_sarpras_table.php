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
        Schema::table('tbl_sarpras', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sekolah')->nullable()->after('status_kepemilikan');
            $table->foreign('id_sekolah')->references('id')->on('tbl_sekolah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sarpras', function (Blueprint $table) {
            $table->dropForeign(['id_sekolah']);
            $table->dropColumn('id_sekolah');
        });
    }
};
