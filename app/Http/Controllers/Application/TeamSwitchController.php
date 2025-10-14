<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamSwitchController extends Controller
{
    public function store(Request $request, Team $team)
    {
        $request->user()->switchTeam($team);

        return redirect()->route('team.dashboard', ['team' => $team->id]);
    }
}