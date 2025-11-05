<?php

namespace App\Livewire\Formateur;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\VideoContent;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class VideoUpload extends Component
{
    use WithFileUploads;

    // Champs du formulaire
    #[Validate('required|string|max:255', message: 'Le titre de la vidéo est obligatoire.')]
    public string $video_title = '';

    #[Validate('nullable|string')]
    public ?string $video_description = '';

    // Limite ajustée pour 512MB (nécessite configuration PHP)
    #[Validate('required|file|mimes:mp4,avi,mov,webm|max:51200', message: 'Veuillez sélectionner un fichier vidéo valide (MP4, AVI, MOV, WebM) de 512MB max.')]
    public $video_file;

    public ?int $video_duration = null; // en minutes (détectée auto)

    // État d’upload (facultatif pour l’UI)
    public bool $is_uploading = false;
    public bool $upload_complete = false;

    // Contexte
    public Formation $formation;
    public Chapter $chapter;
    public Lesson $lesson;

    // Mode édition
    public ?VideoContent $video_content = null;

    public function mount(Formation $formation, Chapter $chapter, Lesson $lesson, $video_content = null): void
    {
        $this->formation = $formation;
        $this->chapter   = $chapter;
        $this->lesson    = $lesson;

        // Si l’on passe un $video_content directement (route optionnelle) :
        if ($video_content instanceof VideoContent) {
            $this->video_content = $video_content;
        }
        // Sinon, si la leçon a déjà un contenu vidéo via la relation polymorphe :
        elseif ($lesson->lessonable_type === VideoContent::class && $lesson->lessonable_id) {
            $this->video_content = VideoContent::find($lesson->lessonable_id);
        }

        // Pré-remplissage en mode édition
        if ($this->video_content) {
            $this->video_title       = $this->video_content->title ?? '';
            $this->video_description = $this->video_content->description ?? '';
            $this->video_duration    = $this->video_content->duration_minutes ?? null;
        }
    }

    public function updatedVideoFile(): void
    {
        logger('VideoUpload: updatedVideoFile called');

        if ($this->video_file) {
            try {
                $this->validateOnly('video_file');
                $this->upload_complete = true;
                logger('VideoUpload: File valid');

                // maj d’un éventuel aperçu côté front
                $this->dispatch('file-uploaded', [
                    'name' => $this->video_file->getClientOriginalName(),
                    'size' => $this->video_file->getSize(),
                    'url'  => $this->video_file->temporaryUrl(),
                ]);
            } catch (\Throwable $e) {
                $this->upload_complete = false;
                logger('VideoUpload: validation failed - '.$e->getMessage());
                $this->dispatch('video-error', message: 'Fichier invalide: '.$e->getMessage());
                $this->reset('video_file');
            }
        } else {
            $this->upload_complete = false;
        }
    }

    public function save()
    {
        logger('VideoUpload: save() called');

        try {
            $this->validate(); // valide titre + fichier (et description si présente)

            // Stockage
            $videoPath = $this->video_file->store('videos', 'public');
            logger('VideoUpload: stored at '.$videoPath);

            // Durée détectée
            $duration = $this->detectVideoDuration($videoPath);

            if ($this->video_content) {
                // Mise à jour
                $this->video_content->update([
                    'title'            => $this->video_title,
                    'description'      => $this->video_description,
                    'video_path'       => $videoPath,
                    'duration_minutes' => $duration,
                ]);

                $this->dispatch('video-updated', message: 'Vidéo modifiée avec succès!');
                logger('VideoUpload: updated');
            } else {
                // Création
                $videoContent = VideoContent::create([
                    'lesson_id'        => $this->lesson->id,
                    'title'            => $this->video_title,
                    'description'      => $this->video_description,
                    'video_path'       => $videoPath,
                    'duration_minutes' => $duration,
                ]);

                // Liaison polymorphe
                $this->lesson->update([
                    'lessonable_type' => VideoContent::class,
                    'lessonable_id'   => $videoContent->id,
                ]);

                $this->dispatch('video-created', message: 'Vidéo ajoutée avec succès!');
                logger('VideoUpload: created');
            }

            // Redirection vers la page formation
            return redirect()->route('formateur.formation.show', $this->formation);

        } catch (\Throwable $e) {
            logger('VideoUpload: save() error - '.$e->getMessage());
            $this->dispatch('video-error', message: 'Erreur lors de la sauvegarde: '.$e->getMessage());
        }
    }

    private function detectVideoDuration(string $videoPath): int
    {
        try {
            $fullPath = Storage::disk('public')->path($videoPath);

            // Essai via ffprobe si dispo
            if (function_exists('shell_exec') && ! ini_get('disable_functions')) {
                $cmd = 'ffprobe -v quiet -print_format json -show_format '.escapeshellarg($fullPath).' 2>&1';
                $output = shell_exec($cmd);

                if ($output) {
                    $data = json_decode($output, true);
                    if (isset($data['format']['duration'])) {
                        $seconds = (float) $data['format']['duration'];

                        return max(1, (int) ceil($seconds / 60));
                    }
                }
            }

            // Fallback (approx) : ~1.5 Mo / min
            $fileSize = filesize($fullPath); // en octets
            $estimatedMinutes = (int) ceil($fileSize / (1024 * 1024 * 1.5));

            return max(1, min($estimatedMinutes, 300)); // clamp 1..300

        } catch (\Throwable $e) {
            // Valeur par défaut si échec
            return 30;
        }
    }

    public function testLivewire(): void
    {
        logger('VideoUpload: testLivewire called');
        $this->dispatch('livewire-test', message: 'Livewire fonctionne correctement!');
    }

    public function cancel()
    {
        return redirect()->route('formateur.formation.show', $this->formation);
    }

    public function render()
    {
        return view('livewire.formateur.video-upload');
    }
}
