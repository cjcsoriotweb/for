<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Console\Application;
use Illuminate\Routing\Controllers\Middleware;


class ApplicationController extends Controller
{
    public function index(Team $team)
    {
        return view('application.index', compact('team'));
    }

    public function indexCgu(Team $team)
    {
        return view('application.cgu', compact('team'));
    }

}