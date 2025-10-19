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
        Schema::table('text_contents', function (Blueprint $table) {
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content');
            $table->unsignedInteger('estimated_read_time')->nullable();
            $table->boolean('allow_download')->default(false);
            $table->boolean('show_progress')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_contents', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn(['lesson_id', 'title', 'description', 'content', 'estimated_read_time', 'allow_download', 'show_progress']);
        });
    }
};
