<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Console\Application;
use Illuminate\Routing\Controllers\Middleware;


class EleveController extends Controller
{
    public function index(Team $team)
    {
        return view('application.app.index', compact('team'));
    }
    

}