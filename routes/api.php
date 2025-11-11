<?php

use App\Http\Controllers\ToolController;
use App\Models\AiConversation;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/openapi.json', function () {
    $path = resource_path('openapi/openapi.json');

    if (! File::exists($path)) {
        return response()->json([
            'error' => true,
            'message' => 'La spécification OpenAPI est introuvable.',
        ], 404);
    }

    $spec = json_decode(File::get($path), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json([
            'error' => true,
            'message' => 'Spécification OpenAPI invalide: ' . json_last_error_msg(),
        ], 500);
    }

    return response()->json($spec);
});

Route::prefix('tools')->group(function () {
    Route::get('weather', [ToolController::class, 'weather']);
    Route::post('echo', [ToolController::class, 'echo']);
});

Route::get('/users', function () {
    return User::query()
        ->select(['id', 'name', 'email'])
        ->orderBy('id')
        ->get();
});

Route::get('/ai/trainers', function () {
    $trainers = AiTrainer::query()
        ->active()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->map(function (AiTrainer $trainer) {
            return [
                'slug' => $trainer->slug,
                'name' => $trainer->name,
                'description' => $trainer->description,
                'model' => $trainer->model,
                'temperature' => $trainer->temperature,
                'use_tools' => (bool) $trainer->use_tools,
                'show_everywhere' => (bool) $trainer->show_everywhere,
                'prompt_preview' => Str::limit($trainer->systemPrompt(), 240),
                'capabilities' => array_values(array_filter([
                    $trainer->use_tools ? 'Peut déclencher des outils métier' : null,
                    $trainer->show_everywhere ? 'Disponible dans toutes les sections' : null,
                ])),
            ];
        });

    return response()->json([
        'trainers' => $trainers->values(),
        'retrieved_at' => now()->toIso8601String(),
    ]);
});

Route::get('/ai/insights', function () {
    $totals = [
        'users' => User::count(),
        'formations' => Formation::count(),
        'lessons' => Lesson::count(),
        'active_conversations' => AiConversation::query()
            ->where('status', AiConversation::STATUS_ACTIVE)
            ->count(),
    ];

    $topFormations = Formation::query()
        ->withCount('learners')
        ->orderByDesc('learners_count')
        ->limit(3)
        ->get(['id', 'title', 'level', 'active', 'updated_at'])
        ->map(function (Formation $formation) {
            return [
                'id' => $formation->id,
                'title' => $formation->title,
                'level' => $formation->level,
                'active' => (bool) $formation->active,
                'learner_count' => (int) $formation->learners_count,
                'last_update' => optional($formation->updated_at)->toIso8601String(),
            ];
        });

    $latestFormationModels = Formation::query()
        ->latest()
        ->limit(3)
        ->get();

    $latestFormations = $latestFormationModels->map(function (Formation $formation) {
        return [
            'id' => $formation->id,
            'title' => $formation->title,
            'level' => $formation->level,
            'summary' => Str::limit(strip_tags((string) $formation->description), 160),
            'created_at' => optional($formation->created_at)->toIso8601String(),
        ];
    });

    $recentConversations = AiConversation::query()
        ->with('user:id,name,email')
        ->orderByDesc('last_message_at')
        ->orderByDesc('updated_at')
        ->limit(5)
        ->get()
        ->map(function (AiConversation $conversation) {
            $lastActivity = $conversation->last_message_at
                ?? $conversation->updated_at
                ?? $conversation->created_at;

            return [
                'id' => $conversation->id,
                'status' => $conversation->status,
                'trainer' => data_get($conversation->metadata, 'trainer'),
                'last_message_at' => optional($lastActivity)->toIso8601String(),
                'user' => $conversation->user ? [
                    'id' => $conversation->user->id,
                    'name' => $conversation->user->name,
                    'email' => $conversation->user->email,
                ] : null,
            ];
        });

    $promptIdeas = $latestFormationModels->map(function (Formation $formation) {
        return sprintf(
            'Explique-moi comment appliquer "%s" dans un contexte professionnel.',
            $formation->title
        );
    });

    return response()->json([
        'totals' => $totals,
        'top_formations' => $topFormations->values(),
        'latest_formations' => $latestFormations->values(),
        'recent_conversations' => $recentConversations->values(),
        'recommended_prompts' => $promptIdeas->values(),
        'generated_at' => now()->toIso8601String(),
    ]);
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

