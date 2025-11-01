<?php

namespace App\Services\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AiConversationService
{
    /**
     * (Optionnel) Créer/retourner une conversation active SANS trainer,
     * en stockant le prompt brut dans metadata.
     */
    public function getOrCreateConversationRaw(User $user, ?Formation $formation = null, ?Team $team = null, string $systemPrompt = ''): AiConversation
    {
        return DB::transaction(function () use ($user, $formation, $team, $systemPrompt) {
            $conversation = AiConversation::query()->firstOrCreate(
                [
                    'user_id' => $user->id,
                    'formation_id' => $formation?->id,
                ],
                [
                    'team_id' => $team?->id,
                    'status' => AiConversation::STATUS_ACTIVE,
                    'metadata' => [
                        'system_prompt' => $systemPrompt,
                    ],
                    'last_message_at' => now(),
                ]
            );

            // S’assure que le prompt courant est présent en metadata (sans écraser si vide).
            if ($systemPrompt !== '') {
                $metadata = $conversation->metadata ?? [];
                if (($metadata['system_prompt'] ?? '') !== $systemPrompt) {
                    $metadata['system_prompt'] = $systemPrompt;
                    $conversation->forceFill(['metadata' => $metadata])->save();
                }
            }

            $this->syncUserContext($conversation, $user);

            return $conversation->fresh();
        });
    }

    /**
     * (Optionnel) Démarrer une nouvelle conversation (archive les actives semblables).
     */
    public function startNewConversationRaw(User $user, ?Formation $formation = null, ?Team $team = null, string $systemPrompt = ''): AiConversation
    {
        return DB::transaction(function () use ($user, $formation, $team, $systemPrompt) {
            $query = AiConversation::query()
                ->where('user_id', $user->id);

            $query = $formation
                ? $query->where('formation_id', $formation->id)
                : $query->whereNull('formation_id');

            $query = $team
                ? $query->where('team_id', $team->id)
                : $query->whereNull('team_id');

            /** @var Collection<int, AiConversation> $existing */
            $existing = $query->get();

            foreach ($existing as $conversation) {
                if ($conversation->status === AiConversation::STATUS_ACTIVE) {
                    $conversation->archive();
                }
            }

            $conversation = AiConversation::query()->create([
                'user_id' => $user->id,
                'formation_id' => $formation?->id,
                'team_id' => $team?->id,
                'status' => AiConversation::STATUS_ACTIVE,
                'metadata' => array_replace(
                    $existing->first()?->metadata ?? [],
                    ['system_prompt' => $systemPrompt]
                ),
                'last_message_at' => now(),
            ]);

            $this->syncUserContext($conversation, $user);

            return $conversation->fresh();
        });
    }

    public function appendMessage(
        AiConversation $conversation,
        string $role,
        string $content,
        ?User $author = null,
        array $context = []
    ): AiConversationMessage {
        return DB::transaction(function () use ($conversation, $role, $content, $author, $context) {
            $message = $conversation->messages()->create([
                'role' => $role,
                'content' => $content,
                'user_id' => $author?->id,
                'context_label' => Str::limit($context['label'] ?? '', 120, ''),
                'context_path' => Str::limit($context['path'] ?? '', 255, ''),
                'metadata' => Arr::except($context, ['label', 'path']),
            ]);

            $conversation->forceFill([
                'last_message_at' => now(),
            ])->save();

            return $message;
        });
    }

    public function syncUserContext(AiConversation $conversation, User $user): void
    {
        $context = trim((string) $user->getIaContext());
        $metadata = $conversation->metadata ?? [];
        $hash = $context === '' ? null : sha1($context);
        $currentHash = Arr::get($metadata, 'user_context.hash');

        if ($hash === null) {
            if ($currentHash !== null) {
                Arr::forget($metadata, 'user_context');
                $conversation->forceFill(['metadata' => $metadata])->save();
            }
            return;
        }

        if ($currentHash === $hash) {
            return;
        }

        $metadata['user_context'] = [
            'hash' => $hash,
            'content' => $context,
            'updated_at' => now()->toIso8601String(),
        ];

        $conversation->forceFill(['metadata' => $metadata])->save();
    }

    public function syncSessionContext(AiConversation $conversation, array $session = []): void
    {
        if ($session === []) {
            return;
        }

        $metadata = $conversation->metadata ?? [];
        $existing = Arr::get($metadata, 'session', []);

        if (! is_array($existing)) {
            $existing = [];
        }

        foreach ($session as $key => $value) {
            if ($value === null || (is_string($value) && trim($value) === '')) {
                unset($existing[$key]);
            } else {
                $existing[$key] = is_string($value) ? trim($value) : $value;
            }
        }

        if ($existing === []) {
            Arr::forget($metadata, 'session');
        } else {
            $metadata['session'] = $existing;
        }

        $conversation->forceFill(['metadata' => $metadata])->save();
    }

