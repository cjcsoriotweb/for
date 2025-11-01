<?php

namespace App\Livewire\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Services\Ai\AiConversationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
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

    public function mount(?int $trainerId = null): void
    {
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

            $context = [
                'label' => 'Assistant IA',
                'path' => url()->current(),
            ];

            $this->service()->appendMessage(
                $conversation,
                AiConversationMessage::ROLE_USER,
                $content,
                $user,
                $context
            );

            $this->message = '';

            $this->service()->generateAssistantReply($conversation, $trainer);

            $this->refreshMessages();
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Erreur : :message', ['message' => $exception->getMessage()]);
        } finally {
            $this->awaitingResponse = false;
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
            AiConversationMessage::ROLE_ASSISTANT => $this->trainer['name'] ?: __('Assistant IA'),
            AiConversationMessage::ROLE_SYSTEM => __('Systeme'),
            default => __('Vous'),
        };
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

    private function user(): ?Authenticatable
    {
        return Auth::user();
    }

    private function service(): AiConversationService
    {
        return app(AiConversationService::class);
    }
}
