<?php

namespace App\Livewire\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
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
    public ?int $trainerId = null;

    public ?int $conversationId = null;

    /** @var array<int, array<string, mixed>> */
    public array $messages = [];

    public string $message = '';

    /** @var array{name: string, description: ?string, avatar: ?string} */
    public array $trainer = [
        'name' => '',
        'description' => null,
        'avatar' => null,
    ];

    public bool $hasTrainer = false;

    public bool $awaitingResponse = false;

    public ?string $error = null;

    public ?string $originUrl = null;

    public ?string $originLabel = null;

    public function mount(?int $trainerId = null): void
    {
        $this->originUrl = $this->sanitizeOriginUrl(request()->query('origin'));
        $this->originLabel = $this->sanitizeOriginLabel(request()->query('origin_label'));
        $this->trainerId = $trainerId;
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
            $this->error = __('Vous devez etre connecte pour utiliser l\'assistant IA.');

            return;
        }

        $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'message' => __('Message'),
        ]);

        if (! $this->conversationId || ! $this->hasTrainer) {
            $this->error = __('L\'assistant IA est indisponible.');

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
            $trainer = AiTrainer::query()->findOrFail($conversation->ai_trainer_id);

            $this->service()->syncUserContext($conversation, $user);
            $this->service()->syncSessionContext($conversation, $this->sessionContext());
            $conversation->refresh();

            $metadata = $conversation->metadata ?? [];
            $context = [
                'label' => 'Assistant IA',
                'path' => url()->current(),
                'user_context_hash' => $metadata['user_context']['hash'] ?? null,
            ];

            $this->service()->appendMessage(
                $conversation,
                AiConversationMessage::ROLE_USER,
                $content,
                $user,
                $context
            );

            $this->message = '';

            $assistantMessage = $this->service()->generateAssistantReply($conversation, $trainer);

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
            $this->error = __('Vous devez etre connecte pour utiliser l\'assistant IA.');

            return;
        }

        if (! $this->trainerId) {
            $this->error = __('Aucun assistant IA selectionne.');

            return;
        }

        try {
            $trainer = AiTrainer::query()->findOrFail($this->trainerId);
            $conversation = $this->service()->startNewConversation(
                $trainer,
                $user,
                formation: null,
                team: $user->currentTeam
            );

            $this->service()->syncSessionContext($conversation, $this->sessionContext());
            $conversation->refresh();

            $this->conversationId = $conversation->id;
            $this->messages = [];
            $this->message = '';
            $this->error = null;
            $this->refreshMessages();
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Impossible de demarrer une nouvelle conversation : :message', ['message' => $exception->getMessage()]);
        }
    }

    private function initializeConversation(): void
    {
        $user = $this->user();

        if (! $user) {
            $this->resetConversationState();
            $this->error = __('Vous devez etre connecte pour utiliser l\'assistant IA.');

            return;
        }

        $trainer = $this->service()->resolveTrainer(null, $this->trainerId);

        if (! $trainer) {
            $this->resetConversationState();
            $this->error = __('Aucun assistant IA n\'est configure. Veuillez contacter l\'administrateur.');

            return;
        }

        try {
            $conversation = $this->service()->getOrCreateConversation(
                $trainer,
                $user,
                formation: null,
                team: $user->currentTeam
            );

            $this->service()->syncUserContext($conversation, $user);
            $this->service()->syncSessionContext($conversation, $this->sessionContext());
            $conversation->refresh();

            $this->conversationId = $conversation->id;
            $this->trainerId = $trainer->id;
            $this->trainer = [
                'name' => $trainer->name,
                'description' => $trainer->description,
                'avatar' => $trainer->avatar_path ?: 'images/ai-trainer-placeholder.svg',
            ];
            $this->hasTrainer = true;
            $this->error = null;
            $this->messages = $this->loadMessages();
        } catch (Throwable $exception) {
            report($exception);
            $this->resetConversationState();
            $this->error = __('Erreur lors de la creation de la conversation : :message', ['message' => $exception->getMessage()]);
        }
    }

    private function loadMessages(): array
    {
        if (! $this->conversationId) {
            return [];
        }

        return AiConversationMessage::query()
            ->where('conversation_id', $this->conversationId)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(function (AiConversationMessage $message) {
                $metadata = $message->metadata ?? [];

                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'author' => $this->authorFor($message),
                    'content' => $message->content,
                    'created_at' => optional($message->created_at)->toIso8601String(),
                    'created_at_human' => optional($message->created_at)->diffForHumans(),
                    'segment_index' => (int) ($metadata['segment_index'] ?? 0),
                    'segment_of' => $metadata['segment_of'] ?? null,
                ];
            })
            ->all();
    }

    private function authorFor(AiConversationMessage $message): string
    {
        return match ($message->role) {
            AiConversationMessage::ROLE_ASSISTANT => $this->trainer['name'] ?: __('Assistant IA'),
            AiConversationMessage::ROLE_SYSTEM => __('Systeme'),
            default => __('Vous'),
        };
    }

    private function handleAssistantActions(AiConversationMessage $assistantMessage, AiConversation $conversation, Authenticatable $user): void
    {
        $messages = $this->segmentAssistantMessage($assistantMessage, $conversation);

        foreach ($messages as $message) {
            $content = $message->content ?? '';

            if (! is_string($content) || ! Str::contains($content, '[[CREATE_TICKET]]')) {
                continue;
            }

            [$visibleContent, $ticketPayload] = explode('[[CREATE_TICKET]]', $content, 2);

            $visibleContent = trim($visibleContent);
            $ticketSummary = isset($ticketPayload) ? trim($ticketPayload) : '';

            if ($visibleContent === '') {
                $message->delete();
            } else {
                $message->forceFill([
                    'content' => $visibleContent,
                ])->save();
            }

            $this->createSupportTicketFromAssistant($conversation, $user, $ticketSummary);

            break;
        }
    }

    /**
     * @return array<int, AiConversationMessage>
     */
    private function segmentAssistantMessage(AiConversationMessage $assistantMessage, AiConversation $conversation): array
    {
        if ($assistantMessage->role !== AiConversationMessage::ROLE_ASSISTANT) {
            return [$assistantMessage];
        }

        $rawContent = (string) ($assistantMessage->content ?? '');
        $normalized = preg_replace("/\r\n|\r/", "\n", $rawContent);
        $parts = array_values(array_filter(array_map('trim', preg_split("/\n{2,}/", $normalized)), fn ($part) => $part !== ''));

        if (count($parts) <= 1) {
            $metadata = $assistantMessage->metadata ?? [];
            $metadata['segment_index'] = (int) ($metadata['segment_index'] ?? 0);

            $assistantMessage->forceFill([
                'content' => $parts[0] ?? $rawContent,
                'metadata' => $metadata,
            ])->save();

            return [$assistantMessage];
        }

        $first = array_shift($parts);
        $metadata = $assistantMessage->metadata ?? [];
        $metadata['segment_index'] = 0;
        $metadata['segment_count'] = count($parts) + 1;

        $assistantMessage->forceFill([
            'content' => $first,
            'metadata' => $metadata,
        ])->save();

        $messages = [$assistantMessage];

        foreach ($parts as $index => $segment) {
            $segmentMetadata = [
                'segment_of' => $assistantMessage->id,
                'segment_index' => $index + 1,
            ];

            $timestamp = now()->addMilliseconds(($index + 1) * 500);

            $messages[] = $conversation->messages()->create([
                'role' => AiConversationMessage::ROLE_ASSISTANT,
                'content' => $segment,
                'metadata' => $segmentMetadata,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        return $messages;
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
                    AiConversationMessage::ROLE_SYSTEM => 'Systeme',
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
            $bodySections[] = "Resume de l'assistant :\n".$summary;
        }

        if ($history !== '') {
            $bodySections[] = "Extrait de la conversation :\n".$history;
        }

        $bodySections[] = sprintf('Conversation IA #%d.', $conversation->id);

        $body = implode("\n\n", $bodySections);

        $subjectSource = $summary !== '' ? $summary : __('Demande d\'assistance via l\'assistant IA');
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

        $confirmation = __('Un ticket support a été créé pour vous (référence : #:id). Un membre de l\'équipe vous répondra prochainement.', ['id' => $ticket->id]);

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
        $this->hasTrainer = false;
        $this->messages = [];
        $this->trainer = [
            'name' => '',
            'description' => null,
            'avatar' => null,
        ];
        $this->awaitingResponse = false;
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