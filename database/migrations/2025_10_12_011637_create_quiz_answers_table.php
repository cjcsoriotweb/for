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
        Schema::create('quiz_answers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $t->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $t->foreignId('choice_id')->nullable()->constrained('quiz_choices')->nullOnDelete(); // null si réponse texte
            $t->text('text_answer')->nullable();
            $t->boolean('is_correct')->default(false);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
