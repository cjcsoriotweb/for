<?php

namespace App\Livewire\Chat;

use App\Models\AiTrainer;
use App\Models\IaChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AiChat extends Component
{
    public $contactId;

    public $title;

    public $messages = [];

    public $message = '';

    public $isActive = false;

    public $awaitingAiResponse = false;

    public ?int $trainerId = null;

    public ?string $aiTrainerSlug = null;

    public ?string $aiAssistantName = null;

    public bool $showAiDebug = false;

    protected $listeners = [
        'activate-chat-polling' => 'activatePolling',
    ];

    public function mount($contactId, $title = null): void
    {
        $this->contactId = $contactId;
        $this->title = $title ?: 'Assistant IA';
        $this->showAiDebug = config('app.debug', false);

        $this->initializeContext();
        $this->loadMessages();
        $this->isActive = true;
    }

    public function sendMessage(): void
    {
        $text = trim($this->message);
        if ($text === '') {
            return;
        }

        $user = Auth::user();
        if (! $user) {
            return;
        }

        if (! $this->trainerId || ! $this->aiTrainerSlug) {
            $this->initializeContext();
        }

        if (! $this->trainerId || ! $this->aiTrainerSlug) {
            return;
        }

        $this->message = '';
        $this->awaitingAiResponse = true;

        try {
            $chatMessage = IaChatMessage::query()->create([
                'user_id' => $user->id,
                'trainer_id' => $this->trainerId,
                'role' => IaChatMessage::ROLE_USER,
                'status' => IaChatMessage::STATUS_PENDING,
                'content' => $text,
            ]);

            $this->loadMessages();

            $this->dispatchBrowserEvent('chat-message-sent', [
                'componentId' => $this->getId(),
            ]);

            $this->dispatchBrowserEvent('start-ai-stream', [
                'componentId' => $this->getId(),
                'messageId' => $chatMessage->id,
                'trainerSlug' => $this->aiTrainerSlug,
                'message' => $text,
                'assistantName' => $this->aiAssistantName ?? $this->title ?? 'Assistant IA',
                'endpoint' => route('ai.stream'),
            ]);
        } catch (\Throwable $e) {
            Log::error('ai-chat:send-failed', [
                'trainer_id' => $this->trainerId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function toggleAiDebug(): void
    {
        $this->showAiDebug = ! $this->showAiDebug;
    }

    public function loadMessages(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        if (! $this->trainerId) {
            $this->messages = [];
            $this->awaitingAiResponse = false;

            return;
        }

        try {
            $this->loadAiMessages($user);
        } catch (\Throwable $e) {
            Log::error('ai-chat:load-failed', [
                'contact_id' => $this->contactId,
                'error' => $e->getMessage(),
            ]);

            $this->messages = [];
            $this->awaitingAiResponse = false;
        }
    }

    public function refreshMessages(): void
    {
        if (! $this->isActive) {
            return;
        }

        $this->loadMessages();
    }

    public function activatePolling($contactId): void
    {
        if ($contactId === $this->contactId) {
            $this->isActive = true;
            $this->loadMessages();
        }
    }

    public function render()
    {
        return view('livewire.chat.ai-chat');
    }

    private function loadAiMessages($user): void
    {
        $messages = IaChatMessage::query()
            ->where('user_id', $user->id)
            ->where('trainer_id', $this->trainerId)
            ->orderBy('id')
            ->get();

        $assistantName = $this->aiAssistantName ?? $this->title ?? 'Assistant IA';

        $this->messages = $messages->map(function (IaChatMessage $message) use ($assistantName, $user) {
            $isUser = $message->role === IaChatMessage::ROLE_USER;

            return [
                'sender' => $isUser ? $user : null,
                'sender_name' => $isUser ? $user->name : $assistantName,
                'content' => $message->content,
                'created_at' => $message->created_at,
                'is_mine' => $isUser,
                'is_ai' => ! $isUser,
                'status' => $message->status,
                'status_label' => $this->formatStatusLabel($message),
            ];
        })->toArray();

        $lastMessage = $messages->last();
        $this->awaitingAiResponse = $lastMessage
            ? $lastMessage->role === IaChatMessage::ROLE_USER
                && in_array($lastMessage->status, [IaChatMessage::STATUS_PENDING, IaChatMessage::STATUS_SEEN], true)
            : false;
    }

    private function initializeContext(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $trainerId = $this->resolveTrainerId();
        if (! $trainerId) {
            return;
        }

        $trainer = AiTrainer::query()->active()->find($trainerId);
        if (! $trainer) {
            return;
        }

        $this->trainerId = $trainer->id;
        $this->aiTrainerSlug = $trainer->slug;
        $this->aiAssistantName = $trainer->name ?: 'Assistant IA';

        if (! $this->title || $this->title === 'Chat') {
            $this->title = $trainer->name ?? 'Assistant IA';
        }
    }

    private function resolveTrainerId(): ?int
    {
        $id = (int) str_replace('ai_', '', (string) $this->contactId);

        return $id > 0 ? $id : null;
    }

    private function formatStatusLabel(IaChatMessage $message): ?string
    {
        return match ($message->status) {
            IaChatMessage::STATUS_PENDING => $message->role === IaChatMessage::ROLE_USER
                ? 'En attente de l\'assistant'
                : 'Assistant en train de repondre',
            IaChatMessage::STATUS_SEEN => 'Lu par l\'assistant',
            IaChatMessage::STATUS_COMPLETED => $message->role === IaChatMessage::ROLE_ASSISTANT
                ? 'Reponse envoyee'
                : 'Reponse recue',
            IaChatMessage::STATUS_FAILED => 'Echec du traitement',
            default => null,
        };
    }
}
