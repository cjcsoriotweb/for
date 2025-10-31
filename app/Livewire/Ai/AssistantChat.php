<?php

namespace App\Livewire\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Services\Ai\AiConversationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

class AssistantChat extends Component
{
    public ?int $trainerId = null;

    public ?int $conversationId = null;

    /** @var array<int, array<string, mixed>> */
    public array $messages = [];

    public string $message = '';

    public string $trainerName = '';

    public ?string $trainerDescription = null;

    public ?string $trainerAvatar = null;

    public bool $hasTrainer = false;

    public bool $awaitingResponse = false;

    public ?string $error = null;

    public function mount(?int $trainerId = null): void
    {
        $this->trainerId = $trainerId;
        $this->hydrateConversation();
    }

    public function sendMessage(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->error = 'Vous devez être connecté pour utiliser l\'assistant IA.';

            return;
        }

        $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'message' => __('Message'),
        ]);

        $this->error = null;

        if (! $this->hasTrainer || ! $this->conversationId) {
            $this->error = __('L\'assistant IA est indisponible.');

            return;
        }

        if ($this->awaitingResponse) {
            return;
        }

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
                $this->message,
                $user,
                $context
            );

            $oldMessage = $this->message;
            $this->message = '';

            $this->awaitingResponse = true;

            // Clear any errors before generating response
            $this->error = null;

            $this->service()->generateAssistantReply($conversation, $trainer);
        } catch (Throwable $exception) {
            $this->error = 'Erreur: '.$exception->getMessage();
            $this->awaitingResponse = false;
        } finally {
            $this->refreshMessages();
            $this->awaitingResponse = false;
        }
    }

    public $listeners = ['messageReceived' => 'refreshMessages'];

    public function poll()
    {
        $this->refreshMessages();
    }

    public function render()
    {
        return view('livewire.ai.assistant-chat');
    }

    private function hydrateConversation(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->messages = [];
            $this->conversationId = null;
            $this->hasTrainer = false;
            $this->awaitingResponse = false;
            $this->error = 'Vous devez être connecté pour utiliser l\'assistant IA.';

            return;
        }

        $trainer = $this->service()->resolveTrainer(null, $this->trainerId);

        if (! $trainer) {
            $this->hasTrainer = false;
            $this->messages = [];
            $this->conversationId = null;
            $this->trainerId = null;
            $this->trainerName = '';
            $this->trainerDescription = null;
            $this->trainerAvatar = null;
            $this->awaitingResponse = false;
            $this->error = 'Aucun assistant IA n\'est configuré. Veuillez contacter l\'administrateur.';

            return;
        }

        try {
            $conversation = $this->service()->getOrCreateConversation(
                $trainer,
                $user,
                null, // no formation
                $user->currentTeam
            );

            $this->hasTrainer = true;
            $this->conversationId = $conversation->id;
            $this->trainerId = $trainer->id;
            $this->trainerName = $trainer->name;
            $this->trainerDescription = $trainer->description;
            $this->trainerAvatar = $trainer->avatar_path ?: 'images/ai-trainer-placeholder.svg';
            $this->error = null;

            $this->refreshMessages();
        } catch (\Throwable $exception) {
            $this->hasTrainer = false;
            $this->error = 'Erreur lors de la création de la conversation: '.$exception->getMessage();
        }
    }

    private function refreshMessages(): void
    {
        if (! $this->conversationId) {
            $this->messages = [];

            return;
        }

        $conversation = AiConversation::query()->find($this->conversationId);

        if (! $conversation) {
            $this->messages = [];

            return;
        }

        $orderedMessages = $conversation->messages()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $this->messages = $orderedMessages->map(function (AiConversationMessage $message) {
            $author = match ($message->role) {
                AiConversationMessage::ROLE_ASSISTANT => $this->trainerName,
                AiConversationMessage::ROLE_SYSTEM => 'Systeme',
                default => __('Vous'),
            };

            return [
                'id' => $message->id,
                'role' => $message->role,
                'author' => $author,
                'content' => $message->content,
                'created_at' => optional($message->created_at)->toIso8601String(),
                'created_at_human' => optional($message->created_at)->diffForHumans(),
            ];
        })->all();
    }

    private function service(): AiConversationService
    {
        return app(AiConversationService::class);
    }
}
