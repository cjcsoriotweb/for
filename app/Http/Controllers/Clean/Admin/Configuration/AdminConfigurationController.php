<?php

namespace App\Http\Controllers\Clean\Admin\Configuration;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Configuration\CreditUpdate;
use App\Http\Requests\Admin\Configuration\PhotoUpdate;
use App\Services\Clean\Account\AccountService;
use App\Models\Team;


class AdminConfigurationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function updatePhoto(PhotoUpdate $request, Team $team)
    {
        $data = $request->validated();
        $path = "teams/{$team->id}/photo.".$data['photo']->extension();
        $data['photo']->storeAs("teams/{$team->id}", basename($path), 'public');
        $team->profile_photo_path = $path;
        $team->save();
        return back()->with('ok', 'Photo mise à jour.');
    }
    public function addCredit(CreditUpdate $request, Team $team)
    {
        $data = $request->validated();
        
        $team->money += $data['montant'];
        $team->save();
        
        return back()->with('ok', 'Crédit ajouté.');

    }

}