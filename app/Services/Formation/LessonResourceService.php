<?php

namespace App\Services\Formation;

use App\Models\Formation;
use App\Models\TextContentAttachment;
use App\Models\User;
use Illuminate\Support\Collection;

class LessonResourceService
{
    /**
     * Get lesson resources with attachments for a formation
     *
     * @param Formation $formation The formation to get resources for
     * @param User|null $user Optional user to check lesson completion status
     * @param bool $includeProgressStatus Whether to include progress status (requires $user)
     * @return Collection Collection of lesson resources with attachments
     */
    public function getLessonResourcesForFormation(
        Formation $formation,
        ?User $user = null,
        bool $includeProgressStatus = false
    ): Collection {
        $query = TextContentAttachment::query()
            ->whereHas('textContent.lessonable', function ($lessonQuery) use ($formation) {
                $lessonQuery->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with(['textContent', 'textContent.lessonable.chapter']);

        // If we need progress status, eager load the learner relationship
        if ($includeProgressStatus && $user) {
            $query->with([
                'textContent.lessonable.learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                },
            ]);
        }

        $lessonAttachments = $query->get();

        return $lessonAttachments
            ->groupBy('text_content_id')
            ->map(function ($attachments) use ($includeProgressStatus, $user) {
                /** @var \Illuminate\Support\Collection<int, TextContentAttachment> $attachments */
                $firstAttachment = $attachments->first();
                $textContent = $firstAttachment?->textContent;
                $lesson = $textContent?->lessonable;

                if (! $lesson) {
                    return null;
                }

                $resource = [
                    'chapter_title' => $lesson->chapter?->title,
                    'chapter_position' => $lesson->chapter?->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'attachments' => $attachments,
                ];

                // Add progress status if requested
                if ($includeProgressStatus && $user) {
                    $lessonLearner = $lesson->learners->first();
                    $lessonStatus = optional($lessonLearner?->pivot)->status;
                    $isCompleted = $lessonStatus === 'completed';
                    $isInProgress = $lessonStatus === 'in_progress';

                    $resource['is_completed'] = $isCompleted;
                    $resource['is_in_progress'] = $isInProgress;
                    $resource['can_download_resources'] = $isCompleted || $isInProgress;
                } else {
                    // Default to completed if no progress tracking
                    $resource['is_completed'] = true;
                }

                return $resource;
            })
            ->filter()
            ->sortBy([
                fn ($item) => $item['chapter_position'],
                fn ($item) => $item['lesson_position'],
            ])
            ->values();
    }
}
