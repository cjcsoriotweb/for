<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;

class FormateurPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function home(Team $team, FormationService $formations)
    {

        return view('clean.formateur.FormateurHomePage');
    }
}
