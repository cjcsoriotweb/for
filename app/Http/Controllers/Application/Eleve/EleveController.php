<?php

namespace App\Http\Controllers\Application\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Formation;
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

    public function formationPreview(Team $team, Formation $formation)
    {
        return view('application.eleve.formationPreview', compact('team', 'formation'));
    }

    public function formationContinue(Team $team, Formation $formation)
    {
        return view('application.eleve.formationContinue', compact('team', 'formation'));
    }

    public function formationEnable(Team $team, Formation $formation)
    {
        return redirect()->route('application.eleve.formation.continue', [$team,$formation])->with('success', "La formation '{$formation->title}' a été activée.");
    }

}