<?php

namespace App\Http\Requests\Formateur\Formation\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChapter extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'position' => 'integer',
        ];
    }
}
