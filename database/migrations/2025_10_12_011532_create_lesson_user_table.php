<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lesson_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Etat de la leçon
            $table->enum('status', ['not_started','in_progress','completed'])->default('not_started');

            // Vidéo : progression en secondes (si applicable)
            $table->unsignedInteger('watched_seconds')->default(0);

            // Quiz : meilleur score (si applicable)
            $table->unsignedInteger('best_score')->default(0);
            $table->unsignedInteger('max_score')->default(0);
            $table->unsignedSmallInteger('attempts')->default(0);

            // Texte : % de lecture (optionnel)
            $table->unsignedTinyInteger('read_percent')->default(0);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['lesson_id','user_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('lesson_user');
    }
};
