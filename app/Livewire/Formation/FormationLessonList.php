<?php

namespace App\Livewire\Formation;

use App\Models\Chapter;
use App\Models\Formation;
use App\Services\FormationService;
use Livewire\Component;

class FormationLessonList extends Component
{
    public Formation $formation;

    public Chapter $chapter;
    /** [lesson_id => title] pour binder proprement */
    public array $lessonsById = [];

    /** id de la leçon en cours d’édition, ou null */
    public ?int $lessonEdition = null;

    public function mount(Formation $formation, Chapter $chapter): void
    {
        // Important : assigner la formation reçue
        $this->formation = $formation;
        $this->chapter = $chapter;

        // Précharger les titres des leçons (clé = id, valeur = title)
        $this->formation->loadMissing('lessons'); // si pas déjà chargé
        $this->lessonsById = $this->formation->lessons->pluck('title', 'id')->toArray();
    }

    /** Règles de validation des titres */
    protected function rules(): array
    {
        return [
            'lessonsById.*' => ['required', 'string', 'min:2', 'max:255'],
        ];
    }

    /** Passe une leçon en mode édition */
    public function editLesson(int $lessonId): void
    {
        $this->lessonEdition = $lessonId;

        // S'assure que le champ d'édition a la valeur actuelle
        if (! array_key_exists($lessonId, $this->lessonsById)) {
            $current = $this->formation->lessons()->whereKey($lessonId)->value('title');
            if ($current !== null) {
                $this->lessonsById[$lessonId] = $current;
            }
        }
    }

    /** Enregistre le titre de la leçon en cours d’édition */
    public function saveLesson(int $lessonId): void
    {
        // Valide uniquement le champ modifié
        $this->validateOnly("lessonsById.$lessonId");

        $lesson = $this->formation->lessons()->whereKey($lessonId)->firstOrFail();

        $lesson->update([
            'title' => $this->lessonsById[$lessonId] ?? $lesson->title,
        ]);

        // Sort du mode édition et rafraîchit la liste
        $this->lessonEdition = null;
        $this->formation->refresh();
        $this->formation->loadMissing('lessons'); // si pas déjà chargé
        $this->lessonsById = $this->formation->lessons->pluck('title', 'id')->toArray();

        // Événement front (optionnel)
        $this->dispatch('lesson-saved', id: $lessonId);
    }

    /** Crée une nouvelle leçon */
    public function addLesson(): void
    {
        // Via ton service (si c'est ton design actuel)
        app(FormationService::class)
            ->lessons()
            ->createLesson($this->formation);

        // Si tu veux un fallback simple, dé-commente ci-dessous :
        // $this->formation->lessons()->create(['title' => 'Nouvelle leçon']);

        $this->formation->refresh();
        $this->lessonsById = $this->formation->lessons->pluck('title', 'id')->toArray();

        $this->dispatch('lesson-added');
    }

    /** Annule l’édition en cours */
    public function cancelEdit(): void
    {
        $this->lessonEdition = null;
    }

    public function render()
    {
        return view('livewire.formation.formation-lesson-list', [
            'lessons' => $this->formation->lessons,
        ]);
    }
}
