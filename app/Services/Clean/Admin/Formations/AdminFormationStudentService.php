<?php

namespace App\Services\Clean\Admin\Formations;

use App\Models\Formation;
use App\Models\FormationUser;
use App\Models\Lesson;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminFormationStudentService
{
    public function getEnrollment(Formation $formation, Team $team, User $student): FormationUser
    {
        return FormationUser::query()
            ->where('formation_id', $formation->id)
            ->where('team_id', $team->id)
            ->where('user_id', $student->id)
            ->firstOrFail();
    }

    public function completeLesson(Formation $formation, Team $team, User $student, Lesson $lesson): void
    {
        if ($lesson->chapter?->formation_id !== $formation->id) {
            abort(404);
        }

        $this->getEnrollment($formation, $team, $student);

        DB::transaction(function () use ($formation, $student, $lesson): void {
            $existingPivot = $lesson->learners()
                ->where('user_id', $student->id)
                ->first()?->pivot;

            $payload = [
                'status' => 'completed',
                'last_activity_at' => now(),
                'completed_at' => now(),
            ];

            if (! $existingPivot) {
                $lesson->learners()->attach($student->id, array_merge($payload, [
                    'watched_seconds' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                    'attempts' => 0,
                    'read_percent' => 100,
                    'started_at' => now(),
                ]));
            } else {
                if (! $existingPivot->started_at) {
                    $payload['started_at'] = now();
                }

                if (($existingPivot->read_percent ?? 0) < 100) {
                    $payload['read_percent'] = 100;
                }

                $lesson->learners()->updateExistingPivot($student->id, $payload);
            }

            $this->recalculateFormationProgress($formation, $student);
        });
    }

    public function resetProgress(Formation $formation, Team $team, User $student): void
    {
        $this->getEnrollment($formation, $team, $student);

        DB::transaction(function () use ($formation, $student): void {
            $lessonIds = $formation->lessons()->pluck('lessons.id');

            if ($lessonIds->isNotEmpty()) {
                DB::table('lesson_user')
                    ->where('user_id', $student->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->delete();
            }

            $formation->learners()->updateExistingPivot($student->id, [
                'status' => 'enrolled',
                'current_lesson_id' => $this->firstLessonId($formation),
                'last_seen_at' => now(),
                'completed_at' => null,
                'score_total' => null,
                'max_score_total' => null,
            ]);
        });
    }

    public function unenrollStudent(Formation $formation, Team $team, User $student, bool $refund = true): int
    {
        $enrollment = $this->getEnrollment($formation, $team, $student);

        return DB::transaction(function () use ($formation, $team, $student, $enrollment, $refund): int {
            $lessonIds = $formation->lessons()->pluck('lessons.id');

            if ($lessonIds->isNotEmpty()) {
                DB::table('lesson_user')
                    ->where('user_id', $student->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->delete();
            }

            $formation->learners()->detach($student->id);

            $refundAmount = (int) ($enrollment->enrollment_cost ?? $formation->money_amount ?? 0);

            if ($refund && $refundAmount > 0) {
                $team->increment('money', $refundAmount);
            }

            return $refund ? $refundAmount : 0;
        });
    }

    private function recalculateFormationProgress(Formation $formation, User $student): void
    {
        $chapters = $formation->chapters()
            ->with(['lessons' => fn ($query) => $query->orderBy('position')])
            ->orderBy('position')
            ->get();

        $lessonIds = $chapters->pluck('lessons')->flatten()->pluck('id');

        $lessonStatuses = collect();
        if ($lessonIds->isNotEmpty()) {
            $lessonStatuses = DB::table('lesson_user')
                ->where('user_id', $student->id)
                ->whereIn('lesson_id', $lessonIds)
                ->pluck('status', 'lesson_id');
        }

        $totalLessons = $lessonIds->count();
        $completedLessons = $lessonStatuses->filter(fn ($status) => $status === 'completed')->count();

        $progressPercent = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        $nextLessonId = null;
        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                if (($lessonStatuses[$lesson->id] ?? null) !== 'completed') {
                    $nextLessonId = $lesson->id;
                    break 2;
                }
            }
        }

        $pivot = $formation->learners()
            ->where('users.id', $student->id)
            ->first()?->pivot;

        $updatePayload = [
            'last_seen_at' => now(),
            'current_lesson_id' => $nextLessonId,
            'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            'completed_at' => $progressPercent >= 100
                ? ($pivot?->completed_at ?? now())
                : null,
        ];

        $formation->learners()->syncWithoutDetaching([
            $student->id => $updatePayload,
        ]);
    }

    private function firstLessonId(Formation $formation): ?int
    {
        return $formation->chapters()
            ->orderBy('position')
            ->first()
            ?->lessons()
            ->orderBy('position')
            ->first()
            ?->id;
    }
}
