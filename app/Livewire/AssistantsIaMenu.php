<?php

namespace App\Livewire;

use App\Models\AiTrainer;
use App\Models\Formation;
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
        $this->drawer = !$this->drawer;
        if (!$this->drawer) {
            $this->active = null;
        }
    }

    public function selectTrainer($slug)
    {
        $this->active = $slug;
    }

    public function getTrainersProperty()
    {
        if (!$this->enable || !Auth::check()) {
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

    public function render()
    {
        return view('livewire.assistants-ia-menu');
    }
}
