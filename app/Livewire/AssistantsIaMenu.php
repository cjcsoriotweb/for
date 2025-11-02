<?php

namespace App\Livewire;

use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssistantsIaMenu extends Component
{
    public bool $drawer = false;

    public ?string $active = null;

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
        $this->active = $slug;
    }

    public function closeChat()
    {
        $this->active = null;
    }

    public function toggleDrawer()
    {
        $this->drawer = ! $this->drawer;
        if (! $this->drawer) {
            $this->active = null;
        }
    }

    public function selectContact($contactId)
    {
        $this->active = $contactId;
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

        // Récupérer les superadmin (membres d'équipes avec role superadmin)
        return User::whereHas('teams', function ($teamQuery) {
            $teamQuery->where('role', 'superadmin');
        })
            ->where('id', '!=', $user->id) // Exclure l'utilisateur actuel
            ->orderBy('name')
            ->get();
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

    public function render()
    {
        return view('livewire.assistants-ia-menu');
    }
}
