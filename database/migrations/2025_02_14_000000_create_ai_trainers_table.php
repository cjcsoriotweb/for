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
        Schema::create('ai_trainers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('model')->nullable();
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->boolean('use_tools')->default(false);
            $table->string('guard')->nullable();
            $table->text('prompt_purpose')->nullable();
            $table->text('prompt_allowed')->nullable();
            $table->text('prompt_not_allowed')->nullable();
            $table->text('prompt_rules')->nullable();
            $table->text('prompt_custom')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_trainers');
    }
};

