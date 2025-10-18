<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function dashboard(Team $team)
    {
        
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminApplication', compact(['organisations', 'team']));
    }

    public function users(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminApplication', compact(['organisations', 'team']));
    }


}