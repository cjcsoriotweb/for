<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formateur\Formation\UpdateFormationCoverImageRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationDescriptionRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationTitleRequest;
use App\Models\Formation;
use App\Models\Quiz;
use App\Models\TextContent;
use App\Models\VideoContent;
use App\Services\FormationService;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormateurFormationController extends Controller
{
    public function showFormation(Formation $formation)
    {
        return view('out-application.formateur.formation.formation-show', compact('formation'));
    }

    public function previewFormation(Formation $formation)
    {
        $formation->loadMissing([
            'chapters.lessons' => fn ($query) => $query->orderBy('position'),
            'chapters.lessons.lessonable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    VideoContent::class => [],
                    TextContent::class => [],
                    Quiz::class => ['quizQuestions'],
                ]);
            },
        ]);

        $chapters = $formation->chapters;
        $lessonCount = $chapters->sum(fn ($chapter) => $chapter->lessons->count());

        $lessons = $chapters->flatMap(fn ($chapter) => $chapter->lessons);
        $totalDurationMinutes = $lessons->sum(function ($lesson) {
            if (! $lesson->lessonable) {
                return 0;
            }

            return match ($lesson->lessonable_type) {
                VideoContent::class => (int) ($lesson->lessonable->duration_minutes ?? 0),
                TextContent::class => (int) ($lesson->lessonable->estimated_read_time ?? 0),
                Quiz::class => (function () use ($lesson) {
                    $estimated = (int) ($lesson->lessonable->estimated_duration_minutes ?? 0);
                    if ($estimated > 0) {
                        return $estimated;
                    }

                    $questionCount = $lesson->lessonable->quizQuestions?->count()
                        ?? $lesson->lessonable->quizQuestions()->count();

                    return $questionCount > 0 ? max($questionCount * 2, 5) : 0;
                })(),
                default => 0,
            };
        });

        $durationHours = intdiv($totalDurationMinutes, 60);
        $durationMinutesRemainder = $totalDurationMinutes % 60;

        $formattedEstimatedDuration = $totalDurationMinutes > 0
            ? implode(' ', array_filter([
                $durationHours > 0 ? $durationHours . ' h' : null,
                $durationMinutesRemainder > 0 ? $durationMinutesRemainder . ' min' : null,
            ]))
            : null;

        return view('out-application.formateur.formation.formation-preview', [
            'formation' => $formation,
            'lessonCount' => $lessonCount,
            'totalDurationMinutes' => $totalDurationMinutes,
            'formattedEstimatedDuration' => $formattedEstimatedDuration,
        ]);
    }

    public function showFormationTeams(Formation $formation)
    {
        $formation->loadMissing(['teams:id,name']);

        return view('out-application.formateur.formation.formation-teams', compact('formation'));
    }

    public function editFormation(Formation $formation)
    {
        return view('out-application.formateur.formation.formation-edit', compact('formation'));
    }

    public function editFormationTitle(Formation $formation)
    {
        return view('out-application.formateur.formation.edit.title', compact('formation'));
    }

    public function updateFormationTitle(UpdateFormationTitleRequest $request, Formation $formation)
    {
        $formation->update([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Titre mis à jour avec succès.');
    }

    public function editFormationDescription(Formation $formation)
    {
        return view('out-application.formateur.formation.edit.description', compact('formation'));
    }

    public function updateFormationDescription(UpdateFormationDescriptionRequest $request, Formation $formation)
    {
        $formation->update([
            'description' => $request->description,
        ]);

        return back()->with('success', 'Description mise à jour avec succès.');
    }

    public function editFormationCoverImage(Formation $formation)
    {
        return view('out-application.formateur.formation.edit.cover-image', compact('formation'));
    }

    public function updateFormationCoverImage(UpdateFormationCoverImageRequest $request, Formation $formation)
    {
        if ($formation->cover_image_path && Storage::disk('public')->exists($formation->cover_image_path)) {
            Storage::disk('public')->delete($formation->cover_image_path);
        }

        $formation->update([
            'cover_image_path' => $request->file('cover_image')->store('formations/covers', 'public'),
        ]);

        return back()->with('success', 'Image de couverture mise à jour avec succès.');
    }

    public function editPricing(Formation $formation)
    {
        $formation->load([
            'chapters' => fn ($query) => $query->orderBy('position'),
            'chapters.lessons' => fn ($query) => $query->orderBy('position'),
            'chapters.lessons.lessonable',
            'entryQuiz',
        ]);

        $lessons = $formation->chapters
            ->flatMap(fn ($chapter) => $chapter->lessons)
            ->filter(fn ($lesson) => $lesson->lessonable_type !== null)
            ->values();

        $groupedLessons = $lessons->groupBy('lessonable_type');

        $lessonGroups = [
            'quizzes' => $groupedLessons->get(Quiz::class, collect()),
            'videos' => $groupedLessons->get(VideoContent::class, collect()),
            'texts' => $groupedLessons->get(TextContent::class, collect()),
        ];

        return view('out-application.formateur.formation.formation-pricing', [
            'formation' => $formation,
            'lessonGroups' => $lessonGroups,
        ]);
    }

    public function manageChapters(Formation $formation)
    {
        $formation->load([
            'chapters.lessons' => fn ($query) => $query->orderBy('position'),
            'chapters.lessons.resources',
            'chapters.lessons.lessonable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    TextContent::class => ['attachments'],
                    VideoContent::class => [],
                    Quiz::class => [],
                ]);
            },
        ]);

        return view('out-application.formateur.formation.formation-chapters', compact('formation'));
    }

    // editAi and updateAi methods removed - trainers are now managed in config/ai.php

    public function updateFormation(UpdateFormationRequest $request, Formation $formation)
    {
        $updatePayload = [
            'title' => $request->title,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : $formation->active,
        ];

        if ($request->hasFile('cover_image')) {
            if ($formation->cover_image_path && Storage::disk('public')->exists($formation->cover_image_path)) {
                Storage::disk('public')->delete($formation->cover_image_path);
            }

            $updatePayload['cover_image_path'] = $request->file('cover_image')->store('formations/covers', 'public');
        }

        $formation->update($updatePayload);

        return back()->with('success', 'Formation mise à jour avec succès.');
    }

    public function toggleStatus(Formation $formation)
    {
        $formation->update([
            'active' => ! $formation->active,
        ]);

        $status = $formation->active ? 'activée' : 'désactivée';

        return response()->json([
            'success' => true,
            'message' => "Formation {$status} avec succès.",
            'active' => $formation->active,
        ]);
    }

    public function createFormation(FormationService $formationService)
    {
        $formation = $formationService->createFormation();

        return redirect()->route('formateur.formation.show', [$formation]);
    }

    public function deleteFormation(Formation $formation)
    {
        $confirmationCode = rand(1, 6000);

        session(['formation_delete_code' => $confirmationCode]);

        return view('out-application.formateur.formation.formation-delete', compact('formation', 'confirmationCode'));
    }

    public function destroyFormation(Request $request, Formation $formation)
    {
        $request->validate([
            'confirmation_code' => 'required|integer|min:1|max:6000',
        ]);

        $expectedCode = session('formation_delete_code');

        if ((int) $request->confirmation_code !== $expectedCode) {
            return back()->withErrors(['confirmation_code' => 'Le code de confirmation est incorrect.']);
        }

        // Supprimer l'image de couverture si elle existe
        if ($formation->cover_image_path && Storage::disk('public')->exists($formation->cover_image_path)) {
            Storage::disk('public')->delete($formation->cover_image_path);
        }

        // Supprimer la formation (les relations seront supprimées en cascade grâce aux foreign keys)
        $formation->delete();

        // Nettoyer la session
        session()->forget('formation_delete_code');

        return redirect()->route('formateur.home')->with('success', 'Formation supprimée avec succès.');
    }
}
