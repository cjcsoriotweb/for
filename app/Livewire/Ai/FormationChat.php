<?php

namespace App\Livewire\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Services\Ai\AiConversationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class FormationChat extends Component
{
    public bool $isOpen = false;

    public ?int $formationId = null;
    public ?int $trainerId = null;
    public ?int $conversationId = null;

    /** @var array<int, array<string, mixed>> */
    public array $messages = [];

    public string $message = '';

    public string $trainerName = '';
    public ?string $trainerDescription = null;
    public ?string $trainerAvatar = null;

    public ?string $formationTitle = null;

    public ?string $error = null;

    public function mount(?int $formationId = null, ?int $trainerId = null, bool $open = false): void
    {
        $this->formationId = $formationId;
        $this->trainerId = $trainerId;
        $this->isOpen = $open;

        $this->hydrateConversation();
    }

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function sendMessage(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'message' => __('Message'),
        ]);

        $this->error = null;

        try {
            $conversation = AiConversation::query()->findOrFail($this->conversationId);
            $trainer = AiTrainer::query()->findOrFail($conversation->ai_trainer_id);
            $formation = $this->formationId ? Formation::query()->find($this->formationId) : null;

            $context = [
                'label' => $this->formationTitle
                    ? Str::limit($this->formationTitle, 120, '')
                    : 'Formation',
                'path' => url()->current(),
                'formation_id' => $formation?->id,
            ];

            $this->service()->appendMessage(
                $conversation,
                AiConversationMessage::ROLE_USER,
                $this->message,
                $user,
                $context
            );

            $this->message = '';

            $this->service()->generateAssistantReply($conversation, $trainer);
        } catch (Throwable $exception) {
            $this->error = $exception->getMessage();
        } finally {
            $this->refreshMessages();
        }
    }

    public function render()
    {
        return view('livewire.ai.formation-chat');
    }

    private function hydrateConversation(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->messages = [];
            $this->conversationId = null;

            return;
        }

        $formation = $this->formationId ? Formation::query()->find($this->formationId) : null;
        $trainer = $this->service()->resolveTrainer($formation, $this->trainerId);

        $conversation = $this->service()->getOrCreateConversation(
            $trainer,
            $user,
            $formation,
            $user->currentTeam
        );

        $this->conversationId = $conversation->id;
        $this->trainerId = $trainer->id;
        $this->trainerName = $trainer->name;
        $this->trainerDescription = $trainer->description;
        $this->trainerAvatar = $trainer->avatar_path;
        $this->formationTitle = $formation?->title;

        $this->refreshMessages();
    }

    private function refreshMessages(): void
    {
        if (! $this->conversationId) {
            $this->messages = [];

            return;
        }

        $conversation = AiConversation::query()
            ->with(['messages' => fn ($query) => $query->orderBy('created_at')])
            ->find($this->conversationId);

        if (! $conversation) {
            $this->messages = [];

            return;
        }

        $this->messages = $conversation->messages->map(function (AiConversationMessage $message) {
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
