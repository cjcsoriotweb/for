<?php

namespace App\Livewire;

use App\Models\AiTrainer;
use App\Models\Chat;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class ChatAndIAMenu extends Component
{
    public bool $drawer = false;

    public ?string $active = null;

    public ?string $drawerTab = null;

    public $notifications = 0;

    public $enable = true;

    public $locked = false;

    public string $addContactEmail = '';

    public ?string $addContactError = null;

    public ?string $addContactSuccess = null;

    public array $manualContactIds = [];

    protected $listeners = [
        'launchAssistant' => 'onLaunchAssistant',
    ];

    public function mount($notifications = 0, $enable = true, $locked = false)
    {
        $this->notifications = $notifications;
        $this->enable = $enable;
        $this->locked = $locked;
    }

    public function onLaunchAssistant($slug)
    {
        $this->drawer = true;
        $this->drawerTab = 'ia';
        $this->active = Str::start($slug, 'ai_');
    }

    public function closeChat()
    {
        $this->active = null;
    }

    public function toggleDrawer(?string $tab = null)
    {
        if ($tab === null) {
            if ($this->drawer) {
                $this->resetDrawerState();
            } else {
                $this->drawer = true;
            }

            return;
        }

        if ($this->drawer && $this->drawerTab === $tab) {
            $this->resetDrawerState();

            return;
        }

        $this->drawer = true;
        $this->drawerTab = $tab;

        if ($tab === 'notifications') {
            $this->active = null;
            $this->markNotificationsAsRead();
        } elseif ($tab === 'ia' && ! str_starts_with((string) $this->active, 'ai_')) {
            $this->active = null;
        } elseif ($tab === 'contacts' && str_starts_with((string) $this->active, 'ai_')) {
            $this->active = null;
        }
    }

    public function selectContact($contactId)
    {
        $this->active = $contactId;
        $this->drawer = true;
        $this->drawerTab = str_starts_with($contactId, 'ai_') ? 'ia' : 'contacts';

        // Activer le polling pour le chat actif
        $this->dispatch('activate-chat-polling', contactId: $contactId);
    }

    public function closeDrawer()
    {
        $this->resetDrawerState();
    }

    protected function resetDrawerState(): void
    {
        $this->drawer = false;
        $this->drawerTab = null;
        $this->active = null;
    }

    protected function updateNotificationCounter(bool $refreshUnread = false): void
    {
        if ($refreshUnread) {
            unset($this->unreadNotificationsCount, $this->userNotifications, $this->formattedNotifications);
        }

        if (! Auth::check()) {
            $this->notifications = 0;

            return;
        }

        $this->notifications = $this->unreadNotificationsCount;
    }

    protected function markNotificationsAsRead(): void
    {
        if (! Auth::check()) {
            return;
        }

        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        unset($this->userNotifications, $this->formattedNotifications, $this->unreadNotificationsCount);

        $this->updateNotificationCounter(true);
    }

    public function markNotificationAsRead(string $notificationId): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        $notification = $user->notifications()
            ->where('id', $notificationId)
            ->first();

        if (! $notification) {
            return;
        }

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        $notification->delete();

        unset($this->userNotifications, $this->formattedNotifications, $this->unreadNotificationsCount);

        $this->updateNotificationCounter(true);
    }

    public function loadPendingContacts()
    {
        // Forcer le rechargement des pending contacts
        unset($this->pendingContacts);
    }

    public function refreshNotifications(): void
    {
        $this->updateNotificationCounter(true);
    }

    public function markAllNotificationsAsRead(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        unset($this->userNotifications, $this->formattedNotifications, $this->unreadNotificationsCount);

        $this->updateNotificationCounter(true);
    }

    public function addContactByEmail(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->addContactError = null;
        $this->addContactSuccess = null;

        $email = trim($this->addContactEmail);

        if ($email === '') {
            $this->addContactError = 'Veuillez saisir une adresse e-mail.';

            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addContactError = 'Adresse e-mail invalide.';

            return;
        }

        $currentUser = Auth::user();

        $contact = User::query()->where('email', $email)->first();

        if (! $contact) {
            $this->addContactError = 'Aucun utilisateur trouvé avec cette adresse e-mail.';

            return;
        }

        if ((int) $contact->id === (int) $currentUser->id) {
            $this->addContactError = 'Vous ne pouvez pas vous ajouter vous-même.';

            return;
        }

        if (! in_array($contact->id, $this->manualContactIds, true)) {
            $this->manualContactIds[] = $contact->id;
        }

        $contactType = $contact->superadmin ? 'admin' : 'user';
        $contactId = $contactType.'_'.$contact->id;

        $this->addContactSuccess = sprintf('%s a été ajouté à vos contacts.', $contact->name ?? $email);
        $this->addContactEmail = '';

        $this->selectContact($contactId);
    }

    public function getTrainersProperty()
    {
        if (! $this->enable || ! Auth::check()) {
            return collect();
        }

        $trainers = AiTrainer::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Ajouter le trainer de la formation si contexte formation
        $currentRoute = request()->route();
        if ($currentRoute && str_contains($currentRoute->getName(), 'formation')) {
            $formationParam = $currentRoute->parameter('formation');
            $formation = null;
            if ($formationParam) {
                if (is_numeric($formationParam)) {
                    $formation = Formation::find($formationParam);
                } elseif ($formationParam instanceof Formation) {
                    $formation = $formationParam;
                } elseif (is_object($formationParam) && property_exists($formationParam, 'id')) {
                    $formation = Formation::find($formationParam->id);
                } elseif (is_array($formationParam) && isset($formationParam['id'])) {
                    $formation = Formation::find($formationParam['id']);
                }
            }
            if ($formation && $formation->category?->aiTrainer) {
                $trainers->prepend($formation->category->aiTrainer);
            }
        }

        return $trainers->unique('id')->values();
    }

    public function getFormationUsersProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        $user = Auth::user();
        $currentRoute = request()->route();

        // Chercher la formation actuelle
        if ($currentRoute && str_contains($currentRoute->getName(), 'formation')) {
            $formationParam = $currentRoute->parameter('formation');
            $formation = null;

            if ($formationParam) {
                if (is_numeric($formationParam)) {
                    $formation = Formation::find($formationParam);
                } elseif ($formationParam instanceof Formation) {
                    $formation = $formationParam;
                } elseif (is_object($formationParam) && property_exists($formationParam, 'id')) {
                    $formation = Formation::find($formationParam->id);
                } elseif (is_array($formationParam) && isset($formationParam['id'])) {
                    $formation = Formation::find($formationParam['id']);
                }
            }

            if ($formation) {
                // Récupérer les utilisateurs de cette formation
                return $formation->users()
                    ->where('users.id', '!=', $user->id) // Exclure l'utilisateur actuel
                    ->orderBy('name')
                    ->get();
            }
        }

        return collect();
    }

    public function getSuperAdminsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        $user = Auth::user();

        // Récupérer les superadmin
        return User::where('superadmin', 1)
            ->where('id', '!=', $user->id) // Exclure l'utilisateur actuel
            ->orderBy('name')
            ->get();
    }

    public function getPendingContactsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        $user = Auth::user();

        // Récupérer les utilisateurs qui ont envoyé des messages non lus
        return User::whereHas('sentChats', function ($query) use ($user) {
            $query->where('receiver_user_id', $user->id)
                ->where('is_read', false);
        })
            ->where('id', '!=', $user->id)
            ->withCount(['sentChats as unread_count' => function ($query) use ($user) {
                $query->where('receiver_user_id', $user->id)
                    ->where('is_read', false);
            }])
            ->with(['sentChats' => function ($query) use ($user) {
                $query->where('receiver_user_id', $user->id)
                    ->where('is_read', false)
                    ->latest()
                    ->limit(1);
            }])
            ->get()
            ->map(function ($contact) {
                $latestMessage = $contact->sentChats->first();
                $contact->latest_message = $latestMessage;
                $contact->chat_contact_type = $contact->superadmin ? 'admin' : 'user';
                $contact->chat_contact_id = $contact->chat_contact_type.'_'.$contact->id;

                return $contact;
            })
            ->sortByDesc(function ($contact) {
                return optional($contact->latest_message)->created_at;
            })
            ->values();
    }

    public function getConversationContactsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        $user = Auth::user();

        $conversationIds = Chat::query()
            ->selectRaw('CASE WHEN sender_user_id = ? THEN receiver_user_id ELSE sender_user_id END AS contact_id', [$user->id])
            ->where(function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->where('sender_user_id', $user->id)
                        ->whereNotNull('receiver_user_id');
                })->orWhere(function ($subQuery) use ($user) {
                    $subQuery->where('receiver_user_id', $user->id)
                        ->whereNotNull('sender_user_id');
                });
            })
            ->whereNull('sender_ia_id')
            ->pluck('contact_id')
            ->filter()
            ->unique()
            ->values();

        if ($conversationIds->isEmpty()) {
            return collect();
        }

        $recentMessages = Chat::query()
            ->where(function ($query) use ($user, $conversationIds) {
                $query->where(function ($subQuery) use ($user, $conversationIds) {
                    $subQuery->where('sender_user_id', $user->id)
                        ->whereIn('receiver_user_id', $conversationIds);
                })->orWhere(function ($subQuery) use ($user, $conversationIds) {
                    $subQuery->whereIn('sender_user_id', $conversationIds)
                        ->where('receiver_user_id', $user->id);
                });
            })
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function (Chat $chat) use ($user) {
                return $chat->sender_user_id === $user->id
                    ? $chat->receiver_user_id
                    : $chat->sender_user_id;
            })
            ->map(function ($group) {
                return $group->first();
            });

        $excludedIds = collect()
            ->merge($this->pendingContacts->pluck('id'))
            ->merge($this->formationUsers->pluck('id'))
            ->merge($this->superAdmins->pluck('id'))
            ->unique();

        return User::query()
            ->whereIn('id', $conversationIds)
            ->get()
            ->reject(function ($contact) use ($excludedIds) {
                return $excludedIds->contains($contact->id);
            })
            ->map(function (User $contact) use ($recentMessages) {
                $latest = $recentMessages->get($contact->id);
                $contact->latest_message = $latest;
                $contact->latest_message_at = optional($latest)->created_at;
                $contact->chat_contact_type = $contact->superadmin ? 'admin' : 'user';
                $contact->chat_contact_id = $contact->chat_contact_type.'_'.$contact->id;

                return $contact;
            })
            ->sortByDesc(function (User $contact) {
                return optional($contact->latest_message_at)->getTimestamp() ?? 0;
            })
            ->values();
    }

    public function getManualContactsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        $ids = collect($this->manualContactIds ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $excludedIds = collect()
            ->merge($this->pendingContacts->pluck('id'))
            ->merge($this->formationUsers->pluck('id'))
            ->merge($this->superAdmins->pluck('id'))
            ->merge($this->conversationContacts->pluck('id'));

        return User::query()
            ->whereIn('id', $ids)
            ->get()
            ->reject(function (User $contact) use ($excludedIds) {
                return $excludedIds->contains($contact->id);
            })
            ->map(function (User $contact) {
                $contact->latest_message = null;
                $contact->latest_message_at = null;
                $contact->chat_contact_type = $contact->superadmin ? 'admin' : 'user';
                $contact->chat_contact_id = $contact->chat_contact_type.'_'.$contact->id;

                return $contact;
            })
            ->values();
    }

    public function getAllChatUsersProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        $user = Auth::user();

        // Récupérer tous les utilisateurs qui peuvent être contactés
        // Combinaison de formationUsers, superAdmins et pendingContacts
        $allUsers = collect();

        // Ajouter les utilisateurs de formation
        $this->formationUsers->each(function ($formationUser) use (&$allUsers) {
            $allUsers->push($formationUser);
        });

        // Ajouter les superadmin
        $this->superAdmins->each(function ($admin) use (&$allUsers) {
            $allUsers->push($admin);
        });

        // Ajouter les pending contacts (utilisateurs qui ont envoyé des messages non lus)
        $this->pendingContacts->each(function ($pendingUser) use (&$allUsers) {
            // Éviter les doublons
            if (! $allUsers->contains('id', $pendingUser->id)) {
                $allUsers->push($pendingUser);
            }
        });
        $this->manualContacts->each(function ($manualContact) use (&$allUsers) {
            if (! $allUsers->contains('id', $manualContact->id)) {
                $allUsers->push($manualContact);
            }
        });

        $this->conversationContacts->each(function ($contact) use (&$allUsers) {
            if (! $allUsers->contains('id', $contact->id)) {
                $allUsers->push($contact);
            }
        });

        return $allUsers->unique('id')->values();
    }

    public function getAllContactsProperty()
    {
        $contacts = collect();

        // Ajouter les IA
        $this->trainers->each(function ($trainer) use (&$contacts) {
            $contacts->push([
                'id' => 'ai_'.$trainer->id,
                'type' => 'ai',
                'name' => $trainer->name,
                'description' => $trainer->description ?? '',
                'slug' => $trainer->slug,
                'avatar' => null,
            ]);
        });

        // Ajouter les utilisateurs de la formation
        $this->formationUsers->each(function ($user) use (&$contacts) {
            $contacts->push([
                'id' => 'user_'.$user->id,
                'type' => 'user',
                'name' => $user->name,
                'description' => 'Participant à la formation',
                'slug' => null,
                'avatar' => $user->profile_photo_path,
            ]);
        });

        // Ajouter les superadmin
        $this->superAdmins->each(function ($admin) use (&$contacts) {
            $contacts->push([
                'id' => 'admin_'.$admin->id,
                'type' => 'admin',
                'name' => $admin->name,
                'description' => 'Administrateur',
                'slug' => null,
                'avatar' => $admin->profile_photo_path,
            ]);
        });

        return $contacts;
    }

    public function getUserNotificationsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        return Auth::user()->notifications()
            ->latest()
            ->limit(20)
            ->get();
    }

    public function getFormattedNotificationsProperty()
    {
        return $this->userNotifications->map(function ($notification) {
            $data = $notification->data ?? [];
            if (! is_array($data)) {
                $data = (array) $data;
            }

            $type = $data['type'] ?? null;
            $title = $data['title'] ?? ($data['subject'] ?? null);

            if (! $title) {
                $title = Str::headline(class_basename($notification->type ?? 'Notification'));
            }

            $message = $data['message'] ?? ($data['body'] ?? null);
            if (($type ?? '') === 'chat_ai_reply') {
                $assistantName = $data['assistant_name'] ?? 'Assistant IA';

                if (! $title) {
                    $title = sprintf('Reponse de %s', $assistantName);
                }

                if (! $message) {
                    $excerpt = $data['assistant_message_excerpt'] ?? null;
                    $message = $excerpt
                        ? sprintf('"%s"', $excerpt)
                        : 'Un assistant IA vous a repondu.';
                }
            }

            return (object) [
                'id' => $notification->id,
                'type' => $type ?? $notification->type,
                'title' => $title,
                'message' => $message,
                'created_at' => $notification->created_at,
                'is_read' => (bool) $notification->read_at,
                'contact' => $data['contact'] ?? null,
                'payload' => $data,
            ];
        });
    }

    public function getUnreadNotificationsCountProperty()
    {
        if (! Auth::check()) {
            return 0;
        }

        return Auth::user()->unreadNotifications()->count();
    }

    public function getPendingTotalUnreadProperty()
    {
        return $this->pendingContacts->sum('unread_count');
    }

    public function render()
    {
        $this->updateNotificationCounter();

        return view('livewire.chat-and-ia-menu');
    }
}
