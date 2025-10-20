<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\Team;

/**
 * Service de gestion de la visibilité des formations pour les équipes
 */
class FormationVisibilityService
{
    /**
     * Vérifie si une formation est visible pour une équipe donnée
     */
    public function isFormationVisibleForTeam(Formation $formation, Team $team): bool
    {
        return $formation->teams()
            ->where('teams.id', $team->id)
            ->wherePivot('visible', true)
            ->exists();
    }

    /**
     * Rend une formation visible pour une équipe
     */
    public function makeFormationVisibleForTeam(Formation $formation, Team $team): void
    {
        $team->formations()->syncWithoutDetaching([
            $formation->id => ['visible' => true],
        ]);
    }

    /**
     * Rend une formation invisible pour une équipe
     */
    public function makeFormationInvisibleForTeam(Formation $formation, Team $team): void
    {
        $team->formations()->syncWithoutDetaching([
            $formation->id => ['visible' => false],
        ]);
    }

    /**
     * Obtient toutes les formations visibles pour une équipe
     */
    public function getVisibleFormationsForTeam(Team $team): \Illuminate\Database\Eloquent\Collection
    {
        return $team->formations()
            ->wherePivot('visible', true)
            ->get();
    }
}
