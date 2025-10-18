<?php

namespace App\Services\Clean\Account;

use App\Models\Team;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

class TeamService
{
    public function listByUser(User $user)
    {
        // Retourne la liste des applications (teams) liees a l'utilisateur.
        return $user->teams;
    }
    public function switchTeam(User $user, Team $team)
    {
        $role = $user->teamRole($team)->key;

        if ($role) {
            return redirect()->route('application.admin.index', ['team' => $team]);
        }

        return abort(403, __('You do not have permission to access this team.'));
    }
}