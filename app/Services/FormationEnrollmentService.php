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
     * Vérifie si un utilisateur est déjà inscrit à une formation
     */
    public function isUserEnrolled(Formation $formation, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        return $formation->learners()->where('users.id', $userId)->exists();
    }

    /**
     * Inscrit un utilisateur à une formation
     */
    public function enrollUser(Formation $formation, Team $team, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        $formation->learners()->attach($userId, [
            'team_id' => $team->id,
            'status' => 'in_progress',
            'enrolled_at' => now(),
            'last_seen_at' => now(),
            'progress_percent' => 0,
            'current_lesson_id' => null,
        ]);

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
