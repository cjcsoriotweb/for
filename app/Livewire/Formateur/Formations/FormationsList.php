<?php

namespace App\Livewire\Formateur\Formations;

use App\Models\Formation;
use Livewire\Component;
use Livewire\WithPagination;

class FormationsList extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 5;

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
            ->with(['lessons' => function ($query) {
                $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
            }]);

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('description', 'like', '%'.$this->search.'%');
        }

        $this->formations = $query->paginate($this->perPage);

        // Add content type counts and duration for each formation
        $this->formations->each(function ($formation) {
            $formation->video_count = $formation->lessons->where('lessonable_type', 'App\\Models\\VideoContent')->count();
            $formation->quiz_count = $formation->lessons->where('lessonable_type', 'App\\Models\\Quiz')->count();
            $formation->text_count = $formation->lessons->where('lessonable_type', 'App\\Models\\TextContent')->count();

            // Calculate total duration in minutes
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
                        // Estimate quiz duration: 2 minutes per question
                        $questionCount = $lesson->lessonable->quizQuestions()->count();
                        $totalDuration += max($questionCount * 2, 5); // Minimum 5 minutes per quiz
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
