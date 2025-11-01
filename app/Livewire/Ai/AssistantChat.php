<?php

namespace App\Livewire\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Services\Ai\AiConversationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class AssistantChat extends Component
{
    public ?int $conversationId = null;

    /** @var array<int, array<string, mixed>> */
    public array $messages = [];

    public string $message = '';

    /**
     * Prompt “brut” utilisé comme système/persona.
     * Peut être injecté via mount($prompt) ou via ?prompt=... (query string).
     */
    public string $systemPrompt = '';

    public bool $awaitingResponse = false;

    public ?string $error = null;

    public ?string $originUrl = null;

    public ?string $originLabel = null;

    public function mount(?string $prompt = null): void
    {
        $this->originUrl = $this->sanitizeOriginUrl(request()->query('origin'));
        $this->originLabel = $this->sanitizeOriginLabel(request()->query('origin_label'));

        // Priorité: argument -> query string -> vide
   
        $this->systemPrompt = $prompt;

        $this->initializeConversation();
    }

    public function render()
    {
        return view('livewire.ai.assistant-chat');
    }

    #[On('assistant-message-updated')]
    public function refreshMessages(): void
    {
        $this->messages = $this->loadMessages();
    }

    public function pollMessages(): void
    {
        if ($this->awaitingResponse) {
            return;
        }

        $this->refreshMessages();
    }

