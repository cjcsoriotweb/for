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
        return true;
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
            'formation_id' => ['required', 'integer', 'exists:formations,id'],
        ];
    }

    /**
     * Valider que la formation est accessible pour l'équipe et que les fonds sont suffisa

     * Vérifie si l'équipe a les fonds nécessaires pour commencer une formation
     */
    private function canTeamAffordFormation(Team $team, Formation $formation): bool
    {
        return $team->money >= $formation->money_amount;
    }
}
