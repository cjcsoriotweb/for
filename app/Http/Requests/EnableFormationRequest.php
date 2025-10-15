<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EnableFormationRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Vérifier que l'utilisateur est membre de l'équipe via les relations team->users
        return $this->route('team')
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
            'formation' => 'required|exists:formations,id',
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'formation.required' => 'Une formation doit être spécifiée.',
            'formation.exists' => 'Cette formation n\'existe pas.',
        ];
    }

    /**
     * Valider que la formation est accessible pour l'équipe.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $formation = \App\Models\Formation::find($this->input('formation'));
            $team = $this->route('team');

            if (!$formation || !$formation->teams()->where('teams.id', $team->id)->wherePivot('visible', true)->exists()) {
                $validator->errors()->add('formation', 'Cette formation n\'est pas accessible pour votre équipe.');
            }
        });
    }
}
