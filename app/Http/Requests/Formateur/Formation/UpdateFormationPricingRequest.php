<?php

namespace App\Http\Requests\Formateur\Formation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFormationPricingRequest extends FormRequest
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
            'money_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'money_amount.numeric' => 'Le prix doit être un nombre.',
            'money_amount.min' => 'Le prix ne peut pas être négatif.',
        ];
    }
}
