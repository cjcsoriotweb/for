<?php

namespace App\Http\Controllers;

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