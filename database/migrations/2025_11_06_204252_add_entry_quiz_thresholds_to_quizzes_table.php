<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedTinyInteger('entry_min_score')
                ->nullable()
                ->after('passing_score');
            $table->unsignedTinyInteger('entry_max_score')
                ->nullable()
                ->after('entry_min_score');
        });

        DB::table('quizzes')
            ->where('type', 'entry')
            ->update([
                'entry_min_score' => DB::raw('COALESCE(entry_min_score, 0)'),
                'entry_max_score' => DB::raw('COALESCE(entry_max_score, passing_score)'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['entry_min_score', 'entry_max_score']);
        });
    }
};
