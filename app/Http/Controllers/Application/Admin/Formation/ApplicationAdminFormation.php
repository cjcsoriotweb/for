<?php

namespace App\Http\Controllers\Application\Admin\Formation;

use App\Http\Controllers\Controller;
use App\Models\Team;


class ApplicationAdminFormation extends Controller
{


    public function formationsIndex(Team $team)
    {
        return view('application.admin.formations.index', compact('team'));
    }

    public function formationsList(Team $team)
    {
        return view('application.admin.formations.list', compact('team'));
    }

    /* POST */
    public function formationEnable(Team $team)
    {
  

        $formationId = request()->input('formation_id');
        $x = $team->formations()->updateExistingPivot($formationId, ['visible' => true,'team_id'=>$team->id,'formation_id'=>$formationId]);


        return redirect()->route('application.admin.formations.list', $team)->with('status', __('Formation enabled successfully!'));
    }
    

}