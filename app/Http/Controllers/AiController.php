<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\User;
use App\Services\Ai\OllamaClient;
use App\Services\Ai\ToolExecutor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        // Validation - les trainers disponibles sont mis en cache
        $availableTrainers = implode(',', $this->getAvailableTrainers());
        
        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string', 'max:' . config('ai.max_message_length', 2000)],
            'trainer' => ['nullable', 'string', 'in:' . $availableTrainers],
            'conversation_id' => ['required', 'integer', 'exists:ai_conversations,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentification requise',
            ], 401);
        }

        $message = trim($request->input('message'));
        $trainerSlug = $request->input('trainer', config('ai.default_trainer_slug'));
        $conversationId = $request->input('conversation_id');

        // Récupérer le trainer depuis la config
        $trainer = config("ai.trainers.$trainerSlug");
        if (!$trainer) {
            return response()->json([
                'error' => true,
                'message' => "Trainer '$trainerSlug' non trouvé",
            ], 404);
        }

        // Récupérer la conversation (doit exister)
        $conversationQuery = AiConversation::query()
            ->where('id', $conversationId);

        if (! $user->superadmin()) {
            $conversationQuery->where('user_id', $user->id);
        }

        $conversation = $conversationQuery->first();

        if (!$conversation) {
            return response()->json([
                'error' => true,
                'message' => 'Conversation non trouvée. Veuillez créer une conversation d\'abord.',
            ], 404);
        }

        $messageAuthorId = $user->id;

        if ($user->superadmin() && $request->filled('acting_user_id')) {
            $actingUserId = (int) $request->input('acting_user_id');
            $actingUser = User::query()->find($actingUserId);

            if ($actingUser) {
                $messageAuthorId = $actingUser->id;
            }
        }

        // Sauvegarder le message utilisateur
        $conversation->messages()->create([
            'role' => AiConversationMessage::ROLE_USER,
            'content' => $message,
            'user_id' => $messageAuthorId,
        ]);

        // Préparer les messages pour l'IA
        $messages = $this->prepareMessages($conversation, $trainer);

        // Streamer la réponse
        return response()->stream(function () use ($conversation, $messages, $trainer, $user) {
            $fullResponse = '';

            // Envoyer l'ID de conversation au début
            $conversationData = json_encode([
                'type' => 'conversation_id',
                'conversation_id' => $conversation->id,
            ]);
            echo "data: {$conversationData}\n\n";
            
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();

            try {
                // Stream les chunks de réponse
                foreach ($this->ollamaClient->chatStream($messages, $trainer) as $chunk) {
                    $fullResponse .= $chunk;

                    $chunkData = json_encode([
                        'type' => 'chunk',
                        'content' => $chunk,
                    ]);
                    echo "data: {$chunkData}\n\n";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }

                // Exécuter les outils si présents dans la réponse
                $toolResult = $this->toolExecutor->parseAndExecuteTools($fullResponse, $user);
                $processedResponse = $toolResult['content'];
                
                // Si des outils ont été utilisés, envoyer le contenu traité
                if (!empty($toolResult['tool_results'])) {
                    // Envoyer le contenu mis à jour (avec résultats des outils)
                    $updateData = json_encode([
                        'type' => 'tool_result',
                        'content' => $processedResponse,
                        'tool_results' => $toolResult['tool_results'],
                    ]);
                    echo "data: {$updateData}\n\n";
                    
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
                
                // Sauvegarder la réponse complète (avec résultats d'outils)
                $conversation->messages()->create([
                    'role' => AiConversationMessage::ROLE_ASSISTANT,
                    'content' => $processedResponse,
                    'metadata' => [
                        'trainer' => $trainer['slug'],
                        'model' => $trainer['model'],
                        'tools_used' => !empty($toolResult['tool_results']),
                        'tool_results' => $toolResult['tool_results'],
                    ],
                ]);

                $conversation->update(['last_message_at' => now()]);

                // Envoyer le signal de fin
                $doneData = json_encode(['type' => 'done']);
                echo "data: {$doneData}\n\n";

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            } catch (\Throwable $e) {
                // Envoyer l'erreur
                $errorData = json_encode([
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ]);
                echo "data: {$errorData}\n\n";

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
     * Prépare les messages pour l'IA en incluant le prompt système et l'historique.
     *
     * @param  AiConversation  $conversation
     * @param  array<string, mixed>  $trainer
     * @return array<int, array{role: string, content: string}>
     */
    private function prepareMessages(AiConversation $conversation, array $trainer): array
    {
        $messages = [];

        // 1. Prompt système du trainer
        $messages[] = [
            'role' => 'system',
            'content' => $trainer['system_prompt'],
        ];

        // 2. Ajouter les définitions d'outils si le trainer les utilise
        if ($trainer['use_tools'] ?? false) {
            $messages[] = [
                'role' => 'system',
                'content' => ToolExecutor::getToolsPrompt(),
            ];
        }

        // 3. Contexte utilisateur (si disponible)
        $user = $conversation->user;
        if ($user && method_exists($user, 'getIaContext')) {
            $userContext = trim((string) $user->getIaContext());
            if ($userContext !== '') {
                $messages[] = [
                    'role' => 'system',
                    'content' => "Contexte utilisateur :\n" . $userContext,
                ];
            }
        }

        // 4. Historique des messages (limité)
        $historyLimit = config('ai.history_limit', 30);
        $history = $conversation->messages()
            ->latest('id')
            ->limit($historyLimit)
            ->get()
            ->sortBy('id')
            ->values();

        foreach ($history as $msg) {
            if ($msg->role !== AiConversationMessage::ROLE_SYSTEM) {
                $messages[] = [
                    'role' => $msg->role,
                    'content' => $msg->content,
                ];
            }
        }

        return $messages;
    }

    /**
     * Retourne la liste des trainers disponibles (pour validation).
     * Mise en cache statique pour éviter les appels répétés à config().
     *
     * @return array<string>
     */
    private function getAvailableTrainers(): array
    {
        static $trainers = null;

        if ($trainers === null) {
            $trainers = array_keys(config('ai.trainers', []));
        }

        return $trainers;
    }

    /**
     * Créer une nouvelle conversation.
     */
    public function createConversation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trainer' => ['nullable', 'string', 'in:' . implode(',', $this->getAvailableTrainers())],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentification requise',
            ], 401);
        }

        $trainerSlug = $request->input('trainer', config('ai.default_trainer_slug'));
        $trainer = config("ai.trainers.$trainerSlug");
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

        if (!$trainer) {
            return response()->json([
                'error' => true,
                'message' => "Trainer '$trainerSlug' non trouvé",
            ], 404);
        }

        $conversation = AiConversation::create([
            'user_id' => $targetUser->id,
            'team_id' => $targetUser->currentTeam?->id,
            'status' => AiConversation::STATUS_ACTIVE,
            'metadata' => [
                'trainer' => $trainerSlug,
                'model' => $trainer['model'],
            ],
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'trainer' => $trainerSlug,
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
