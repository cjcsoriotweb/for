<?php

namespace App\Livewire\Chat;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UserChat extends Component
{
    public $contactId;

    public $contactType = 'user';

    public $title;

    public $messages = [];

    public $message = '';

    public $isSending = false;

    public $isActive = false;

    protected $listeners = [
        'activate-chat-polling' => 'activatePolling',
    ];

    public function mount($contactId, $contactType = 'user', $title = null): void
    {
        $this->contactId = $contactId;
        $this->contactType = $contactType;
        $this->title = $title ?: 'Chat';

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

        $this->isSending = true;

        try {
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

            $this->message = '';
            $this->loadMessages();

            $this->dispatchBrowserEvent('chat-message-sent', [
                'componentId' => $this->getId(),
            ]);
        } catch (\Throwable $e) {
            Log::error('user-chat:send-failed', [
                'contact_id' => $this->contactId,
                'contact_type' => $this->contactType,
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->isSending = false;
        }
    }

    public function loadMessages(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        try {
            $query = Chat::query()
                ->with(['sender'])
                ->orderBy('created_at', 'desc');

            $this->applyContactFilter($query, $user);

            $chats = $query->get();

            $this->messages = $chats->map(function ($chat) use ($user) {
                return [
                    'sender' => $chat->sender,
                    'sender_name' => $chat->sender?->name,
                    'content' => $chat->content,
                    'created_at' => $chat->created_at,
                    'is_mine' => $chat->sender_user_id === $user->id,
                    'is_ai' => false,
                ];
            })->toArray();

            $this->markMessagesAsRead($user);
        } catch (\Throwable $e) {
            Log::error('user-chat:load-failed', [
                'contact_id' => $this->contactId,
                'contact_type' => $this->contactType,
                'error' => $e->getMessage(),
            ]);

            $this->messages = [];
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
        return view('livewire.chat.user-chat');
    }

    private function applyContactFilter($query, $user): void
    {
        if ($this->contactType === 'user') {
            $receiverId = (int) str_replace('user_', '', $this->contactId);
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
            $receiverId = (int) str_replace('admin_', '', $this->contactId);
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

    private function markMessagesAsRead($user): void
    {
        $query = Chat::query();

        if ($this->contactType === 'user') {
            $senderId = (int) str_replace('user_', '', $this->contactId);
            $query->where('sender_user_id', $senderId)
                ->where('receiver_user_id', $user->id);
        } elseif ($this->contactType === 'admin') {
            $senderId = (int) str_replace('admin_', '', $this->contactId);
            $query->where('sender_user_id', $senderId)
                ->where('receiver_user_id', $user->id);
        }

        $query->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }
}
