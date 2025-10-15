<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) \Illuminate\Support\Facades\Auth::user()?->superadmin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'chapter_id' => ['required', 'integer', 'exists:chapters,id'],
            'title' => ['required', 'string', 'max:255'],
            'content_type' => ['required', 'in:text,video,quiz'],
            'order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
