<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Requests\Formateur\Formation\Chapter\DeleteChapter;
use App\Http\Requests\Formateur\Formation\Chapter\UpdateChapter;
use App\Models\Chapter;
use App\Models\Formation;
use App\Services\FormationService;

class FormationChapterController
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
        return redirect()->route('formateur.formation.edit', [$formation])->with('success', 'Chapitre mis à jour avec succès.');
    }

    public function deleteChapter(DeleteChapter $request, Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        //
        $validated = $request->validated();
        $formationService->chapters()->deleteChapter($chapter);
        return redirect()->route('formateur.formation.edit', [$formation])->with('success', 'Chapitre supprimé avec succès.');
    }

    public function editChapter(Formation $formation, Chapter $chapter)
    {
        // Logic to edit a chapter of the formation
        return view('clean.formateur.Formation.Chapter.ChapterEdit', compact('formation', 'chapter'));
    }
}
