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
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('sender_ia_id')
                ->nullable()
                ->after('sender_user_id')
                ->constrained('ai_trainers')
                ->nullOnDelete();

            $table->index(['sender_ia_id', 'receiver_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropIndex(['sender_ia_id', 'receiver_user_id']);
            $table->dropConstrainedForeignId('sender_ia_id');
        });
    }
};
