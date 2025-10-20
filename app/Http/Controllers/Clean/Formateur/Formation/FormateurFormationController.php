<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formateur\Formation\UpdateFormationRequest;
use App\Models\Formation;
use App\Models\Team;
use App\Services\FormationService;

class FormateurFormationController extends Controller
{
    public function showFormation(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationShow', compact('formation'));
    }

    public function updateFormation(UpdateFormationRequest $request, Formation $formation)
    {
        $formation->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Formation mise à jour avec succès.');
    }

    public function createFormation(Team $team, FormationService $formationService)
    {
        $formationService->createFormation();
    }
}
