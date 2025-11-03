<?php

namespace App\Http\Requests\Formateur\Formation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFormationDescriptionRequest extends FormRequest
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
            'description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'La description de la formation est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
        ];
    }
}
