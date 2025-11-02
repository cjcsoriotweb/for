<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->foreignId('primary_ai_trainer_id')
                ->nullable()
                ->constrained('ai_trainers')
                ->nullOnDelete()
                ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            if (Schema::hasColumn('formations', 'primary_ai_trainer_id')) {
                $table->dropForeign(['primary_ai_trainer_id']);
                $table->dropColumn('primary_ai_trainer_id');
            }
        });
    }
};

