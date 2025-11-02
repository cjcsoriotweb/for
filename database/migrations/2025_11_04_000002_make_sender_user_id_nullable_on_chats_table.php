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
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign('chats_sender_user_id_foreign');
        });

        DB::statement('ALTER TABLE chats MODIFY sender_user_id BIGINT UNSIGNED NULL;');

        Schema::table('chats', function (Blueprint $table) {
            $table->foreign('sender_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        DB::statement("
            UPDATE chats
            SET sender_ia_id = JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.ai.trainer_id'))
            WHERE sender_ia_id IS NULL
              AND JSON_EXTRACT(metadata, '$.ai.trainer_id') IS NOT NULL
        ");

        DB::statement("
            UPDATE chats
            SET sender_user_id = NULL
            WHERE sender_ia_id IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign('chats_sender_user_id_foreign');
        });

        DB::statement('UPDATE chats SET sender_user_id = receiver_user_id WHERE sender_user_id IS NULL;');

        DB::statement('ALTER TABLE chats MODIFY sender_user_id BIGINT UNSIGNED NOT NULL;');

        Schema::table('chats', function (Blueprint $table) {
            $table->foreign('sender_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
