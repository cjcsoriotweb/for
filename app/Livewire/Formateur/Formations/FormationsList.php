<?php

namespace App\Livewire\Formateur\Formations;

use App\Models\Formation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class FormationsList extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 5;

    public $formations;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->loadFormations();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadFormations();
    }

    public function loadFormations()
    {
        $query = Formation::withCount(['learners', 'lessons'])
            ->with([
                'lessons' => function ($query) {
                    $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
                },
                'lessons.lessonable.quizQuestions' => function ($query) {
                    $query->select('id', 'quiz_id'); // Précharger le compte de questions pour optimiser
                },
            ]);

        // Appliquer le filtre utilisateur seulement si ce n'est pas un superadmin
        if (! Auth::user()->superadmin) {
            $query->where('user_id', '=', Auth::user()->id);
        }

        // Recherche sécurisée avec scoping approprié
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        $this->formations = $query->paginate($this->perPage);

        // Shape data for the view to keep Blade templates presentation-only.
        $this->formations->each(function ($formation) {
            $formation->video_count = $formation->lessons->where('lessonable_type', 'App\\Models\\VideoContent')->count();
            $formation->quiz_count = $formation->lessons->where('lessonable_type', 'App\\Models\\Quiz')->count();
            $formation->text_count = $formation->lessons->where('lessonable_type', 'App\\Models\\TextContent')->count();

            $formation->card_lessons_count = $formation->lessons_count ?? 0;
            $formation->card_learners_count = $formation->learners_count ?? 0;
            $formation->card_description = Str::limit((string) $formation->description, 180);
            $formation->card_completion_percentage = min(100, $formation->completion_percentage ?? 75);
            $formation->card_created_label = $formation->created_at instanceof Carbon
                ? $formation->created_at->diffForHumans()
                : null;
            $formation->card_is_active = (bool) $formation->active;

            // Calculate total duration in minutes.
            $totalDuration = 0;

            foreach ($formation->lessons as $lesson) {
                switch ($lesson->lessonable_type) {
                    case 'App\\Models\\VideoContent':
                        $totalDuration += $lesson->lessonable->duration_minutes ?? 0;
                        break;
                    case 'App\\Models\\TextContent':
                        $totalDuration += $lesson->lessonable->estimated_read_time ?? 0;
                        break;
                    case 'App\\Models\\Quiz':
                        $questionCount = $lesson->lessonable->quizQuestions()->count();
                        $totalDuration += max($questionCount * 2, 5);
                        break;
                }
            }

            $formation->total_duration_minutes = $totalDuration;
        });
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadFormations();
    }

    public function render()
    {
        return view('livewire.formateur.formations.formations-list', [
            'formations' => $this->formations,
        ]);
    }
}
