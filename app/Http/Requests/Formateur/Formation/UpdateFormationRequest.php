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
            'money_amount' => 'nullable|numeric|min:0',
            'active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la formation est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description de la formation est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'money_amount.numeric' => 'Le prix doit être un nombre.',
            'money_amount.min' => 'Le prix ne peut pas être négatif.',
            'active.boolean' => 'Le statut de la formation doit être un booléen.',
        ];
    }
}
