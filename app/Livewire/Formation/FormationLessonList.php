<?php

namespace App\Livewire\Formation;

use App\Models\Chapter;
use App\Models\Formation;
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

        // Précharger les titres des leçons du chapitre spécifique (clé = id, valeur = title)
        $this->chapter->loadMissing('lessons');
        $this->lessonsById = $this->chapter->lessons->pluck('title', 'id')->toArray();
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
            $current = $this->chapter->lessons()->whereKey($lessonId)->value('title');
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

        $lesson = $this->chapter->lessons()->whereKey($lessonId)->firstOrFail();

        $lesson->update([
            'title' => $this->lessonsById[$lessonId] ?? $lesson->title,
        ]);

        // Sort du mode édition et rafraîchit la liste
        $this->lessonEdition = null;
        $this->chapter->refresh();
        $this->chapter->loadMissing('lessons');
        $this->lessonsById = $this->chapter->lessons->pluck('title', 'id')->toArray();

        // Événement front (optionnel)
        $this->dispatch('lesson-saved', id: $lessonId);
    }

    /** Crée une nouvelle leçon */
    public function addLesson(): void
    {
        // Create lesson directly in the specific chapter
        $lesson = $this->chapter->lessons()->create([
            'title' => 'Nouvelle leçon',
            'position' => $this->chapter->lessons()->count() + 1,
        ]);

        // Refresh chapter data
        $this->chapter->refresh();
        $this->chapter->loadMissing('lessons');

        // Update lessonsById array with chapter-specific lessons
        $this->lessonsById = $this->chapter->lessons->pluck('title', 'id')->toArray();

        $this->dispatch('lesson-added', lessonId: $lesson->id);
    }

    /** Annule l’édition en cours */
    public function cancelEdit(): void
    {
        $this->lessonEdition = null;
    }

    /** Confirme la suppression d’une leçon */
    public function confirmDeleteLesson(int $lessonId): void
    {
        $this->dispatch('confirm-delete-lesson', lessonId: $lessonId);
    }

    /** Supprime une leçon */
    public function deleteLesson(int $lessonId): void
    {
        $lesson = $this->chapter->lessons()->whereKey($lessonId)->firstOrFail();
        $lesson->delete();

        // Refresh chapter data
        $this->chapter->refresh();
        $this->chapter->loadMissing('lessons');

        // Update lessonsById array with chapter-specific lessons
        $this->lessonsById = $this->chapter->lessons->pluck('title', 'id')->toArray();

        $this->dispatch('lesson-deleted', lessonId: $lessonId);
    }

    public function render()
    {
        return view('livewire.formation.formation-lesson-list', [
            'lessons' => $this->chapter->lessons,
        ]);
    }
}
