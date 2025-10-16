<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class TeamService
{


    public function getUsersTeam(){
        return Auth::user()->allTeams();
    }
 


}
