<?php

namespace App\Http\Requests\Admin\Formations;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class FormationCreateNameByTeam extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {


        if (Auth::user()->superadmin()) {
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
            'formation.title' => [
                'required'
            ],
            'formation.description' => [
                'required',
            ],
        ];
    }
}