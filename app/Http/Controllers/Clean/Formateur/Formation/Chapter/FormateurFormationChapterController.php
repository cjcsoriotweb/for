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
        $chapter = $formationService->chapters()->createChapter($formation);
        return redirect()->route('formateur.formation.chapter.edit', [$formation, $chapter])->with('success', 'Chapitre créé avec succès.');
    }

    public function updateChapter(UpdateChapter $request, Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        //
        $validated = $request->validated();
        $formationService->chapters()->updateChapter($chapter, $validated);
        return back()->with('success', 'Chapitre mis à jour avec succès.');
    }
}
