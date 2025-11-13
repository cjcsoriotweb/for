<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_with_bots', function (Blueprint $table) {
            $table->foreignId('chatbot_conversation_id')
                ->nullable()
                ->after('conversation')
                ->constrained('chatbot_conversations')
                ->cascadeOnDelete();

            $table->foreignId('chatbot_model_id')
                ->nullable()
                ->after('chatbot_conversation_id')
                ->constrained('chatbot_models')
                ->nullOnDelete();
        });

        $existingConversations = DB::table('chat_with_bots')
            ->select('user_id', 'conversation')
            ->where('conversation', '>', 0)
            ->distinct()
            ->get();

        foreach ($existingConversations as $record) {
            $conversationId = DB::table('chatbot_conversations')->insertGetId([
                'user_id' => $record->user_id,
                'chatbot_model_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('chat_with_bots')
                ->where('user_id', $record->user_id)
                ->where('conversation', $record->conversation)
                ->update([
                    'chatbot_conversation_id' => $conversationId,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_with_bots', function (Blueprint $table) {
            $table->dropForeign(['chatbot_conversation_id']);
            $table->dropForeign(['chatbot_model_id']);
            $table->dropColumn(['chatbot_conversation_id', 'chatbot_model_id']);
        });
    }
};
