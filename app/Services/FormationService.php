<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\FormationTeam;
use App\Models\Team;

class FormationService
{



    /*
    * Récupère toutes les formations
    */
    public function getAllFormations()
    {
        return Formation::all();
    }

    /*
    * Récupère toutes les formations visibles pour un équipe
    */

    public function getVisibleFormations(Team $team)
    {
        return Formation::ForTeam($team->id)->get();
    }

    public function getDisponibleFormations()
    {
        return Formation::get();
    }


    public function createFormation($title = "Titre par défaut", $description = "Description par défaut", $level = "debutant", $money_amount = 0)
    {
        return Formation::create([
            'title' => $title,
            'description' => $description,
            'level' => $level,
            'money_amount' => $money_amount,
        ]);
    }


}
