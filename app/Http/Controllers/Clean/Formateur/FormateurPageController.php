<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
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

    public function showFormation(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationShow', compact('formation'));
    }

    public function editChapter(Formation $formation, Chapter $chapter)
    {
        // Logic to edit a chapter of the formation
        return view('clean.formateur.Formation.Chapter.ChapterEdit', compact('formation', 'chapter'));
    }
}
