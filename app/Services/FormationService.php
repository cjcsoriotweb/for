<?php

namespace App\Services;

use App\Models\Formation;

class FormationService
{
    public function getFormations()
    {
        return Formation::all();
    }
}
