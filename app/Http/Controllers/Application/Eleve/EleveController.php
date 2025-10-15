<?php

namespace App\Http\Controllers\Application\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;



class EleveController extends Controller
{
    public function index(Team $team)
    {
        return view('application.eleve.index', compact('team'));
    }

    public function formationIndex(Team $team)
    {
        return view('application.eleve.formationsList', compact('team'));
    }

    public function formationPreview(Team $team, Formation $formation)
    {
        return view('application.eleve.formationPreview', compact('team', 'formation'));
    }

    public function formationContinue(Team $team, Formation $formation)
    {
        return view('application.eleve.formationContinue', compact('team', 'formation'));
    }

    public function formationShow(Team $team, Formation $formation)
    {
        // Vérifier que l'utilisateur peut accéder à cette formation
        if (!$formation->teams()->where('teams.id', $team->id)->wherePivot('visible', true)->exists()) {
            abort(403, 'Formation non accessible.');
        }

        return view('application.eleve.formationShow', compact('team', 'formation'));
    }

    public function formationEnable(Team $team, Formation $formation)
    {
        // Vérifier que l'utilisateur peut accéder à cette formation
        if (!$formation->teams()->where('teams.id', $team->id)->wherePivot('visible', true)->exists()) {
            abort(403, 'Formation non accessible.');
        }

        // Vérifier si déjà inscrit
        $user = Auth::id();
        if ($formation->learners()->where('users.id', $user)->exists()) {
            return redirect()->route('application.eleve.formations.continue', [$team, $formation])
                ->with('info', 'Vous êtes déjà inscrit à cette formation.');
        }

        // Inscrire l'utilisateur à la formation
        $formation->learners()->attach($user, [
            'status' => 'in_progress',
            'enrolled_at' => now(),
            'last_seen_at' => now(),
            'progress_percent' => 0,
            'current_lesson_id' => null,
        ]);

        return redirect()->route('application.eleve.formations.continue', [$team, $formation])
            ->with('success', "La formation '{$formation->title}' a été activée.");
    }

}
