<?php

use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', function () {
    return User::query()
        ->select(['id', 'name', 'email'])
        ->orderBy('id')
        ->get();
});

Route::get('/formations', function () {
    return Formation::get();
});

Route::get('/formations/featured', function () {
    $formations = Formation::query()
        ->with(['category:id,name'])
        ->withCount('learners')
        ->where('active', true)
        ->orderByDesc('updated_at')
        ->limit(6)
        ->get()
        ->map(function (Formation $formation) {
            return [
                'id' => $formation->id,
                'title' => $formation->title,
                'description' => Str::limit(strip_tags((string) $formation->description), 200),
                'level' => $formation->level,
                'category' => $formation->category?->name,
                'cover_image_url' => $formation->cover_image_url,
                'learner_count' => (int) $formation->learners_count,
            ];
        });

    return response()->json([
        'featured' => $formations->values(),
        'updated_at' => now()->toIso8601String(),
    ]);
});

Route::get('/formations/{formation}/outline', function (Formation $formation) {
    $formation->load([
        'category:id,name',
        'chapters.lessons',
    ]);

    $outline = $formation->chapters->map(function ($chapter) {
        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'position' => $chapter->position,
            'lessons' => $chapter->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->getName(),
                    'position' => $lesson->position,
                ];
            })->values()->all(),
        ];
    })->values();

    $lessonCount = $outline->sum(function (array $chapter) {
        return count($chapter['lessons']);
    });

    return response()->json([
        'formation' => [
            'id' => $formation->id,
            'title' => $formation->title,
            'level' => $formation->level,
            'category' => $formation->category?->name,
            'chapter_count' => $outline->count(),
            'lesson_count' => $lessonCount,
        ],
        'outline' => $outline,
        'refreshed_at' => now()->toIso8601String(),
    ]);
});
