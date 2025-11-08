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
        Schema::create('assistant_messages', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->longText('text');
            $table->boolean('is_ia')->default(0);
            
            
            $table->foreignId('ai_trainer_id')
                ->nullable()
                ->constrained('ai_trainers')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistant_messages');
    }
};
