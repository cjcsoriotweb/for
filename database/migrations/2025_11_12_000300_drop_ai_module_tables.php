<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formation_categories', function (Blueprint $table) {
            if (Schema::hasColumn('formation_categories', 'ai_trainer_id')) {
                $table->dropForeign(['ai_trainer_id']);
                $table->dropColumn('ai_trainer_id');
            }
        });

        Schema::table('formations', function (Blueprint $table) {
            if (Schema::hasColumn('formations', 'primary_ai_trainer_id')) {
                $table->dropForeign(['primary_ai_trainer_id']);
                $table->dropColumn('primary_ai_trainer_id');
            }
        });

        Schema::table('chats', function (Blueprint $table) {
            if (Schema::hasColumn('chats', 'receiver_ia_id')) {
                $table->dropForeign(['receiver_ia_id']);
                $table->dropColumn('receiver_ia_id');
            }
            if (Schema::hasColumn('chats', 'sender_ia_id')) {
                $table->dropForeign(['sender_ia_id']);
                $table->dropColumn('sender_ia_id');
            }
        });

        DB::table('chats')
            ->whereNull('sender_user_id')
            ->orWhereNull('receiver_user_id')
            ->delete();

        if (Schema::hasColumn('chats', 'sender_user_id')) {
            DB::statement('ALTER TABLE chats MODIFY sender_user_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('chats', 'receiver_user_id')) {
            DB::statement('ALTER TABLE chats MODIFY receiver_user_id BIGINT UNSIGNED NOT NULL');
        }

        $tablesToDrop = [
            'ia_chat_messages',
            'assistant_messages',
            'ai_conversation_messages',
            'ai_conversations',
            'ai_trainers',
        ];

        foreach ($tablesToDrop as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        // Le module IA a �t� d�finitivement retir�.
    }
};
