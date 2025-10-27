<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formateur\Formation\UpdateFormationRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationPricingRequest;
use App\Models\Formation;
use App\Services\FormationService;

class FormateurFormationController extends Controller
{
    public function showFormation(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationShow', compact('formation'));
    }

    public function editFormation(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationEdit', compact('formation'));
    }

    public function editPricing(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationPricing', compact('formation'));
    }

    public function manageChapters(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationChapters', compact('formation'));
    }

    public function updateFormation(UpdateFormationRequest $request, Formation $formation)
    {
        $formation->update([
            'title' => $request->title,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : $formation->active,
        ]);

        return back()->with('success', 'Formation mise à jour avec succès.');
    }

    public function updatePricing(UpdateFormationPricingRequest $request, Formation $formation)
    {
        $formation->update([
            'money_amount' => $request->money_amount ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarification mise à jour avec succès.',
            'new_price' => $formation->fresh()->money_amount
        ]);
    }

    public function toggleStatus(Formation $formation)
    {
        $formation->update([
            'active' => ! $formation->active,
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