    /**
     * Génère la réponse de l’assistant à partir d’un prompt BRUT.
     *
     * @param  array<int, array{role:string, content:string}>  $pendingMessages
     */
    public function generateAssistantReply(AiConversation $conversation, string $systemPrompt, array $pendingMessages = []): AiConversationMessage
    {
        // Provider & modèle depuis la config (avec overrides possibles dans metadata)
        $provider = Arr::get($conversation->metadata, 'provider', config('ai.default_driver', 'ollama'));
        $providerConfig = config("ai.providers.$provider");

        if (! $providerConfig) {
            throw new RuntimeException(sprintf('Provider [%s] is not configured.', (string) $provider));
        }

        $client = ChatCompletionClient::fromConfig($providerConfig);

        $model = Arr::get(
            $conversation->metadata,
            'model',
            Arr::get($providerConfig, 'default_model', 'llama3')
        );

        $historyLimit = max((int) config('ai.conversation.history_limit', 30), 1);

        $history = $conversation->messages()
            ->latest('id')
            ->limit($historyLimit)
            ->get()
            ->sortBy('created_at')
            ->values();

        $messages = [];

        // 1) Prompt système brut
        if (trim($systemPrompt) !== '') {
            $messages[] = [
                'role' => AiConversationMessage::ROLE_SYSTEM,
                'content' => $systemPrompt,
            ];
        }

        // 2) Contexte utilisateur
        $userContext = Arr::get($conversation->metadata, 'user_context.content');
        if (is_string($userContext) && trim($userContext) !== '') {
            $messages[] = [
                'role' => AiConversationMessage::ROLE_SYSTEM,
                'content' => $userContext,
            ];
        }

        // 3) Contexte de session
        $sessionContext = Arr::get($conversation->metadata, 'session', []);
        if (is_array($sessionContext) && $sessionContext !== []) {
            $sessionDetails = [];

            $originLabel = Arr::get($sessionContext, 'origin_label');
            if (is_string($originLabel) && trim($originLabel) !== '') {
                $sessionDetails[] = "Page courante de l'utilisateur : ".$originLabel;
            }

            $originUrl = Arr::get($sessionContext, 'origin_url');
            if (is_string($originUrl) && trim($originUrl) !== '') {
                $sessionDetails[] = 'URL de la page : '.$originUrl;
            }

            $lastTicket = Arr::get($sessionContext, 'last_ticket_id');
            if ($lastTicket) {
                $sessionDetails[] = 'Dernier ticket support créé : #'.$lastTicket;
            }

            if ($sessionDetails !== []) {
                $messages[] = [
                    'role' => AiConversationMessage::ROLE_SYSTEM,
                    'content' => implode("\n", $sessionDetails),
                ];
            }
        }

        // 4) Règle de création de ticket (toujours ajoutée)
        $messages[] = [
            'role' => AiConversationMessage::ROLE_SYSTEM,
            'content' => "Si tu ne disposes pas de l'information demandée, indique-le clairement. Propose à l'utilisateur de créer un ticket support. Si l'utilisateur confirme qu'il souhaite une demande, termine ta réponse par [[CREATE_TICKET]] suivi d'un bref résumé (max 400 caractères) de la demande. N'utilise JAMAIS la balise sans consentement explicite.",
        ];

        // 5) Historique
        /** @var AiConversationMessage $msg */
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        // 6) Éventuels messages en attente
        foreach ($pendingMessages as $pending) {
            $messages[] = $pending;
        }

        // Payload modèle
        $payload = [
            'model' => $model,
            'messages' => $messages,
        ];

        // Température (override metadata.settings.temperature > config)
        $temperature = Arr::get(
            $conversation->metadata,
            'settings.temperature',
            Arr::get($providerConfig, 'temperature')
        );

        if ($temperature !== null) {
            $payload['temperature'] = (float) $temperature;
        }

        $response = $client->chat($payload);

        $choice = Arr::get($response, 'choices.0');
        $content = Arr::get($choice, 'message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new RuntimeException('La réponse du modèle est vide.');
        }

        $usage = Arr::get($response, 'usage', []);

        return $conversation->messages()->create([
            'role' => AiConversationMessage::ROLE_ASSISTANT,
            'content' => $content,
            'prompt_tokens' => Arr::get($usage, 'prompt_tokens'),
            'completion_tokens' => Arr::get($usage, 'completion_tokens'),
            'metadata' => [
                'provider' => $provider,
                'model' => $payload['model'],
            ],
        ]);
    }
}
