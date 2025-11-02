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

    public $messagesOffset = 0;

    public $canLoadMore = false;

    public $canLoadNewer = false;

    protected $listeners = [
        'activate-chat-polling' => 'activatePolling',
    ];

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

            // Reset l'offset pour afficher les derniers messages
            $this->messagesOffset = 0;
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
            // Récupérer d'abord le nombre total de messages pour savoir s'il y en a plus
            $totalQuery = Chat::query();
            $this->applyContactFilter($totalQuery, $user);
            $totalMessages = $totalQuery->count();

            // Charger les messages avec pagination
            $query = Chat::query()
                ->with(['sender'])
                ->orderBy('created_at', 'asc');

            $this->applyContactFilter($query, $user);

            // Calculer l'offset et la limite
            $limit = 5; // Maximum 5 messages affichés
            $offset = max(0, $totalMessages - $this->messagesOffset - $limit);

            $query->offset($offset)->limit($limit);

            $chats = $query->get();

            $this->messages = $chats->map(function ($chat) use ($user) {
                return [
                    'sender' => $chat->sender,
                    'content' => $chat->content,
                    'created_at' => $chat->created_at,
                    'is_mine' => $chat->sender_user_id === $user->id,
                ];
            })->toArray();

            // Vérifier s'il y a plus de messages à charger
            $this->canLoadMore = ($offset > 0);
            $this->canLoadNewer = ($this->messagesOffset > 0 && $totalMessages > $limit);

            // Marquer les messages comme lus (seulement pour les conversations user-to-user)
            if ($this->contactType !== 'ai') {
                $this->markMessagesAsRead();
            }

        } catch (\Exception $e) {
            $this->messages = [];
            $this->canLoadMore = false;
        }
    }

    public function loadMoreMessages()
    {
        $this->messagesOffset += 5;
        $this->loadMessages();

        // Scroll vers le haut pour montrer les nouveaux messages
        $this->dispatch('scroll-to-top');
    }

    public function loadNewerMessages()
    {
        $this->messagesOffset = max(0, $this->messagesOffset - 5);
        $this->loadMessages();

        // Scroll vers le bas pour montrer les nouveaux messages
        $this->dispatch('scroll-to-bottom');
    }

    private function applyContactFilter($query, $user)
    {
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
    }

    public function activatePolling($contactId)
    {
        // Activer le polling seulement si c'est le bon contact
        if ($contactId === $this->contactId) {
            $this->isActive = true;
            // Reset l'offset pour afficher les derniers messages
            $this->messagesOffset = 0;
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
