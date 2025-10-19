<?php

namespace App\Services\Formation;

use App\Models\Formation;

class SuperAdminFormationService extends BaseFormationService
{
    /**
     * Create a formation with default values suitable for a super admin.
     */
    public function createFormation(array $attributes = []): Formation
    {
        $payload = array_replace([
            'title' => 'Titre par defaut',
            'description' => 'Description par defaut',
            'level' => 'debutant',
            'money_amount' => 0,
        ], $attributes);

        return Formation::create($payload);
    }

    public function createChapter(Formation $formation, array $attributes = [])
    {
        $payload = array_replace([
            'title' => 'Nouveau Chapitre',
            'position' => $formation->chapters()->count() + 1,
        ], $attributes);

        return $formation->chapters()->create($payload);
    }
}