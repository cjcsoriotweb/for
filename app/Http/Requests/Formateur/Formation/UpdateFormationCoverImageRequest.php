<?php

namespace App\Http\Requests\Formateur\Formation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFormationCoverImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'cover_image' => 'required|image|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'cover_image.required' => 'Une image de couverture est requise.',
            'cover_image.image' => 'Le fichier téléchargé doit être une image.',
            'cover_image.max' => 'L\'image ne doit pas dépasser 4 Mo.',
        ];
    }
}
