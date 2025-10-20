<?php

namespace App\Livewire\Formation;

use App\Models\Chapter;
use App\Models\Formation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormationLessonList extends Component
{
    public Formation $formation;

    public Chapter $chapter;

    /** [lesson_id => title] pour binder proprement */
    public array $lessonsById = [];

    /** id de la leçon en cours d’édition, ou null */
    public ?int $lessonEdition = null;

    /** État de succès pour les messages */
    public bool $showSuccess = false;

    /** État d'erreur pour les messages */
    public bool $showError = false;

    /** Message de succès */
    public string $successMessage = '';

    /** Message d'erreur */
    public string $errorMessage = '';

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
        try {
            // Valide uniquement le champ modifié
            $this->validateOnly("lessonsById.$lessonId");

            $lesson = $this->chapter->lessons()->whereKey($lessonId)->firstOrFail();

            $oldTitle = $lesson->title;
            $newTitle = $this->lessonsById[$lessonId] ?? $lesson->title;

            $lesson->update([
                'title' => $newTitle,
            ]);

            // Sort du mode édition et rafraîchit la liste
            $this->lessonEdition = null;
            $this->chapter->refresh();
            $this->chapter->loadMissing('lessons');
            $this->lessonsById = $this->chapter->lessons->pluck('title', 'id')->toArray();

            // Message de succès
            $this->showSuccessMessage("Leçon '{$oldTitle}' renommée en '{$newTitle}' avec succès.");

            // Événement front (optionnel)
            $this->dispatch('lesson-saved', id: $lessonId);
        } catch (\Exception $e) {
            $this->showErrorMessage('Erreur lors de la sauvegarde : '.$e->getMessage());
        }
    }

    /** Crée une nouvelle leçon */
    public function addLesson(): void
    {
        try {
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

            // Corrige les positions après la création
            $this->fixPositions();

            $this->showSuccessMessage("Nouvelle leçon '{$lesson->title}' créée avec succès.");

            $this->dispatch('lesson-added', lessonId: $lesson->id);
        } catch (\Exception $e) {
            $this->showErrorMessage('Erreur lors de la création : '.$e->getMessage());
        }
    }

    /** Annule l’édition en cours */
    public function cancelEdit(): void
    {
        $this->lessonEdition = null;
    }

    /** Supprime une leçon après confirmation */
    public function confirmDeleteLesson(int $lessonId): void
    {
        try {
            $lesson = $this->chapter->lessons()->whereKey($lessonId)->firstOrFail();
            $lessonTitle = $lesson->title;
            $lesson->delete();

            // Refresh chapter data
            $this->chapter->refresh();
            $this->chapter->loadMissing('lessons');

            // Update lessonsById array with chapter-specific lessons
            $this->lessonsById = $this->chapter->lessons->pluck('title', 'id')->toArray();

            // Corrige les positions après la suppression
            $this->fixPositions();

            $this->showSuccessMessage("Leçon '{$lessonTitle}' supprimée avec succès.");

            $this->dispatch('lesson-deleted', lessonId: $lessonId);
        } catch (\Exception $e) {
            $this->showErrorMessage('Erreur lors de la suppression : '.$e->getMessage());
        }
    }

    /** Déplace une leçon vers le haut */
    public function moveLessonUp(int $lessonId): void
    {
        try {
            $lesson = $this->chapter->lessons()->whereKey($lessonId)->firstOrFail();

            if ($lesson->position <= 1) {
                return; // Déjà en première position
            }

            // Utilise une transaction pour éviter les conflits
            DB::transaction(function () use ($lesson) {
                // Décale vers le bas toutes les leçons qui ont une position inférieure
                $this->chapter->lessons()
                    ->where('position', '<', $lesson->position)
                    ->increment('position');

                // Place la leçon à la position précédente
                $lesson->update(['position' => $lesson->position - 1]);
            });

            // Rafraîchit les données
            $this->chapter->refresh();
            $this->chapter->loadMissing('lessons');

            $this->showSuccessMessage("Leçon '{$lesson->title}' déplacée vers le haut.");
        } catch (\Exception $e) {
            $this->showErrorMessage('Erreur lors du déplacement : '.$e->getMessage());
        }
    }

    /** Déplace une leçon vers le bas */
    public function moveLessonDown(int $lessonId): void
    {
        try {
            $lesson = $this->chapter->lessons()->whereKey($lessonId)->firstOrFail();
            $totalLessons = $this->chapter->lessons()->count();

            if ($lesson->position >= $totalLessons) {
                return; // Déjà en dernière position
            }

            // Utilise une transaction pour éviter les conflits
            DB::transaction(function () use ($lesson) {
                // Décale vers le haut toutes les leçons qui ont une position supérieure
                $this->chapter->lessons()
                    ->where('position', '>', $lesson->position)
                    ->decrement('position');

                // Place la leçon à la position suivante
                $lesson->update(['position' => $lesson->position + 1]);
            });

            // Rafraîchit les données
            $this->chapter->refresh();
            $this->chapter->loadMissing('lessons');

            $this->showSuccessMessage("Leçon '{$lesson->title}' déplacée vers le bas.");
        } catch (\Exception $e) {
            $this->showErrorMessage('Erreur lors du déplacement : '.$e->getMessage());
        }
    }

    /** Vérifie et corrige les positions après un déplacement */
    private function fixPositions(): void
    {
        try {
            $lessons = $this->chapter->lessons()->orderBy('position')->get();

            foreach ($lessons as $index => $lesson) {
                $expectedPosition = $index + 1;
                if ($lesson->position !== $expectedPosition) {
                    $lesson->update(['position' => $expectedPosition]);
                }
            }

            // Rafraîchit les données
            $this->chapter->refresh();
            $this->chapter->loadMissing('lessons');
        } catch (\Exception $e) {
            $this->showErrorMessage('Erreur lors de la correction des positions : '.$e->getMessage());
        }
    }

    /** Affiche un message de succès */
    private function showSuccessMessage(string $message): void
    {
        $this->successMessage = $message;
        $this->showSuccess = true;
        $this->showError = false;

        // Auto-hide après 3 secondes
        $this->dispatch('auto-hide-message');
    }

    /** Affiche un message d'erreur */
    private function showErrorMessage(string $message): void
    {
        $this->errorMessage = $message;
        $this->showError = true;
        $this->showSuccess = false;

        // Auto-hide après 5 secondes pour les erreurs
        $this->dispatch('auto-hide-error');
    }

    /** Efface le message de succès */
    public function clearSuccessMessage(): void
    {
        $this->showSuccess = false;
        $this->successMessage = '';
    }

    /** Efface le message d'erreur */
    public function clearErrorMessage(): void
    {
        $this->showError = false;
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.formation.formation-lesson-list', [
            'lessons' => $this->chapter->lessons,
        ]);
    }
}
