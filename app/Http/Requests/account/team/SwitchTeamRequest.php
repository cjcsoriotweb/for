<?php

namespace App\Http\Requests\account\team;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SwitchTeamRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {

        return Auth::user()->hasTeamRole($this->route('team'), 'admin');
  
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


}