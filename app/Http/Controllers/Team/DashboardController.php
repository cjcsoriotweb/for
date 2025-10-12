<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show($team)
    {
        $team = Team::findOrFail($team);
        return view('team.dashboard', ['team' => $team]);
    }
}
