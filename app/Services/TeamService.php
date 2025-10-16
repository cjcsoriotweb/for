<?php

namespace App\Services;

use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Auth;

class TeamService
{


    public function getUsersTeam(){
        return Auth::user()->allTeams();
    }

    public function getTeamInvitedMe(){
        $email = Auth::user()->email;

        return TeamInvitation::query()
            ->select(['id', 'team_id', 'email', 'role', 'created_at'])
            ->with(['team:id,name'])
            ->where('email', $email)
            ->latest('id')
            ->get();
    }
 


}
