<?php

namespace App\Http\Requests\Admin\Configuration;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreditUpdate extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {


        $team = Team::findOrFail(request()->get('team_id'));

        if (Auth::user()->belongsToTeam($team) && Auth::user()->hasTeamRole($team, 'admin')) {
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
            'montant' => [
                'required',
                'numeric',
            ],
            'raison' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }


}