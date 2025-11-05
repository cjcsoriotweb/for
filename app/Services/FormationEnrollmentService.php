<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\FormationInTeams;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Service de gestion des inscriptions aux formations
 */
class FormationEnrollmentService
{
    /**
     * Vérifie si l'équipe dispose encore d'unités d'usage pour une formation donnée.
     */
    public function canTeamAffordFormation(Team $team, Formation $formation): bool
    {
        $pivot = FormationInTeams::query()
            ->where('formation_id', $formation->id)
            ->where('team_id', $team->id)
            ->first();

        if (! $pivot || ! $pivot->visible) {
            return false;
        }

        return $pivot->usage_quota === null || $pivot->usage_quota > $pivot->usage_consumed;
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
     * Inscrit un utilisateur à une formation et consomme une unité d'usage
     */
    public function enrollUser(Formation $formation, Team $team, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        return (bool) DB::transaction(function () use ($formation, $team, $userId) {
            $pivot = FormationInTeams::query()
                ->where('formation_id', $formation->id)
                ->where('team_id', $team->id)
                ->lockForUpdate()
                ->first();

            if (! $pivot || ! $pivot->visible) {
                return false;
            }

            if ($pivot->usage_quota !== null && $pivot->usage_quota <= $pivot->usage_consumed) {
                return false;
            }

            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            $formation->learners()->syncWithoutDetaching([
                $userId => [
                    'team_id' => $team->id,
                    'status' => 'in_progress',
                    'enrolled_at' => now(),
                    'last_seen_at' => now(),
                    'current_lesson_id' => $firstLesson?->id,
                ],
            ]);

            $pivot->increment('usage_consumed');

            return true;
        });
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
