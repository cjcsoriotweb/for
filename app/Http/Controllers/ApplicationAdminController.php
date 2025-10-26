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
    public function formationsIndex(Team $team)
    {
        return view('application.admin.formations.index', compact('team'));
    }

    public function configurationIndex(Team $team)
    {
        return view('application.admin.configuration.index', compact('team'));
    }

    /* Utilisateurs */
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

    /* */

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




}