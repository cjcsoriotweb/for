<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            // FK vers chapters.id (c'est la clé attendue par les relations)
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();

            $table->string('title');
            $table->unsignedInteger('position')->default(1)->index();

            // Polymorphe vers VideoContent / TextContent / Quiz
            $table->unsignedBigInteger('lessonable_id')->nullable()->index();
            $table->string('lessonable_type')->nullable()->index();

            $table->timestamps();

            // (optionnel) index composite polymorphe
            $table->index(['lessonable_type', 'lessonable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
