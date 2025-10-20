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
        Schema::create('formation_in_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();

            // Contrôle de visibilité/approbation
            $table->boolean('visible')->default(true);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['formation_id', 'team_id']); // une ligne par (formation, team)
            $table->index(['team_id', 'visible']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_in_teams');
    }
};
