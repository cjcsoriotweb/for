<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;


class ApplicationAdminController extends Controller
{

    public function index(Team $team)
    {
        return view('application.admin.index', compact('team'));
    }

    public function configurationIndex(Team $team)
    {
        return view('application.admin.configuration.index', compact('team'));
    }

    public function configurationName(Team $team)
    {
        return view('application.admin.configuration.name', compact('team'));
    }

    public function configurationLogo(Team $team)
    {
        return view('application.admin.configuration.logo', compact('team'));
    }

    public function invitation(Team $team)
    {
        return view('application.admin.invitation', compact('team'));
    }


    public function users(Team $team)
    {
        return view('application.admin.users', compact('team'));
    }

}