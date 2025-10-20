<?php

namespace App\Livewire\Formation;

use App\Models\Formation;
use App\Services\FormationService;
use Livewire\Component;

class FormationChapterList extends Component
{
    public Formation $formation;

    /** [chapter_id => title] pour binder proprement */
    public array $chaptersById = [];

    /** id du chapitre en cours d’édition, ou null */
    public ?int $chapterEdition = null;

    public function mount(Formation $formation): void
    {
        $this->chaptersById = $this->formation->chapters
            ->pluck('title', 'id')
            ->toArray();
    }

    /** Règles de validation des titres */
    protected function rules(): array
    {
        return [
            'chaptersById.*' => ['required', 'string', 'min:2', 'max:255'],
        ];
    }

    public function editChapter(int $chapterId): void
    {
        $this->chapterEdition = $chapterId;
    }

    public function saveChapter(int $chapterId): void
    {
        // Valide uniquement le champ modifié
        $this->validateOnly("chaptersById.$chapterId");

        $chapter = $this->formation->chapters()->whereKey($chapterId)->firstOrFail();
        $chapter->update([
            'title' => $this->chaptersById[$chapterId] ?? $chapter->title,
        ]);

        // Sort du mode édition et rafraîchit la liste
        $this->chapterEdition = null;
        $this->formation->refresh()->load(['chapters.lessons']);
        $this->chaptersById = $this->formation->chapters->pluck('title', 'id')->toArray();

        // Optionnel : événement front
        $this->dispatch('chapter-saved', id: $chapterId);
    }

    public function addChapter(): void
    {
        // Crée un chapitre via ton service, puis resynchronise l’état
        app(FormationService::class)->chapters()->createChapter($this->formation);

        $this->formation->refresh()->load(['chapters.lessons']);
        $this->chaptersById = $this->formation->chapters->pluck('title', 'id')->toArray();

        $this->dispatch('chapter-added');
    }

    public function render()
    {
        return view('livewire.formation.formation-chapter-list', [
            'chapters' => $this->formation->chapters,
        ]);
    }
}
