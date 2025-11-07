<?php

namespace App\Http\Requests\Admin\Configuration;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class PhotoUpdate extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {

        $team = Team::findOrFail(request()->get('team_id'));

        if (Auth::user()->superadmin) {
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
            'photo' => [
                'required',
                File::image()->max(2 * 1024), // 2 MB
            ],
        ];
    }
}
