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
    public function switchTeam(User $user, Team$team){
        $role = $user->teamRole($team)->key;
        switch ($role) {
            case 'eleve':
                return view('application.loading.template', [
                    'team'=>$team,
                    'redirectUrl'=>route('application.eleve.index', $team),
                    'icon'=>'👨‍🎓'
                ]);
                break;
            case 'manager':
                return view('application.loading.template', [
                    'team'=>$team,
                    'redirectUrl'=>route('application.manager.index', $team),
                    'icon'=>'👨‍🏫'
                ]);
                break;
            case 'admin':
                return view('application.loading.template', [
                    'team'=>$team,
                    'redirectUrl'=>route('application.admin.index', $team),
                    'icon'=>'👨‍💻'
                ]);
                break;
            default:
                return view('application.index', compact('team'));
                break;
        }
        return abort(403, 'Aucun rôle trouvé');
    }
}