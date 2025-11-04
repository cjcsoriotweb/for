<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Models\User;
use App\Services\Ai\OllamaClient;
use App\Services\Ai\ToolExecutor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use RuntimeException;

/**
 * Contrôleur unique pour les interactions IA.
 * Gère le streaming NDJSON pour les réponses progressives.
 */
class AiController extends Controller
{
    public function __construct(
        private readonly OllamaClient $ollamaClient,
        private readonly ToolExecutor $toolExecutor
    ) {}

    /**
     * Endpoint unique pour le streaming IA.
     * Retourne un flux NDJSON (Newline Delimited JSON).
     *
     * @throws ValidationException
     */
    public function stream(Request $request): Response|StreamedResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string', 'max:' . config('ai.max_message_length', 2000)],
            'trainer' => ['nullable', 'string'],
            'conversation_id' => ['required', 'integer', 'exists:ai_conversations,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        if (! $user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentification requise',
            ], 401);
        }

        $message = trim($request->input('message'));
        $conversationId = $request->input('conversation_id');

        $conversationQuery = AiConversation::query()
            ->where('id', $conversationId);

        if (! $user->superadmin()) {
            $conversationQuery->where('user_id', $user->id);
        }

        $conversation = $conversationQuery->first();

        if (! $conversation) {
            return response()->json([
                'error' => true,
                'message' => "Conversation non trouvee. Veuillez creer une conversation d'abord.",
            ], 404);
        }

        $trainerSlug = $request->input('trainer', $conversation->metadata['trainer'] ?? config('ai.default_trainer_slug'));
        $trainerModel = AiTrainer::query()->active()->where('slug', $trainerSlug)->first();

        if (! $trainerModel) {
            $trainerModel = AiTrainer::query()->active()->orderBy('sort_order')->orderBy('name')->first();
        }

        if (! $trainerModel) {
            return response()->json([
                'error' => true,
                'message' => 'Aucun assistant disponible.',
            ], 404);
        }

        $trainer = $this->formatTrainer($trainerModel);

        $conversation->forceFill([
            'metadata' => array_merge($conversation->metadata ?? [], [
                'trainer' => $trainer['slug'],
                'model' => $trainer['model'],
            ]),
        ])->save();

        $messageAuthorId = $user->id;

        if ($user->superadmin() && $request->filled('acting_user_id')) {
            $actingUserId = (int) $request->input('acting_user_id');
            $actingUser = User::query()->find($actingUserId);

            if ($actingUser) {
                $messageAuthorId = $actingUser->id;
            }
        }

        $conversation->messages()->create([
            'role' => AiConversationMessage::ROLE_USER,
            'content' => $message,
            'user_id' => $messageAuthorId,
        ]);

        $messages = $this->prepareMessages($conversation, $trainer);

        return response()->stream(function () use ($conversation, $messages, $trainer, $user) {
            $fullResponse = '';

            $conversationData = json_encode([
                'type' => 'conversation_id',
                'conversation_id' => $conversation->id,
            ]);
            echo "data: {$conversationData}

";

            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();

            try {
                $handle = $this->ollamaClient->chatStreamRaw($messages, $trainer);

                if ($handle === false) {
                    throw new RuntimeException('Impossible d\'initialiser le streaming Ollama.');
                }

                $buffer = '';
                $streamCompleted = false;

                $emitChunk = function (string $chunk) use (&$fullResponse): void {
                    if ($chunk === '') {
                        return;
                    }

                    $fullResponse .= $chunk;

                    $chunkData = json_encode([
                        'type' => 'chunk',
                        'content' => $chunk,
                    ], JSON_UNESCAPED_UNICODE);
                    echo "data: {$chunkData}

";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                };

                curl_setopt($handle, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$buffer, &$streamCompleted, $emitChunk) {
                    $buffer .= $chunk;

                    while (($pos = strpos($buffer, "\n")) !== false) {
                        $line = trim(substr($buffer, 0, $pos));
                        $buffer = substr($buffer, $pos + 1);

                        if ($line === '') {
                            continue;
                        }

                        $parsed = $this->parseStreamChunk($line);

                        if (isset($parsed['error'])) {
                            throw new RuntimeException($parsed['error']);
                        }

                        if (! empty($parsed['chunk'])) {
                            $emitChunk($parsed['chunk']);
                        }

                        if (! empty($parsed['done'])) {
                            $streamCompleted = true;
                        }
                    }

                    return strlen($chunk);
                });

                try {
                    $success = curl_exec($handle);

                    if ($success === false) {
                        $errorMessage = curl_error($handle) ?: 'Echec du streaming Ollama.';
                        throw new RuntimeException($errorMessage);
                    }
                } finally {
                    curl_close($handle);
                }

                if (! $streamCompleted && trim($buffer) !== '') {
                    $parsed = $this->parseStreamChunk(trim($buffer));

                    if (isset($parsed['error'])) {
                        throw new RuntimeException($parsed['error']);
                    }

                    if (! empty($parsed['chunk'])) {
                        $emitChunk($parsed['chunk']);
                    }
                }

                $toolResult = $this->toolExecutor->parseAndExecuteTools($fullResponse, $user);
                $processedResponse = $toolResult['content'];

                if (! empty($toolResult['tool_results'])) {
                    $updateData = json_encode([
                        'type' => 'tool_result',
                        'content' => $processedResponse,
                        'tool_results' => $toolResult['tool_results'],
                    ]);
                    echo "data: {$updateData}

";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }

                $conversation->messages()->create([
                    'role' => AiConversationMessage::ROLE_ASSISTANT,
                    'content' => $processedResponse,
                    'metadata' => [
                        'tool_results' => $toolResult['tool_results'] ?? [],
                    ],
                ]);

                $doneData = json_encode([
                    'type' => 'done',
                    'content' => $processedResponse,
                ]);
                echo "data: {$doneData}

";

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            } catch (Throwable $exception) {
                report($exception);

                $errorData = json_encode([
                    'type' => 'error',
                    'message' => 'Erreur lors du traitement : ' . $exception->getMessage(),
                ]);
                echo "data: {$errorData}

";

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * Parse une ligne de flux Ollama/OpenAI et extrait le chunk texte/drapeaux.
     *
     * @return array{chunk?:string,done?:bool,error?:string}
     */
    private function parseStreamChunk(string $line): array
    {
        $line = trim($line);
        if ($line === '') {
            return [];
        }

        if (str_starts_with($line, 'data:')) {
            $payload = trim(substr($line, 5));

            if ($payload === '[DONE]') {
                return ['done' => true];
            }

            $data = json_decode($payload, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            if (! empty($data['error'])) {
                return ['error' => (string) $data['error']];
            }

            $delta = $data['choices'][0]['delta']['content'] ?? '';
            $done = ($data['choices'][0]['finish_reason'] ?? null) === 'stop';

            $result = [];
            if ($delta !== '') {
                $result['chunk'] = $delta;
            }
            if ($done) {
                $result['done'] = true;
            }

            return $result;
        }

        $data = json_decode($line, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        if (! empty($data['error'])) {
            return ['error' => (string) $data['error']];
        }

        if (! empty($data['done'])) {
            return ['done' => true];
        }

        $chunk = $data['message']['content'] ?? ($data['response'] ?? '');

        return $chunk !== '' ? ['chunk' => $chunk] : [];
    }

    private function formatTrainer(AiTrainer $trainer): array
    {
        return [
            'slug' => $trainer->slug,
            'name' => $trainer->name,
            'model' => $trainer->model ?: config('ai.default_model'),
            'temperature' => $trainer->temperature ?? (float) config('ai.temperature', 0.7),
            'use_tools' => (bool) $trainer->use_tools,
            'system_prompt' => $trainer->systemPrompt(),
        ];
    }

    private function prepareMessages(AiConversation $conversation, array $trainer): array
    {
        $messages = [];

        $systemPrompt = trim((string) $trainer['system_prompt']);
        if ($systemPrompt !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        if ($trainer['use_tools'] ?? false) {
            $messages[] = [
                'role' => 'system',
                'content' => ToolExecutor::getToolsPrompt(),
            ];
        }

        $latestUserMessage = $conversation->messages()
            ->where('role', AiConversationMessage::ROLE_USER)
            ->latest('id')
            ->first();

        if ($latestUserMessage) {
            $messages[] = [
                'role' => AiConversationMessage::ROLE_USER,
                'content' => $latestUserMessage->content,
            ];
        }

        return $messages;
    }

    /**
     * Retourne la liste des trainers disponibles (pour validation).
     * Mise en cache statique pour éviter les appels répétés à config().
     *
     * @return array<string>
     */
    /**
     * Créer une nouvelle conversation.
     */
    public function createConversation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trainer' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        if (! $user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentification requise',
            ], 401);
        }

        $trainerSlug = $request->input('trainer');
        $targetUser = $user;

        if ($user->superadmin() && $request->filled('user_id')) {
            $targetUser = User::query()->find($request->input('user_id'));

            if (! $targetUser) {
                return response()->json([
                    'error' => true,
                    'message' => 'Utilisateur cible introuvable',
                ], 404);
            }
        }

        $trainerQuery = AiTrainer::query()->active();
        $trainerModel = $trainerSlug ? (clone $trainerQuery)->where('slug', $trainerSlug)->first() : null;

        if (! $trainerModel) {
            $trainerModel = $trainerQuery->where('slug', config('ai.default_trainer_slug', 'default'))->first();
        }

        if (! $trainerModel) {
            $trainerModel = AiTrainer::query()->active()->orderBy('sort_order')->orderBy('name')->first();
        }

        if (! $trainerModel) {
            return response()->json([
                'error' => true,
                'message' => 'Aucun assistant disponible.',
            ], 404);
        }

        $trainer = $this->formatTrainer($trainerModel);

        $conversation = AiConversation::create([
            'user_id' => $targetUser->id,
            'team_id' => $targetUser->currentTeam?->id,
            'status' => AiConversation::STATUS_ACTIVE,
            'metadata' => [
                'trainer' => $trainer['slug'],
                'model' => $trainer['model'],
            ],
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'trainer' => $trainer['slug'],
                'created_at' => $conversation->created_at->toIso8601String(),
                'user' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'email' => $targetUser->email,
                    'superadmin' => (bool) $targetUser->superadmin(),
                ],
            ],
        ]);
    }


    /**
     * Lister les conversations de l'utilisateur.
     */
    public function listConversations(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentification requise',
            ], 401);
        }

        $scope = $request->query('scope', 'self');
        $targetUserId = $user->id;

        if ($user->superadmin() && $request->filled('user_id')) {
            $targetUserId = (int) $request->query('user_id');
        }

        $conversationQuery = AiConversation::query()
            ->where('status', AiConversation::STATUS_ACTIVE)
            ->orderBy('last_message_at', 'desc')
            ->with(['user:id,name,email,superadmin']);

        if (! $user->superadmin() || $scope !== 'all') {
            $conversationQuery->where('user_id', $targetUserId);
        }

        $conversations = $conversationQuery
            ->limit(100)
            ->get()
            ->map(function (AiConversation $conversation) {
                $owner = $conversation->user;

                return [
                    'id' => $conversation->id,
                    'trainer' => $conversation->metadata['trainer'] ?? 'default',
                    'message_count' => $conversation->messages()->count(),
                    'last_message_at' => $conversation->last_message_at?->toIso8601String(),
                    'created_at' => $conversation->created_at->toIso8601String(),
                    'user' => $owner ? [
                        'id' => $owner->id,
                        'name' => $owner->name,
                        'email' => $owner->email,
                        'superadmin' => (bool) $owner->superadmin(),
                    ] : null,
                ];
            });

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Afficher le détail d'une conversation et son historique.
     */
    public function showConversation(Request $request, AiConversation $conversation)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentification requise',
            ], 401);
        }

        if (! $user->superadmin() && $conversation->user_id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Accès refusé',
            ], 403);
        }

        $conversation->loadMissing([
            'user:id,name,email,superadmin',
            'messages.author:id,name,email,superadmin',
        ]);

        $messages = $conversation->messages()
            ->orderBy('id')
            ->limit(200)
            ->get()
            ->map(function (AiConversationMessage $message) {
                $author = $message->author;

                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'created_at' => $message->created_at?->toIso8601String(),
                    'user' => $author ? [
                        'id' => $author->id,
                        'name' => $author->name,
                        'email' => $author->email,
                        'superadmin' => (bool) $author->superadmin(),
                    ] : null,
                ];
            });

        $owner = $conversation->user;

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'trainer' => $conversation->metadata['trainer'] ?? 'default',
                'status' => $conversation->status,
                'created_at' => $conversation->created_at?->toIso8601String(),
                'last_message_at' => $conversation->last_message_at?->toIso8601String(),
                'user' => $owner ? [
                    'id' => $owner->id,
                    'name' => $owner->name,
                    'email' => $owner->email,
                    'superadmin' => (bool) $owner->superadmin(),
                ] : null,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * Liste des utilisateurs (réservé superadmin) pour piloter les conversations.
     */
    public function listUsers(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->superadmin()) {
            return response()->json([
                'error' => true,
                'message' => 'Accès superadmin requis',
            ], 403);
        }

        $search = trim((string) $request->query('search', ''));

        $usersQuery = User::query()
            ->select(['id', 'name', 'email', 'superadmin'])
            ->withCount([
                'aiConversations as active_conversations_count' => function ($query) {
                    $query->where('status', AiConversation::STATUS_ACTIVE);
                },
            ])
            ->orderBy('name');

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $usersQuery
            ->limit(100)
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'superadmin' => (bool) $user->superadmin(),
                    'active_conversations_count' => (int) $user->active_conversations_count,
                ];
            });

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }
}



