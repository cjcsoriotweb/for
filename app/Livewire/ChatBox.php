<?php

namespace App\Livewire;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
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

    protected $listeners = [
        'activate-chat-polling' => 'activatePolling',
    ];

    public function mount($contactId, $contactType = 'ai', $title = null)
    {
        $this->contactId = $contactId;
        $this->contactType = $contactType;
        $this->title = $title ?: 'Chat';

        $this->loadMessages();
        $this->isActive = true;
    }

    public function sendMessage()
    {
        $text = trim($this->message);
        if (empty($text)) {
            return;
        }

        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->isSending = true;

        try {
            // Créer le message dans la base de données
            $chatData = [
                'sender_user_id' => $user->id,
                'content' => $text,
            ];

            // Déterminer le destinataire selon le type
            if ($this->contactType === 'ai') {
                $chatData['receiver_ia_id'] = (int) str_replace('ai_', '', $this->contactId);
            } elseif ($this->contactType === 'user') {
                $chatData['receiver_user_id'] = (int) str_replace('user_', '', $this->contactId);
            } elseif ($this->contactType === 'admin') {
                $chatData['receiver_user_id'] = (int) str_replace('admin_', '', $this->contactId);
            }

            Chat::create($chatData);

            $this->loadMessages();
            $this->message = '';

            // Scroll vers le bas
            $this->dispatch('scroll-to-bottom');

        } catch (\Exception $e) {
            // Gérer l'erreur silencieusement
        } finally {
            $this->isSending = false;
        }
    }

    public function loadMessages()
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        try {
            $query = Chat::query()
                ->with(['sender', 'senderIa'])
                ->orderBy('created_at', 'desc');

            $this->applyContactFilter($query, $user);

            $chats = $query->get();

            $this->messages = $chats->map(function ($chat) use ($user) {
                return [
                    'sender' => $chat->senderIa ?: $chat->sender,
                    'content' => $chat->content,
                    'created_at' => $chat->created_at,
                    'is_mine' => $chat->sender_user_id === $user->id && $chat->sender_ia_id === null,
                    'is_ai' => $chat->sender_ia_id !== null,
                ];
            })->toArray();

            // Marquer les messages comme lus (seulement pour les conversations user-to-user)
            if ($this->contactType !== 'ai') {
                $this->markMessagesAsRead();
            }

        } catch (\Exception $e) {
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

    private function applyContactFilter($query, $user)
    {
        // Filtrer selon le type de contact
        if ($this->contactType === 'ai') {
            $aiId = (int) str_replace('ai_', '', $this->contactId);
            $query->where(function ($q) use ($user, $aiId) {
                $q->where(function ($subQ) use ($user, $aiId) {
                    $subQ->where('sender_user_id', $user->id)
                        ->where('receiver_ia_id', $aiId);
                })->orWhere(function ($subQ) use ($user, $aiId) {
                    $subQ->where('receiver_user_id', $user->id)
                        ->where('sender_ia_id', $aiId);
                });
            });
        } elseif ($this->contactType === 'user') {
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
}
