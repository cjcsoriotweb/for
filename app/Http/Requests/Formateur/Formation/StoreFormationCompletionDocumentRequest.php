<?php

namespace App\Http\Requests\Formateur\Formation;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormationCompletionDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240'], // 10 MB
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du document est obligatoire.',
            'file.required' => 'Veuillez choisir un fichier.',
            'file.max' => 'Le fichier ne doit pas depasser 10 Mo.',
        ];
    }
}
