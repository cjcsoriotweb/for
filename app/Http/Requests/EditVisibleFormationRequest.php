<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Formation;
use App\Models\Team;

class EditVisibleFormationRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {

        return Auth::user()->hasTeamRole($this->route('team'), 'admin');
        // Vérifier que l'utilisateur est membre de l'équipe via les relations team->users
  
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
