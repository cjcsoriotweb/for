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
        Schema::table('formation_user', function (Blueprint $table) {
            $table->tinyInteger('feedback_rating')->nullable()->after('completion_documents');
            $table->text('feedback_comment')->nullable()->after('feedback_rating');
            $table->timestamp('feedback_at')->nullable()->after('feedback_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->dropColumn(['feedback_rating', 'feedback_comment', 'feedback_at']);
        });
    }
};
