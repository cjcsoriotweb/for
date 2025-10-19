<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;

class FormateurPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function home()
    {

        return view('clean.formateur.FormateurHomePage');
    }

    public function edit(Formation $formation)
    {
        $formation->load(['chapters.lessons']);

        return view('clean.formateur.FormationEditPage', compact('formation'));
    }
}
