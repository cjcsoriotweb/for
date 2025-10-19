<?php

namespace App\Http\Controllers\Clean\Formateur\Formation\Chapter;

use App\Http\Requests\Formateur\Formation\Chapter\UpdateChapter;
use App\Models\Chapter;
use App\Models\Formation;
use App\Services\FormationService;

class FormateurFormationChapterController
{
    public function createChapter(Formation $formation, FormationService $formationService)
    {
        $formationService->chapters()->createChapter($formation);
        return back()->with('success', 'Chapitre créé avec succès.');
    }

    public function updateChapter(UpdateChapter $request, Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        //
        $validated = $request->validated();
        $formationService->chapters()->updateChapter($chapter, $validated);
        return back()->with('success', 'Chapitre mis à jour avec succès.');
    }
}
