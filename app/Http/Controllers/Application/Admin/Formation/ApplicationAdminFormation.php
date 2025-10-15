<?php

namespace App\Http\Controllers\Application\Admin\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisibleFormationRequest;
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
    public function formationEnable(VisibleFormationRequest $request, Team $team)
    {
        $validated = $request->validated();

        $formation = Formation::find($validated['formation_id']);
        $this->visibilityService->makeFormationVisibleForTeam($formation, $team);

        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation enabled successfully!'));
    }

    public function formationDisable(VisibleFormationRequest $request, Team $team)
    {
        $validated = $request->validated();

        $formation = Formation::find($validated['formation_id']);
        $this->visibilityService->makeFormationInvisibleForTeam($formation, $team);

        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation disabled successfully!'));
    }
    

}
