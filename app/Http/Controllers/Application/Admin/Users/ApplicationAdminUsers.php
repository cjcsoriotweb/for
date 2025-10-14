<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;


class ApplicationAdminUsers extends Controller
{
    public function usersIndex(Team $team)
    {
        return view('application.admin.users.index', compact('team'));
    }

    public function usersManager(Team $team)
    {
        return view('application.admin.users.manager', compact('team'));
    }
    public function usersList(Team $team)
    {
        return view('application.admin.users.list', compact('team'));
    }

    public function invitation(Team $team)
    {
        return view('application.admin.invitation', compact('team'));
    }


}
