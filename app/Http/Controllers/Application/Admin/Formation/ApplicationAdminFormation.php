<?php

namespace App\Http\Controllers\Application\Admin\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditVisibleFormationRequest;
use App\Models\Formation;
use App\Models\FormationInTeams;
use App\Models\Team;
use App\Services\FormationService;
use App\Services\FormationVisibilityService;

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

    public function formationsList(Team $team, FormationService $formations)
    {   
       
        $formations = $formations->team()->listWithTeamFlags(Team::find(1));


        return view('application.admin.formations.list', compact('team', 'formations'));
    }

    /* Client Request Post */
    // Enable Formation By Admin Team
    public function formationEnable(EditVisibleFormationRequest $request, Team $team)
    {
        $validated = $request->validated();

        $formation = (new FormationService())->makeFormationVisibleForTeam(Formation::find($validated['formation_id']), $team);
        

        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation enabled successfully!'));
    }

    /* Client Request Post */
    // Disable Formation By Admin Team
    public function formationDisable(EditVisibleFormationRequest $request, Team $team)
    {
        $validated = $request->validated();

        $formation = (new FormationService())->makeFormationInvisibleForTeam(Formation::find($validated['formation_id']), $team);

        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation disabled successfully!'));
    }
    

}
