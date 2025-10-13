<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Routing\Controllers\Middleware;


class ApplicationController extends Controller
{
    public function index(Team $team)
    {
        return view('application.app.index', compact('team'));
    }

    public function dashboard(Team $team)
    {
        return view('application.app.dashboard', compact('team'));
    }

    public function admin(Team $team)
    {
        return view('application.admin.index', compact('team'));
    }

    public function show(Team $team)
    {
        return view('application.app.show', compact('team'));
    }
}