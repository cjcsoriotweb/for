<?php

namespace App\Http\Requests\Formateur\Formation\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class DeleteChapter extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'chapter_id' => 'required|exists:chapters,id',
        ];
    }
}
