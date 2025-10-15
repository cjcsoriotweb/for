<?php

namespace App\Services;

use App\Models\Formation;

class FormationService
{



    /*
    * Récupère toutes les formations
    */
    public function getAllFormations()
    {
        return Formation::all();
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
