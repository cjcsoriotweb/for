<?php

namespace App\Http\Controllers\Clean\Admin\Formations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Formations\FormationCreateNameByTeam;
use App\Http\Requests\Admin\Formations\FormationUpdateVisibilityByTeam;
use App\Models\Formation;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;

class AdminFormationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function storeNewFormationByTitle(FormationCreateNameByTeam $request)
    {
        $validated = $request->validated();
        $title = $validated['formation']['title'];
        $description = $validated['formation']['description'];

        app(FormationService::class)->createFormation(['title' => $title, 'description' => $description]);

        return redirect()->back()->with('status', __('Formation créée avec succès!'));
    }

    public function updateVisibilityByTeam(FormationUpdateVisibilityByTeam $request, Team $team, FormationService $formationService)
    {
        $validated = $request->validated();
        $formation = Formation::findorfail($validated['formation_id']);

        $enabled = $validated['enabled'];

        if ($enabled) {
            $formation = $formationService->admin()->makeFormationVisibleForTeam($formation, $team);

            return redirect()->route('application.admin.formations.index', $team)->with('status', __('Formation activée avec succès!'));
        } else {
            $formation = $formationService->admin()->makeFormationInvisibleForTeam($formation, $team);

            return redirect()->route('application.admin.formations.index', $team)->with('status', __('Formation désactivée avec succès!'));
        }

        return redirect()->route('application.admin.formations.index', $team)->with('status', __("Erreur la formation n'a pas été modifiée !"));
    }
}
