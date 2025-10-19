<?php

namespace App\Services\Formation;

use App\Models\Formation;

class LessonFormationService extends BaseFormationService
{
    /**
     * Create a formation with default values suitable for a super admin.
     */

    public function createLesson(Formation $formation, array $attributes = [])
    {
        $lesson = $formation->lessons()->create(array_merge([
            'title' => 'New Lesson',
            'chapter_id' => '',
            'content' => '',
        ], $attributes));

        return $lesson;
    }
}
