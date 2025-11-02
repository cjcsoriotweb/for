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
            $table->foreignId('entry_quiz_attempt_id')
                ->nullable()
                ->after('max_score_total')
                ->constrained('quiz_attempts')
                ->nullOnDelete();

            $table->decimal('entry_quiz_score', 5, 2)
                ->nullable()
                ->after('entry_quiz_attempt_id');

            $table->timestamp('entry_quiz_completed_at')
                ->nullable()
                ->after('entry_quiz_score');

            $table->foreignId('post_quiz_attempt_id')
                ->nullable()
                ->after('entry_quiz_completed_at')
                ->constrained('quiz_attempts')
                ->nullOnDelete();

            $table->decimal('post_quiz_score', 5, 2)
                ->nullable()
                ->after('post_quiz_attempt_id');

            $table->timestamp('post_quiz_completed_at')
                ->nullable()
                ->after('post_quiz_score');

            $table->decimal('quiz_progress_delta', 5, 2)
                ->nullable()
                ->after('post_quiz_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->dropForeign(['entry_quiz_attempt_id']);
            $table->dropForeign(['post_quiz_attempt_id']);

            $table->dropColumn([
                'entry_quiz_attempt_id',
                'entry_quiz_score',
                'entry_quiz_completed_at',
                'post_quiz_attempt_id',
                'post_quiz_score',
                'post_quiz_completed_at',
                'quiz_progress_delta',
            ]);
        });
    }
};
