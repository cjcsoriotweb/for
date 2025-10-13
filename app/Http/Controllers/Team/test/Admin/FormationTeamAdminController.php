<?php

namespace App\Http\Controllers\Team\admin;
use App\Models\Team;
use App\Http\Controllers\Controller;


class FormationTeamAdminController extends TeamAdminController
{


    public function formationsDisable(Team $team)
    {
        return redirect()->back()->with('success', 'Formation désactivée.');
    }

    public function formationsEnable(Team $team)
    {
        return redirect()->back()->with('success', 'Formation activée.');
    }



}