    public function sendMessage(): void
    {
        if ($this->awaitingResponse) {
            return;
        }

        $user = $this->user();

        if (! $user) {
            $this->error = __('Vous devez être connecté pour utiliser l’assistant IA.');
            return;
        }

        $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'message' => __('Message'),
        ]);

        if (! $this->conversationId) {
            $this->error = __('L’assistant IA est indisponible.');
            return;
        }

        if (trim($this->systemPrompt) === '') {
            $this->error = __('Aucun prompt n’est défini pour l’assistant.');
            return;
        }

        $content = trim($this->message);
        if ($content === '') {
            return;
        }

        $this->error = null;
        $this->awaitingResponse = true;

        try {
            $conversation = AiConversation::query()->findOrFail($this->conversationId);

            // Contexte User + Session
            $this->service()->syncUserContext($conversation, $user);
            $this->service()->syncSessionContext($conversation, $this->sessionContext());
            $conversation->refresh();

            // Ajout du message utilisateur
            $context = [
                'label' => 'Assistant IA',
                'path' => url()->current(),
                'user_context_hash' => ($conversation->metadata['user_context']['hash'] ?? null) ?: null,
            ];

            $this->service()->appendMessage(
                $conversation,
                AiConversationMessage::ROLE_USER,
                $content,
                $user,
                $context
            );

            $this->message = '';

            // Génération via prompt brut
            $assistantMessage = $this->service()->generateAssistantReply($conversation, $this->systemPrompt);

            // Actions éventuelles (ex: création ticket)
            $this->handleAssistantActions($assistantMessage, $conversation, $user);

            $this->refreshMessages();
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Erreur : :message', ['message' => $exception->getMessage()]);
        } finally {
            $this->awaitingResponse = false;
        }
    }

    public function startNewConversation(): void
    {
        if ($this->awaitingResponse) {
            return;
        }

        $user = $this->user();

        if (! $user) {
            $this->error = __('Vous devez être connecté pour utiliser l’assistant IA.');
            return;
        }

        if (trim($this->systemPrompt) === '') {
            $this->error = __('Aucun prompt n’est défini pour l’assistant.');
            return;
        }

        try {
            // Crée une nouvelle conversation “sans trainer”, en stockant le prompt brut dans metadata
            $conversation = AiConversation::query()->create([
                'user_id' => $user->getAuthIdentifier(),
                // si votre table a une colonne team_id / formation_id, adaptez ici :
                'team_id' => optional($user->currentTeam)->id,
                'metadata' => [
                    'system_prompt' => $this->systemPrompt,
                    'origin' => $this->sessionContext(),
                ],
            ]);

            $this->service()->syncSessionContext($conversation, $this->sessionContext());
            $conversation->refresh();

            $this->conversationId = $conversation->id;
            $this->messages = [];
            $this->message = '';
            $this->error = null;

            // Optionnel : insérer un message système avec le prompt pour traçabilité
            $this->service()->appendMessage(
                $conversation,
                AiConversationMessage::ROLE_SYSTEM,
                '[[SYSTEM_PROMPT]]' . "\n" . $this->systemPrompt,
                null,
                ['label' => 'Assistant IA', 'path' => url()->current()]
            );

            $this->refreshMessages();
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Impossible de démarrer une nouvelle conversation : :message', ['message' => $exception->getMessage()]);
        }
    }

    private function initializeConversation(): void
    {
        $user = $this->user();

        if (! $user) {
            $this->resetConversationState();
            $this->error = __('Vous devez être connecté pour utiliser l’assistant IA.');
            return;
        }

        try {
            // On tente de retrouver une conversation ouverte avec le même contexte (team + origin),
            // sinon on en crée une nouvelle.
            $conversation = AiConversation::query()
                ->where('user_id', $user->getAuthIdentifier())
                ->when(optional($user->currentTeam)->id, fn ($q, $teamId) => $q->where('team_id', $teamId))
                ->latest('id')
                ->first();

            if (! $conversation) {
                $conversation = AiConversation::query()->create([
                    'user_id' => $user->getAuthIdentifier(),
                    'team_id' => optional($user->currentTeam)->id,
                    'metadata' => [
                        'system_prompt' => $this->systemPrompt,
                        'origin' => $this->sessionContext(),
                    ],
                ]);

                // Message système optionnel pour journaliser le prompt initial
                if (trim($this->systemPrompt) !== '') {
                    $this->service()->appendMessage(
                        $conversation,
                        AiConversationMessage::ROLE_SYSTEM,
                        '[[SYSTEM_PROMPT]]' . "\n" . $this->systemPrompt,
                        null,
                        ['label' => 'Assistant IA', 'path' => url()->current()]
                    );
                }
            } else {
                // Si une conversation existe déjà, on s’assure que le prompt courant est en metadata
                $metadata = $conversation->metadata ?? [];
                if (($metadata['system_prompt'] ?? '') !== $this->systemPrompt && trim($this->systemPrompt) !== '') {
                    $metadata['system_prompt'] = $this->systemPrompt;
                    $conversation->forceFill(['metadata' => $metadata])->save();
                }
            }

            $this->service()->syncUserContext($conversation, $user);
            $this->service()->syncSessionContext($conversation, $this->sessionContext());
            $conversation->refresh();

            $this->conversationId = $conversation->id;
            $this->error = null;
            $this->messages = $this->loadMessages();
        } catch (Throwable $exception) {
            report($exception);
            $this->resetConversationState();
            $this->error = __('Erreur lors de la création de la conversation : :message', ['message' => $exception->getMessage()]);
        }
    }

    private function loadMessages(): array
    {
        if (! $this->conversationId) {
            return [];
        }

        return AiConversationMessage::query()
            ->where('conversation_id', $this->conversationId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (AiConversationMessage $message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'author' => $this->authorFor($message),
                    'content' => $message->content,
                    'created_at' => optional($message->created_at)->toIso8601String(),
                    'created_at_human' => optional($message->created_at)->diffForHumans(),
                ];
            })
            ->all();
    }

    private function authorFor(AiConversationMessage $message): string
    {
        return match ($message->role) {
            AiConversationMessage::ROLE_ASSISTANT => __('Assistant IA'),
            AiConversationMessage::ROLE_SYSTEM => __('Système'),
            default => __('Vous'),
        };
    }

    private function handleAssistantActions(AiConversationMessage $assistantMessage, AiConversation $conversation, Authenticatable $user): void
    {
        $content = $assistantMessage->content ?? '';

        if (! is_string($content) || ! Str::contains($content, '[[CREATE_TICKET]]')) {
            return;
        }

        [$visibleContent, $ticketPayload] = explode('[[CREATE_TICKET]]', $content, 2);

        $visibleContent = trim($visibleContent);
        $ticketSummary = isset($ticketPayload) ? trim($ticketPayload) : '';

        if ($visibleContent !== $content) {
            $assistantMessage->forceFill([
                'content' => $visibleContent,
            ])->save();
        }

        $this->createSupportTicketFromAssistant($conversation, $user, $ticketSummary);
    }

    private function createSupportTicketFromAssistant(AiConversation $conversation, Authenticatable $user, string $summary): void
    {
        $userId = $user->getAuthIdentifier();

        if (! $userId) {
            return;
        }

        $summary = Str::limit(trim($summary), 500, '');

        $history = $conversation->messages()
            ->latest('id')
            ->limit(8)
            ->get()
            ->sortBy('id')
            ->map(function (AiConversationMessage $message) {
                $label = match ($message->role) {
                    AiConversationMessage::ROLE_ASSISTANT => 'Assistant',
                    AiConversationMessage::ROLE_SYSTEM => 'Système',
                    default => 'Utilisateur',
                };

                return sprintf(
                    '%s (%s) : %s',
                    $label,
                    optional($message->created_at)->format('d/m/Y H:i'),
                    trim($message->content)
                );
            })
            ->implode("\n\n");

        $bodySections = [];

        if ($summary !== '') {
            $bodySections[] = "Résumé de l'assistant :\n".$summary;
        }

        if ($history !== '') {
            $bodySections[] = "Extrait de la conversation :\n".$history;
        }

        $bodySections[] = sprintf('Conversation IA #%d.', $conversation->id);

        $body = implode("\n\n", $bodySections);

        $subjectSource = $summary !== '' ? $summary : __('Demande d’assistance via l’assistant IA');
        $subject = Str::limit($subjectSource, 120, '...');

        $ticket = null;

        DB::transaction(function () use (&$ticket, $userId, $subject, $body) {
            $ticket = SupportTicket::create([
                'user_id' => $userId,
                'subject' => $subject,
                'status' => SupportTicket::STATUS_OPEN,
                'origin_label' => $this->originLabel,
                'origin_path' => $this->originUrl,
            ]);

            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $userId,
                'is_support' => false,
                'content' => $body,
                'context_label' => $this->originLabel ?? 'Assistant IA',
                'context_path' => $this->originUrl ?? url()->current(),
            ]);
        });

        if (! $ticket) {
            return;
        }

        $sessionContext = $this->sessionContext();
        $sessionContext['last_ticket_id'] = $ticket->id;
        $this->service()->syncSessionContext($conversation, $sessionContext);

        $confirmation = __('Un ticket support a été créé pour vous (référence : #:id). Un membre de l’équipe vous répondra prochainement.', ['id' => $ticket->id]);

        $this->service()->appendMessage(
            $conversation,
            AiConversationMessage::ROLE_SYSTEM,
            $confirmation,
            null,
            [
                'label' => 'Assistant IA',
                'path' => url()->current(),
                'support_ticket_id' => $ticket->id,
            ]
        );
    }

    private function resetConversationState(): void
    {
        $this->conversationId = null;
        $this->messages = [];
        $this->awaitingResponse = false;
        $this->message = '';
        $this->systemPrompt = $this->systemPrompt ?: '';
        $this->error = null;
    }

    private function sanitizeOriginUrl(?string $url): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $trimmed = trim($url);

        if ($trimmed === '' || ! filter_var($trimmed, FILTER_VALIDATE_URL)) {
            return null;
        }

        return Str::limit($trimmed, 500, '');
    }

    private function sanitizeOriginLabel(?string $label): ?string
    {
        if (! is_string($label)) {
            return null;
        }

        $trimmed = trim($label);

        if ($trimmed === '') {
            return null;
        }

        return Str::limit($trimmed, 150, '…');
    }

    /**
     * @return array<string, string>
     */
    private function sessionContext(): array
    {
        return array_filter([
            'origin_url' => $this->originUrl,
            'origin_label' => $this->originLabel,
        ], fn ($value) => is_string($value) && $value !== '');
    }

    private function user(): ?Authenticatable
    {
        return Auth::user();
    }

    private function service(): AiConversationService
    {
        return app(AiConversationService::class);
    }
}
