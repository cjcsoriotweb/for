<?php

namespace App\Http\Controllers\Clean\Eleve\Traits;

use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait AuthorizesFormationAccess
{
    /**
     * Verify that the user is enrolled in the formation and return the user
     *
     * @param Team $team
     * @param Formation $formation
     * @return User The authenticated user
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeFormationEnrollment(Team $team, Formation $formation): User
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        return $user;
    }

    /**
     * Verify that the user is enrolled and the formation is completed
     *
     * @param Team $team
     * @param Formation $formation
     * @return User The authenticated user
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeCompletedFormation(Team $team, Formation $formation): User
    {
        $user = $this->authorizeFormationEnrollment($team, $formation);

        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            abort(403, 'Cette formation n\'est pas encore terminée.');
        }

        return $user;
    }
}
