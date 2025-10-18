<?php

namespace App\Http\Controllers\Clean\Admin\Formations;
use App\Http\Controllers\Controller;

use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Http\Requests\Admin\Formations\FormationUpdate;
use App\Models\Formation;
use App\Services\FormationService;

class AdminFormationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function update(FormationUpdate $request, Team $team, FormationService $formationService)
    {
        $validated = $request->validated();
        $formation = Formation::findorfail($validated['formation_id']);
      
        $enabled = $validated['enabled'];

        if ($enabled) {
            $formation = $formationService->makeFormationVisibleForTeam($formation, $team);
            return redirect()->route('application.admin.formations.index', $team)->with('status', __("Formation activée avec succès!"));
        } else {
            $formation = $formationService->makeFormationInvisibleForTeam($formation, $team);
        return redirect()->route('application.admin.formations.index', $team)->with('status', __("Formation désactivée avec succès!"));

        }
        return redirect()->route('application.admin.formations.index', $team)->with('status', __("Erreur la formation n'a pas été modifiée !"));


    }
    public function destroy(){

    }

}