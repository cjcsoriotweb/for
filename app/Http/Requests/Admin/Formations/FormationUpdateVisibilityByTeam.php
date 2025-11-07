<?php

namespace App\Http\Requests\Admin\Formations;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FormationUpdateVisibilityByTeam extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {

        $team = Team::findOrFail(request()->get('team_id'));

        if (Auth::user()->belongsToTeam($team) && Auth::user()->hasTeamRole($team, 'admin') || Auth::user()->superadmin) {
            return true;
        }

        return false;

    }

    /**
     * Définit les règles de validation pour cette requête.
     */
    public function rules(): array
    {
        return [
            'team_id' => [
                'required', 'exists:teams,id',
            ],
            'formation_id' => [
                'required',
                'exists:formations,id',
            ],

            'enabled' => [
                'required', 'boolean',
            ],
            'usage_quota' => [
                Rule::requiredIf(fn () => (bool) $this->boolean('enabled')),
                'integer',
                'min:1',
            ],
        ];
    }
}
