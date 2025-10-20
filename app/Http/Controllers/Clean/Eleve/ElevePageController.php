<?php

namespace App\Http\Controllers\Clean\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;

class ElevePageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function home(Team $team)
    {
        return view('clean.eleve.home', compact('team'));
    }
}
