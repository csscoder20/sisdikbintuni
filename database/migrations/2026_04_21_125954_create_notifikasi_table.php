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
        Schema::create('notifikasi', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('subject');
            $blueprint->text('content');
            $blueprint->string('recipient_type');
            $blueprint->json('target_ids')->nullable();
            $blueprint->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
