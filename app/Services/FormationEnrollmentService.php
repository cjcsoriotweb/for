<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

/**
 * Service de gestion des inscriptions aux formations
 */
class FormationEnrollmentService
{
    /**
     * Vérifie si l'équipe a les fonds nécessaires pour commencer une formation
     */
    public function canTeamAffordFormation(Team $team, Formation $formation): bool
    {
        return $team->money >= $formation->money_amount;
    }

    /**
     * Vérifie si un utilisateur est déjà inscrit à une formation
     */
    public function isUserEnrolled(Formation $formation, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        return $formation->learners()->where('users.id', $userId)->exists();
    }

    /**
     * Inscrit un utilisateur à une formation et débite les fonds
     */
    public function enrollUser(Formation $formation, Team $team, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        // Vérifier que l'équipe a les fonds nécessaires
        if (! $this->canTeamAffordFormation($team, $formation)) {
            return false;
        }

        // Récupérer la première leçon de la formation pour initialiser current_lesson_id
        $firstLesson = $formation->chapters()
            ->orderBy('position')
            ->first()
            ?->lessons()
            ->orderBy('position')
            ->first();

        $formation->learners()->attach($userId, [
            'team_id' => $team->id,
            'status' => 'in_progress',
            'enrolled_at' => now(),
            'last_seen_at' => now(),
            'current_lesson_id' => $firstLesson?->id,
        ]);

        // Débiter les fonds de l'équipe
        $team->decrement('money', $formation->money_amount);

        return true;
    }

    /**
     * Obtient l'inscription d'un utilisateur pour une formation spécifique
     */
    public function getUserEnrollment(Formation $formation, ?int $userId = null): ?\Illuminate\Database\Eloquent\Relations\Pivot
    {
        $userId = $userId ?? Auth::id();

        return $formation->learners()->where('users.id', $userId)->first()?->pivot;
    }
}
