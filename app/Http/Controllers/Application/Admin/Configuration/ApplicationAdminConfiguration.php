<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;


class ApplicationAdminFormation extends Controller
{

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

}
