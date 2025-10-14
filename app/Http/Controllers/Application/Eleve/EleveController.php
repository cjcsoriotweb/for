<?php

namespace App\Http\Controllers\Application\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Team;



class EleveController extends Controller
{
    public function index(Team $team)
    {
        return view('application.eleve.index', compact('team'));
    }

    public function formationIndex(Team $team)
    {
        return view('application.eleve.formationsList', compact('team'));
    }


}