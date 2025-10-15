<?php

namespace App\Http\Controllers\Application\Admin\Formation;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Services\FormationVisibilityService;
use Illuminate\Http\Request;

class ApplicationAdminFormation extends Controller
{
    private FormationVisibilityService $visibilityService;

    public function __construct(FormationVisibilityService $visibilityService)
    {
        $this->visibilityService = $visibilityService;
    }


    public function formationsIndex(Team $team)
    {
        return view('application.admin.formations.index', compact('team'));
    }

    public function formationsList(Team $team)
    {
        return view('application.admin.formations.list', compact('team'));
    }

    /* POST */
    public function formationEnable(Request $request, Team $team)
    {
        $validated = $request->validate([
            'formation_id' => 'required|exists:formations,id',
        ]);

        $formation = Formation::find($validated['formation_id']);
        $this->visibilityService->makeFormationVisibleForTeam($formation, $team);

        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation enabled successfully!'));
    }

    public function formationDisable(Request $request, Team $team)
    {
        $validated = $request->validate([
            'formation_id' => 'required|exists:formations,id',
        ]);

        $formation = Formation::find($validated['formation_id']);
        $this->visibilityService->makeFormationInvisibleForTeam($formation, $team);

        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation disabled successfully!'));
    }
    

}
