<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formation;
use App\Models\formation_team;
use App\Models\Lesson;
use App\Models\Team;

class FormationsController extends Controller
{
    /**
     * Vérifie que l'utilisateur est bien dans la team courante
     * et que la formation est disponible pour cette team.
     * Retourne l'ID de la team courante.
     */
    protected function assertFormationInCurrentTeam(Formation $formation): int
    {
        $user = auth()->user();
        $teamId = (int) ($user->current_team_id ?? 0);
        abort_if($teamId === 0, 403, 'Vous ne faites pas partie de cette équipe.');

        formation_team::query()
            ->where('formation_id', $formation->id)
            ->where('team_id', $teamId)
            ->firstOr(fn() => abort(403, 'Cette formation n\'est pas disponible pour votre équipe.'));

 
        return $teamId;
    }

    public function startFormation(Request $request, Team $team, Formation $formation)
    {
        $teamId = $this->assertFormationInCurrentTeam($formation);

        // Première leçon : tri par chapitre.position puis leçon.position
        $firstLesson = Lesson::query()
            ->select('lessons.*')
            ->join('chapters', 'chapters.id', '=', 'lessons.chapter_id')
            ->where('chapters.formation_id', $formation->id)
            ->orderBy('chapters.position')
            ->orderBy('lessons.position')
            ->first();

        abort_if(! $firstLesson, 404, 'No lessons found for this formation.');

        // Redirection vers la page de la leçon (adapte le nom de route si besoin)
        return redirect()->route('lessons.show', [
            'team'      => $teamId,
            'formation' => $formation->id,
            'lesson'    => $firstLesson->id,
        ]);
    }
}
