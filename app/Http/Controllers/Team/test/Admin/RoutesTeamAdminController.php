<?php

namespace App\Http\Controllers\Team\admin;
use App\Models\Team;
use App\Http\Controllers\Controller;


class RoutesTeamAdminController extends TeamAdminController
{
    public function index(Team $team)
    {
        $users = $team->users;

        return view('team.admin.index', [
            'team' => $team,
            'users' => $users,
        ]);

  
    }
    
    public function membersIndex(Team $team)
    {
        return view('team.admin.users.index', [
            'team' => $team,
        ]);
    }




}