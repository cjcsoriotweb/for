<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Configuration\PhotoUpdate;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use App\Services\FormationVisibilityService;
use Illuminate\Support\Facades\Auth;

class AdminConfigurationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function update(PhotoUpdate $request, Team $team)
    {
        $data = $request->validated();
        $path = "teams/{$team->id}/photo.".$data['photo']->extension();
        $data['photo']->storeAs("teams/{$team->id}", basename($path), 'public');
        $team->profile_photo_path = $path;
        $team->save();
        return back()->with('ok', 'Photo mise à jour.');
    }
    public function destroy(){

    }

}