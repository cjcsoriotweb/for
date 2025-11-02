<?php

namespace App\Livewire;

use App\Models\AiTrainer;
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

    protected function markNotificationsAsRead(): void
    {
        if (! Auth::check()) {
            return;
        }

        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->notifications = 0;
    }

    public function loadPendingContacts()
    {
        // Forcer le rechargement des pending contacts
        unset($this->pendingContacts);
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
            if ($formation && $formation->primaryTrainer) {
                $trainers->prepend($formation->primaryTrainer);
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
            $title = $data['title'] ?? ($data['subject'] ?? null);

            if (! $title) {
                $title = Str::headline(class_basename($notification->type ?? 'Notification'));
            }

            $message = $data['message'] ?? ($data['body'] ?? null);

            return (object) [
                'id' => $notification->id,
                'title' => $title,
                'message' => $message,
                'created_at' => $notification->created_at,
                'is_read' => (bool) $notification->read_at,
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
        if (Auth::check()) {
            $this->notifications = $this->unreadNotificationsCount;
        } else {
            $this->notifications = 0;
        }

        return view('livewire.chat-and-ia-menu');
    }
}
