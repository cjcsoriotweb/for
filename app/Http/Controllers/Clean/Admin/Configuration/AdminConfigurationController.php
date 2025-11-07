<?php

namespace App\Http\Controllers\Clean\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Configuration\PhotoUpdate;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminConfigurationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}


    public function updatePhoto(PhotoUpdate $request, Team $team)
    {
        $data = $request->validated();
        $path = "teams/{$team->id}/photo.".$data['photo']->extension();
        $data['photo']->storeAs("teams/{$team->id}", basename($path), 'public');
        $team->profile_photo_path = $path;
        $team->save();

        return back()->with('ok', 'Photo mise a jour.');
    }

   
}
