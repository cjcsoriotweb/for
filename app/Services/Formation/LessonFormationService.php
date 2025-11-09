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
            'title' => 'Nouvelle leÃ§on',
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

    public function moveLessonUp(Formation $formation, Chapter $chapter, \App\Models\Lesson $lesson): void
    {
        $chapter->refresh();
        $lessons = $chapter->lessons()->orderBy('position')->get();
        if ($lessons->isEmpty()) { return; }

        if ($lesson->position > 1) {
            $swap = $chapter->lessons()->where('position', $lesson->position - 1)->first();
            if ($swap) {
                $swap->update(['position' => $lesson->position]);
                $lesson->update(['position' => $lesson->position - 1]);
            }
            return;
        }

        // First in chapter: move to previous chapter if exists
        $prevChapter = $formation->chapters()->where('position', '<', $chapter->position)->orderByDesc('position')->first();
        if ($prevChapter) {
            // Resequence previous to ensure contiguous
            $this->resequenceChapter($prevChapter);
            $maxPos = (int) $prevChapter->lessons()->max('position');
            $lesson->update(['chapter_id' => $prevChapter->id, 'position' => $maxPos + 1]);
            $this->resequenceChapter($chapter);
            return;
        }
        // No previous chapter: nothing to do
    }

    public function moveLessonDown(Formation $formation, Chapter $chapter, \App\Models\Lesson $lesson): void
    {
        $chapter->refresh();
        $count = (int) $chapter->lessons()->count();
        if ($count === 0) { return; }

        if ($lesson->position < $count) {
            $swap = $chapter->lessons()->where('position', $lesson->position + 1)->first();
            if ($swap) {
                $swap->update(['position' => $lesson->position]);
                $lesson->update(['position' => $lesson->position + 1]);
            }
            return;
        }

        // Last in chapter: move to next chapter if exists
        $nextChapter = $formation->chapters()->where('position', '>', $chapter->position)->orderBy('position')->first();
        if ($nextChapter) {
            $this->resequenceChapter($nextChapter);
            // Insert as first in next chapter
            // Shift existing lessons down
            $nextLessons = $nextChapter->lessons()->orderBy('position')->get();
            foreach ($nextLessons as $nl) {
                $nl->update(['position' => $nl->position + 1]);
            }
            $lesson->update(['chapter_id' => $nextChapter->id, 'position' => 1]);
            $this->resequenceChapter($chapter);
            return;
        }
        // No next chapter: nothing to do
    }

    private function resequenceChapter(Chapter $chapter): void
    {
        $i = 1;
        foreach ($chapter->lessons()->orderBy('position')->get() as $l) {
            if ((int)$l->position !== $i) {
                $l->update(['position' => $i]);
            }
            $i++;
        }
    }
}

