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
        // Get the first chapter or create one if none exists
        $chapter = $formation->chapters()->first();
        if (! $chapter) {
            $chapter = $formation->chapters()->create([
                'title' => 'Chapitre 1',
                'position' => 1,
            ]);
        }

        $lesson = $chapter->lessons()->create(array_merge([
            'title' => 'Nouvelle leçon',
            'position' => $chapter->lessons()->count() + 1,
        ], $attributes));

        return $lesson;
    }
}
