<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Formation;
use App\Models\Team;

class EnableFormationRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Vérifier que l'utilisateur est membre de l'équipe via les relations team->users
        return \request()->route('team')
            ->users()
            ->where('users.id', Auth::id())
            ->exists();
    }

    /**
     * Définit les règles de validation pour cette requête.
     */
    public function rules(): array
    {
        return [
            'formation' => ['required', 'integer', 'exists:formations,id'],
        ];
    }

    /**
     * Valider que la formation est accessible pour l'équipe et que les fonds sont suffisants.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            /** @var Formation|null $formation */
            $formation = Formation::query()->find(\request()->input('formation'));
            /** @var Team $team */
            $team = \request()->route('team');

            if (!$formation || !$formation->teams()->where('teams.id', $team->id)->wherePivot('visible', true)->exists()) {
                $validator->errors()->add('formation', 'Cette formation n\'est pas accessible pour votre équipe.');
            }

            if ($formation && !$this->canTeamAffordFormation($team, $formation)) {
                $validator->errors()->add('formation', "Les fonds de l'équipe sont insuffisants pour commencer cette formation (requis : {$formation->money_amount}€).");
            }
        });
    }

    /**
     * Vérifie si l'équipe a les fonds nécessaires pour commencer une formation
     */
    private function canTeamAffordFormation(Team $team, Formation $formation): bool
    {
        return $team->money >= $formation->money_amount;
    }
}
