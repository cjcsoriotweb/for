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

    public function mount($contactId, $contactType = 'ai', $title = null)
    {
        $this->contactId = $contactId;
        $this->contactType = $contactType;
        $this->title = $title ?: 'Chat';

        $this->loadMessages();
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
                $chatData['receiver_ia_id'] = str_replace('ai_', '', $this->contactId);
            } elseif ($this->contactType === 'user') {
                $chatData['receiver_user_id'] = str_replace('user_', '', $this->contactId);
            } elseif ($this->contactType === 'admin') {
                $chatData['receiver_user_id'] = str_replace('admin_', '', $this->contactId);
            }

            Chat::create($chatData);

            // Ajouter le message à la liste
            $this->messages[] = [
                'sender' => $user,
                'content' => $text,
                'created_at' => now(),
                'is_mine' => true,
            ];

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
                ->with(['sender'])
                ->orderBy('created_at', 'desc')
                ->limit(50);

            // Filtrer selon le type de contact
            if ($this->contactType === 'ai') {
                $aiId = str_replace('ai_', '', $this->contactId);
                $query->where(function ($q) use ($user, $aiId) {
                    $q->where(function ($subQ) use ($user, $aiId) {
                        $subQ->where('sender_user_id', $user->id)
                            ->where('receiver_ia_id', $aiId);
                    })->orWhere(function ($subQ) use ($user, $aiId) {
                        $subQ->where('sender_user_id', $aiId)
                            ->where('receiver_user_id', $user->id);
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

            $chats = $query->get()->reverse()->values();

            $this->messages = $chats->map(function ($chat) use ($user) {
                return [
                    'sender' => $chat->sender,
                    'content' => $chat->content,
                    'created_at' => $chat->created_at,
                    'is_mine' => $chat->sender_user_id === $user->id,
                ];
            })->toArray();

        } catch (\Exception $e) {
            $this->messages = [];
        }
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
