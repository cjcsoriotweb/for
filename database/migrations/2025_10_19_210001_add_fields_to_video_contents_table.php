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
        Schema::table('video_contents', function (Blueprint $table) {
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_contents', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn(['lesson_id', 'title', 'description', 'video_url', 'video_path', 'duration_minutes']);
        });
    }
};
