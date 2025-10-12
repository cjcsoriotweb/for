<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Formation;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class FormationsController extends Controller
{

    public function startFormation(Request $request, Team $team, Formation $formation)
    {
        // Vérifier que l'utilisateur appartient à l'équipe
        if ($request->user()->current_team_id !== $team->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Vérifier que la formation appartient à l'équipe
        if ($formation->team_id !== $team->id) {
            abort(404, 'Formation not found for this team.');
        }

        // Récupérer la première leçon de la formation
        $firstLesson = $formation->lessons()->orderBy('order')->first();

        if (!$firstLesson) {
            abort(404, 'No lessons found for this formation.');
        }

        // Rediriger vers la première leçon de la formation
        return redirect()->route('lessons.show', ['team' => $team->id, 'formation' => $formation->id, 'lesson' => $firstLesson->id]);
       
    }
}
