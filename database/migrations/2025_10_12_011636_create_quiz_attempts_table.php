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
        Schema::create('quiz_attempts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('score')->default(0);
            $t->unsignedInteger('max_score')->default(0);
            $t->unsignedSmallInteger('duration_seconds')->default(0);
            $t->timestamp('started_at')->nullable();
            $t->timestamp('submitted_at')->nullable();
            $t->timestamps();
            $t->index(['quiz_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
