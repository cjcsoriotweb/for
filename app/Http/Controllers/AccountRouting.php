<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TeamService;

class AccountRouting extends Controller
{
    public function index()
    {
        return view('auth.vous.index', [
            'items' => (new TeamService())->getUsersTeam(),
            'route' => 'team.show'
        ]);
    }
}
