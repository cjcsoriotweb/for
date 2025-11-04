<?php

namespace App\Livewire;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ChatBox extends Component
{
    public $contactId;

    public $contactType; // 'ai', 'user', 'admin'

    public $title;

    public $messages = [];

    public $message = '';

    public $isSending = false;

    public $isActive = false;

    public $awaitingAiResponse = false;

    public ?int $aiConversationId = null;

    public ?string $aiTrainerSlug = null;

    public ?string $aiAssistantName = null;

    public bool $showAiDebug = false;

    protected $listeners = [
        'activate-chat-polling' => 'activatePolling',
    ];

    public function mount($contactId, $contactType = 'ai', $title = null)
    {
        $this->contactId = $contactId;
        $this->contactType = $contactType;
        $this->title = $title ?: 'Chat';

        if ($this->isAiChat()) {
            $this->initializeAiConversation();
            $this->showAiDebug = config('app.debug', false);
        }

        $this->loadMessages();
        $this->isActive = true;
    }

    public function sendMessage()
    {
        $text = trim($this->message);
        if ($text === '') {
            return;
        }

        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->isSending = true;

        try {
            if ($this->isAiChat()) {
                return $this->handleAiSend($text);
            }

            $chatData = [
                'sender_user_id' => $user->id,
                'content' => $text,
            ];

            if ($this->contactType === 'user') {
                $chatData['receiver_user_id'] = (int) str_replace('user_', '', $this->contactId);
            } elseif ($this->contactType === 'admin') {
                $chatData['receiver_user_id'] = (int) str_replace('admin_', '', $this->contactId);
            }

            Chat::create($chatData);

            $this->loadMessages();
            $this->message = '';
            $this->dispatchBrowserEvent('chat-message-sent', [
                'componentId' => $this->getId(),
            ]);
        } catch (\Exception $e) {
            Log::error('chatbox:send-message-failed', [
                'contact_id' => $this->contactId,
                'contact_type' => $this->contactType,
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->isSending = false;
        }
    }

    private function handleAiSend(string $text): void
    {
        if (! $this->aiConversationId || ! $this->aiTrainerSlug) {
            $this->initializeAiConversation();
        }

        if (! $this->aiConversationId || ! $this->aiTrainerSlug) {
            return;
        }

        // Persist user message in the IA conversation
        AiConversationMessage::query()->create([
            'conversation_id' => $this->aiConversationId,
            'user_id' => Auth::id(),
            'role' => AiConversationMessage::ROLE_USER,
            'content' => $text,
        ]);

        $this->message = '';
        $this->awaitingAiResponse = true;
        $this->loadMessages();

        $this->dispatchBrowserEvent('chat-message-sent', [
            'componentId' => $this->getId(),
        ]);

        $this->dispatchBrowserEvent('start-ai-stream', [
            'componentId' => $this->getId(),
            'conversationId' => $this->aiConversationId,
            'trainerSlug' => $this->aiTrainerSlug,
            'message' => $text,
            'assistantName' => $this->aiAssistantName ?? $this->title ?? 'Assistant IA',
            'endpoint' => route('ai.stream'),
        ]);
    }

    public function loadMessages()
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        try {
            if ($this->isAiChat()) {
                $this->loadAiMessages($user);
            } else {
                $this->loadUserMessages($user);
            }
        } catch (\Exception $e) {
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

    private function applyContactFilter($query, $user)
    {
        if ($this->isAiChat()) {
            return;
        }

        if ($this->contactType === 'user') {
            $receiverId = str_replace('user_', '', $this->contactId);
            $query->where(function ($q) use ($user, $receiverId) {
                $q->where(function ($subQ) use ($user, $receiverId) {
                    $subQ->where('sender_user_id', $user->id)
                        ->where('receiver_user_id', $receiverId);
                })->orWhere(function ($subQ) use ($user, $receiverId) {
                    $subQ->where('sender_user_id', $receiverId)
                        ->where('receiver_user_id', $user->id);
                });
            });
        } elseif ($this->contactType === 'admin') {
            $receiverId = str_replace('admin_', '', $this->contactId);
            $query->where(function ($q) use ($user, $receiverId) {
                $q->where(function ($subQ) use ($user, $receiverId) {
                    $subQ->where('sender_user_id', $user->id)
                        ->where('receiver_user_id', $receiverId);
                })->orWhere(function ($subQ) use ($user, $receiverId) {
                    $subQ->where('sender_user_id', $receiverId)
                        ->where('receiver_user_id', $user->id);
                });
            });
        }
    }

    public function activatePolling($contactId)
    {
        // Activer le polling seulement si c'est le bon contact
        if ($contactId === $this->contactId) {
            $this->isActive = true;
            $this->loadMessages();
        }
    }

    public function markMessagesAsRead()
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        try {
            $query = Chat::query();

            // Déterminer le type de destinataire selon le contact
            if ($this->contactType === 'user') {
                $senderId = str_replace('user_', '', $this->contactId);
                $query->where('sender_user_id', $senderId)
                    ->where('receiver_user_id', $user->id);
            } elseif ($this->contactType === 'admin') {
                $senderId = str_replace('admin_', '', $this->contactId);
                $query->where('sender_user_id', $senderId)
                    ->where('receiver_user_id', $user->id);
            }

            // Marquer comme lus
            $query->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);

        } catch (\Exception $e) {
            // Ne pas interrompre le processus si le marquage échoue
        }
    }

    public function render()
    {
        return view('livewire.chat-box');
    }

    public function toggleAiDebug(): void
    {
        if (! $this->isAiChat()) {
            return;
        }

        $this->showAiDebug = ! $this->showAiDebug;
    }

    private function loadAiMessages($user): void
    {
        if (! $this->aiConversationId) {
            $this->messages = [];
            $this->awaitingAiResponse = false;

            return;
        }

        $messages = AiConversationMessage::query()
            ->where('conversation_id', $this->aiConversationId)
            ->orderBy('id')
            ->get();

        $assistantName = $this->aiAssistantName ?? $this->title ?? 'Assistant IA';

        $this->messages = $messages->map(function (AiConversationMessage $message) use ($assistantName, $user) {
            $isUser = $message->role === AiConversationMessage::ROLE_USER;

            return [
                'sender' => $isUser ? $user : null,
                'sender_name' => $isUser ? $user->name : $assistantName,
                'content' => $message->content,
                'created_at' => $message->created_at,
                'is_mine' => $isUser,
                'is_ai' => ! $isUser,
            ];
        })->toArray();

        $lastMessage = $messages->last();
        $this->awaitingAiResponse = $lastMessage ? $lastMessage->role === AiConversationMessage::ROLE_USER : false;
    }

    private function loadUserMessages($user): void
    {
        $query = Chat::query()
            ->with(['sender', 'senderIa'])
            ->orderBy('created_at', 'desc');

        $this->applyContactFilter($query, $user);

        $chats = $query->get();

        $this->messages = $chats->map(function ($chat) use ($user) {
            $sender = $chat->senderIa ?: $chat->sender;

            return [
                'sender' => $sender,
                'sender_name' => $sender?->name,
                'content' => $chat->content,
                'created_at' => $chat->created_at,
                'is_mine' => $chat->sender_user_id === $user->id && $chat->sender_ia_id === null,
                'is_ai' => $chat->sender_ia_id !== null,
            ];
        })->toArray();

        $this->awaitingAiResponse = false;

        $this->markMessagesAsRead();
    }

    private function initializeAiConversation(): void
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

        $this->aiTrainerSlug = $trainer->slug;
        $this->aiAssistantName = $trainer->name ?: 'Assistant IA';

        if (! $this->title || $this->title === 'Chat') {
            $this->title = $trainer->name ?? 'Assistant IA';
        }

        $conversation = AiConversation::query()
            ->where('user_id', $user->id)
            ->where('metadata->trainer', $trainer->slug)
            ->orderByDesc('id')
            ->first();

        if (! $conversation) {
            $conversation = AiConversation::query()->create([
                'user_id' => $user->id,
                'team_id' => $user->currentTeam?->id,
                'status' => AiConversation::STATUS_ACTIVE,
                'metadata' => [
                    'trainer' => $trainer->slug,
                    'model' => $trainer->model ?: config('ai.default_model'),
                ],
                'last_message_at' => now(),
            ]);
        }

        $this->aiConversationId = $conversation->id;
    }

    private function resolveTrainerId(): ?int
    {
        if (! $this->isAiChat()) {
            return null;
        }

        $id = (int) str_replace('ai_', '', (string) $this->contactId);

        return $id > 0 ? $id : null;
    }

    private function isAiChat(): bool
    {
        return $this->contactType === 'ai';
    }
}
