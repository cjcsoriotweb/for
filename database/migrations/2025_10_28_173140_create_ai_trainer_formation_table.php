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
        Schema::create('ai_trainer_formation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_trainer_id')
                ->constrained('ai_trainers')
                ->nullable()
                ->cascadeOnDelete();
            $table->foreignId('formation_id')
                ->constrained('formations')
                ->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['ai_trainer_id', 'formation_id']);
            $table->index(['formation_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_trainer_formation');
    }
};
