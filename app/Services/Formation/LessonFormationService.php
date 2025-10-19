<?php

namespace App\Services\Formation;

use App\Models\Chapter;
use App\Models\Formation;

class LessonFormationService extends BaseFormationService
{
    /**
     * Create a formation with default values suitable for a super admin.
     */
    public function createLesson(Formation $formation, Chapter $chapter, array $attributes = [])
    {

        $lesson = $chapter->lessons()->create(array_merge([
            'title' => 'Nouvelle leçon',
            'position' => $chapter->lessons()->count() + 1,
        ], $attributes));

        return $lesson;
    }

    /**
     * Delete a lesson.
     */
    public function deleteLesson($lesson)
    {
        $lesson->delete();
    }
}
