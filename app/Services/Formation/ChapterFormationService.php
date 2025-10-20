<?php

namespace App\Services\Formation;

use App\Models\Formation;

class ChapterFormationService extends BaseFormationService
{
    /**
     * Create a formation with default values suitable for a super admin.
     */
    public function deleteChapter($chapter)
    {
        $chapter->delete();
    }

    public function createChapter(Formation $formation, array $attributes = [])
    {
        $payload = array_replace([
            'title' => 'Nouveau Chapitre',
            'position' => $formation->chapters()->count() + 1,
        ], $attributes);

        return $formation->chapters()->create($payload);
    }

    public function updateChapter($chapter, array $attributes = [])
    {
        $chapter->update($attributes);

        return $chapter;
    }
}
