<?php

namespace App\Livewire\Formateur;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\VideoContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

final class VideoUpload extends Component
{
    use WithFileUploads;

    private const STORAGE_DISK = 'public';
    private const STORAGE_DIR  = 'videos';

    // Champs du formulaire
    #[Validate('required|string|max:255', message: 'Le titre de la vidéo est obligatoire.')]
    public string $video_title = '';

    #[Validate('nullable|string')]
    public ?string $video_description = '';

    // 51200 = 50 MB (ajustez selon votre php.ini / post_max_size / upload_max_filesize)
    #[Validate('required|file|mimes:mp4,avi,mov,webm|max:51200', message: 'Veuillez sélectionner un fichier vidéo valide (MP4, AVI, MOV, WebM) de 512MB max.')]
    public $video_file;

    /** en minutes (détectée auto) */
    public ?int $video_duration = null;

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

        $this->video_content = $this->resolveExistingVideoContent($lesson, $video_content);

        if ($this->video_content) {
            $this->video_title       = $this->video_content->title ?? '';
            $this->video_description = $this->video_content->description ?? '';
            $this->video_duration    = $this->video_content->duration_minutes ?? null;
        }
    }

    public function updatedVideoFile(): void
    {
        if (! $this->video_file) {
            $this->upload_complete = false;
            return;
        }

        try {
            $this->validateOnly('video_file');
            $this->upload_complete = true;

            $this->dispatch('file-uploaded', [
                'name' => $this->video_file->getClientOriginalName(),
                'size' => $this->video_file->getSize(),
                'url'  => $this->video_file->temporaryUrl(),
            ]);
        } catch (\Throwable $e) {
            $this->upload_complete = false;
            $this->reset('video_file');
            $this->dispatch('video-error', message: 'Fichier invalide: ' . $e->getMessage());
        }
    }

public function save(): ?RedirectResponse
{
    try {
        $this->validate();

        $videoPath = $this->storeVideo();
        $duration  = $this->detectVideoDuration($videoPath);

        if ($this->video_content) {
            // Mode édition → mise à jour
            $this->updateVideoContent($videoPath, $duration);
            return $this->redirectRoute('formation.chapter.edit', $this->formation);
        }

        // Mode création → création d’un nouveau contenu vidéo
        $this->createVideoContent($videoPath, $duration);
        $this->reset(['video_file', 'upload_complete']);
        $this->dispatch('$refresh');

        // Pas de redirection ici, on reste sur la page → return null

        
        return null;
    } catch (\Throwable $e) {
        $this->dispatch('video-error', message: 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        return null; // 🔥 ajout crucial pour respecter le typage
    }

    $this->render();

}


    private function storeVideo(): string
    {
        /** @var string $path */
        $path = $this->video_file->store(self::STORAGE_DIR, self::STORAGE_DISK);
        return $path;
    }

    private function updateVideoContent(string $videoPath, int $duration): void
    {
        $this->video_content?->update([
            'title'            => $this->video_title,
            'description'      => $this->video_description,
            'video_path'       => $videoPath,
            'duration_minutes' => $duration,
        ]);
    }

    private function createVideoContent(string $videoPath, int $duration): void
    {
        $videoContent = VideoContent::create([
            'lesson_id'        => $this->lesson->id,
            'title'            => $this->video_title,
            'description'      => $this->video_description,
            'video_path'       => $videoPath,
            'duration_minutes' => $duration,
        ]);

        $this->lesson->update([
            'lessonable_type' => VideoContent::class,
            'lessonable_id'   => $videoContent->id,
        ]);

        $this->dispatch('video-created', message: 'Vidéo ajoutée avec succès!');
        $this->video_content = $videoContent;
    }

    private function resolveExistingVideoContent(Lesson $lesson, $video_content): ?VideoContent
    {
        if ($video_content instanceof VideoContent) {
            return $video_content;
        }

        if ($lesson->lessonable_type === VideoContent::class && $lesson->lessonable_id) {
            return VideoContent::find($lesson->lessonable_id);
        }

        return null;
    }

    private function publicPath(string $relative): string
    {
        return Storage::disk(self::STORAGE_DISK)->path($relative);
    }

    private function ffprobeAvailable(): bool
    {
        if (! function_exists('shell_exec') || ini_get('disable_functions')) {
            return false;
        }
        // On ne lance pas de commande ici, on vérifie juste les prérequis min.
        return true;
    }

    private function parseFfprobeDuration(string $json): ?int
    {
        $data = json_decode($json, true);
        if (! is_array($data) || empty($data['format']['duration'])) {
            return null;
        }

        $seconds = (float) $data['format']['duration'];
        return max(1, (int) ceil($seconds / 60));
    }

    private function estimateDurationBySize(string $fullPath): int
    {
        // Fallback (approx) : ~1.5 Mo / min
        $fileSizeBytes    = @filesize($fullPath) ?: 0;
        $estimatedMinutes = (int) ceil($fileSizeBytes / (1024 * 1024 * 1.5));

        // clamp 1..300
        return max(1, min($estimatedMinutes, 300));
    }

    private function detectVideoDuration(string $videoPath): int
    {
        try {
            $fullPath = $this->publicPath($videoPath);

            if ($this->ffprobeAvailable()) {
                $cmd    = 'ffprobe -v quiet -print_format json -show_format ' . escapeshellarg($fullPath) . ' 2>&1';
                $output = shell_exec($cmd);

                if (is_string($output)) {
                    $minutes = $this->parseFfprobeDuration($output);
                    if ($minutes !== null) {
                        return $minutes;
                    }
                }
            }

            return $this->estimateDurationBySize($fullPath);
        } catch (\Throwable $e) {
            // Valeur par défaut si échec
            return 30;
        }
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('formateur.formation.show', $this->formation);
    }

    public function render()
    {
        return view('livewire.formateur.video-upload');
    }
}
