<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formateur\Formation\UpdateFormationRequest;
use App\Models\Formation;
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
            'money_amount' => $request->money_amount,
            'active' => $request->has('active') ? (bool) $request->active : $formation->active,
        ]);

        return back()->with('success', 'Formation mise à jour avec succès.');
    }

    public function toggleStatus(Formation $formation)
    {
        $formation->update([
            'active' => !$formation->active,
        ]);

        $status = $formation->active ? 'activée' : 'désactivée';

        return response()->json([
            'success' => true,
            'message' => "Formation {$status} avec succès.",
            'active' => $formation->active,
        ]);
    }

    public function createFormation(FormationService $formationService)
    {
        $formation = $formationService->createFormation();

        return redirect()->route('formateur.formation.show', [$formation]);
    }
}
