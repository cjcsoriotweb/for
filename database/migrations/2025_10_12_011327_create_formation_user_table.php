<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('formation_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Etat global
            $table->enum('status', ['enrolled','in_progress','completed','paused'])->default('enrolled');
            $table->unsignedTinyInteger('progress_percent')->default(0); // 0..100
            $table->foreignId('current_lesson_id')->nullable()->constrained('lessons')->nullOnDelete();

            // Métadonnées
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Scores globaux (optionnels)
            $table->unsignedInteger('score_total')->default(0);
            $table->unsignedInteger('max_score_total')->default(0);

            $table->timestamps();

            $table->unique(['formation_id','user_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('formation_user');
    }
};
