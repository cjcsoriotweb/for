<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Console\Application;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Team $team)
    {
        if(Auth::user()->hasTeamRole($team, 'eleve')) {
            return view('application.loading.loadingTemplate', [
                'team'=>$team,
                'redirectUrl'=>route('application.eleve.index', $team),
                'icon'=>'👨‍🎓'
            ]);
        }
        return view('application.index', compact('team'));
    }
    

    public function switch(Team $team)
    {
        // Logic to switch application context can be added here

        return redirect()->route('application.index', ['team' => $team->id])
                         ->with('success', __("Vous êtes dans l'application : <b>$team->name</b>."));
    }

    public function dashboard(Team $team)
    {
        return view('application.app.dashboard', compact('team'));
    }

    public function show(Team $team)
    {
        return view('application.app.show', compact('team'));
    }
}