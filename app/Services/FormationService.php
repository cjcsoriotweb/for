<?php

namespace App\Services;

use App\Models\Formation;

class FormationService
{
    public function getAllFormations()
    {
        return Formation::all();
    }

    public function createFormation($title, $description, $level, $money_amount)
    {
        return Formation::create([
            'title' => $title,
            'description' => $description,
            'level' => $level,
            'money_amount' => $money_amount,
        ]);
    }
}
