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


}