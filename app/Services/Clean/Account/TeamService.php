<?php

namespace App\Services\Clean\Account;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamService
{
    public function listByUser(User $user)
    {
        // Retourne la liste des applications (teams) liees a l'utilisateur.
        return $user->allTeams();
    }

    public function str_slug($string)
    {
        // Convertit une chaîne en slug (format URL-friendly).
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function switchTeam(User $user, Team $team)
    {
        $role = $user->teamRole($team)->key;

        if (Auth::user()->superadmin) {
            return redirect()->route('application.admin.index', ['team' => $team, 'team_name' => $this->str_slug($team->name)]);
        }

        if (Auth::user()->hasTeamRole($team, 'manager')) {
            return redirect()->route('organisateur.index', ['team' => $team]);
        }

        if (Auth::user()->hasTeamRole($team, 'eleve')) {
            return redirect()->route('eleve.index', ['team' => $team]);
        }



        return abort(403, __('You do not have permission to access this team.'));
    }
}
