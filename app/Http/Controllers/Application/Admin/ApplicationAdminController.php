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


}