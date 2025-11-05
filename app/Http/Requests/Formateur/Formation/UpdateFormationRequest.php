<?php

namespace App\Http\Requests\Formateur\Formation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFormationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the user is associated with the formation through their teams
        return Auth::user()->superadmin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'active' => 'nullable|boolean',
            'cover_image' => 'nullable|image|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la formation est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description de la formation est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'active.boolean' => 'Le statut de la formation doit être un booléen.',
            'cover_image.image' => 'Le fichier téléchargé doit être une image.',
            'cover_image.max' => 'L\'image ne doit pas dépasser 4 Mo.',
        ];
    }
}
