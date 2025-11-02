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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_ia_id')->nullable()->constrained('ai_trainers')->onDelete('cascade');
            $table->foreignId('receiver_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Index pour les performances
            $table->index(['sender_user_id', 'receiver_ia_id']);
            $table->index(['sender_user_id', 'receiver_user_id']);
            $table->index(['receiver_ia_id', 'created_at']);
            $table->index(['receiver_user_id', 'created_at']);
            $table->index('is_read');

            // Contrainte : soit receiver_ia_id soit receiver_user_id doit être rempli
            $table->check('receiver_ia_id IS NOT NULL OR receiver_user_id IS NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
