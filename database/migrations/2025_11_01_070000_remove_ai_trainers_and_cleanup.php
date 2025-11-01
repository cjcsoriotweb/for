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
        // Supprimer les tables ai_trainers et ai_trainer_formation
        Schema::dropIfExists('ai_trainer_formation');
        Schema::dropIfExists('ai_trainers');

        // Supprimer la colonne ai_trainer_id de ai_conversations
        if (Schema::hasTable('ai_conversations')) {
            Schema::table('ai_conversations', function (Blueprint $table) {
                if (Schema::hasColumn('ai_conversations', 'ai_trainer_id')) {
                    $table->dropForeign(['ai_trainer_id']);
                    $table->dropColumn('ai_trainer_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: cette migration ne peut pas être inversée car nous supprimons des données
        // Les trainers sont maintenant définis dans config/ai.php
    }
};
