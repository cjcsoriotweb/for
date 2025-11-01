<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Services\Ai\OllamaClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Contrôleur unique pour les interactions IA.
 * Gère le streaming NDJSON pour les réponses progressives.
 */
class AiController extends Controller
{
    public function __construct(
        private readonly OllamaClient $ollamaClient
    ) {}

    /**
     * Endpoint unique pour le streaming IA.
     * Retourne un flux NDJSON (Newline Delimited JSON).
     *
     * @throws ValidationException
     */
    public function stream(Request $request): Response
    {
        // Validation - les trainers disponibles sont mis en cache
        $availableTrainers = implode(',', $this->getAvailableTrainers());
        
        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string', 'max:' . config('ai.max_message_length', 2000)],
            'trainer' => ['nullable', 'string', 'in:' . $availableTrainers],
            'conversation_id' => ['nullable', 'integer', 'exists:ai_conversations,id'],
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

        // Récupérer ou créer la conversation
        if ($conversationId) {
            $conversation = AiConversation::query()
                ->where('id', $conversationId)
                ->where('user_id', $user->id)
                ->first();

            if (!$conversation) {
                return response()->json([
                    'error' => true,
                    'message' => 'Conversation non trouvée',
                ], 404);
            }
        } else {
            // Créer une nouvelle conversation
            $conversation = AiConversation::create([
                'user_id' => $user->id,
                'team_id' => $user->currentTeam?->id,
                'status' => AiConversation::STATUS_ACTIVE,
                'metadata' => [
                    'trainer' => $trainerSlug,
                    'model' => $trainer['model'],
                ],
                'last_message_at' => now(),
            ]);
        }

        // Sauvegarder le message utilisateur
        $conversation->messages()->create([
            'role' => AiConversationMessage::ROLE_USER,
            'content' => $message,
            'user_id' => $user->id,
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

                // Sauvegarder la réponse complète
                $conversation->messages()->create([
                    'role' => AiConversationMessage::ROLE_ASSISTANT,
                    'content' => $fullResponse,
                    'metadata' => [
                        'trainer' => $trainer['slug'],
                        'model' => $trainer['model'],
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

        // 2. Contexte utilisateur (si disponible)
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

        // 3. Historique des messages (limité)
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
}
