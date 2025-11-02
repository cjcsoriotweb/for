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
        // Migration devenue obsolète : les formateurs IA sont gérés via la base de données.
        // On ne supprime plus aucune table pour préserver les données existantes.
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
