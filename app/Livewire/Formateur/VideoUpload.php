<?php

namespace App\Livewire\Formateur;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\VideoContent;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VideoUpload extends Component
{
    use WithFileUploads;

    // Propriétés du formulaire
    public $video_title = '';

    public $video_description = '';

    public $video_file;

    public $video_duration; // Sera détecté automatiquement

    // Propriétés pour l'upload (simplifiées pour utiliser uniquement WithFileUploads)
    public $is_uploading = false;

    public $upload_complete = false;

    // Relations
    public $formation;

    public $chapter;

    public $lesson;

    public $video_content; // Pour l'édition

    // Règles de validation
    protected $rules = [
        'video_title' => 'required|string|max:255',
        'video_description' => 'nullable|string',
        'video_file' => 'required|file|mimes:mp4,avi,mov,webm|max:52402880', // 5GB max
    ];

    protected $messages = [
        'video_title.required' => 'Le titre de la vidéo est obligatoire.',
        'video_file.required' => 'Veuillez sélectionner un fichier vidéo.',
        'video_file.max' => 'La taille du fichier ne peut pas dépasser 5GB.',
        'video_file.mimes' => 'Le fichier doit être au format MP4, AVI, MOV ou WebM.',
    ];

    public function mount(Formation $formation, Chapter $chapter, Lesson $lesson, $video_content = null)
    {
        $this->formation = $formation;
        $this->chapter = $chapter;
        $this->lesson = $lesson;
        $this->video_content = $video_content;

        // Si on édite une vidéo existante
        if ($video_content) {
            $this->video_title = $video_content->title;
            $this->video_description = $video_content->description;
            $this->video_duration = $video_content->duration_minutes;
        }
    }

    public function updatedVideoFile()
    {
        // Validate the file when selected
        if ($this->video_file) {
            try {
                $this->validateOnly('video_file');
                $this->upload_complete = true;
            } catch (\Exception $e) {
                $this->upload_complete = false;
                $this->dispatch('video-error', message: 'Fichier invalide: '.$e->getMessage());
            }
        }
    }

    public function save()
    {
        logger('VideoUpload: save() method called');

        try {
            // Validate all form data including the file
            $this->validate();

            logger('VideoUpload: Validation passed');

            // Store the uploaded file
            $videoPath = $this->video_file->store('videos', 'public');
            logger('VideoUpload: File stored at: '.$videoPath);

            // Détecter la durée de la vidéo automatiquement
            $duration = $this->detectVideoDuration($videoPath);
            logger('VideoUpload: Duration detected: '.$duration.' minutes');

            // Create or update video content
            if ($this->video_content) {
                logger('VideoUpload: Updating existing video content');
                $this->video_content->update([
                    'title' => $this->video_title,
                    'description' => $this->video_description,
                    'video_path' => $videoPath,
                    'duration_minutes' => $duration,
                ]);

                $this->dispatch('video-updated', message: 'Vidéo modifiée avec succès!');
                logger('VideoUpload: Video content updated');
            } else {
                logger('VideoUpload: Creating new video content');
                $videoContent = VideoContent::create([
                    'lesson_id' => $this->lesson->id,
                    'title' => $this->video_title,
                    'description' => $this->video_description,
                    'video_path' => $videoPath,
                    'duration_minutes' => $duration,
                ]);

                // Update lesson with polymorphic relationship
                $this->lesson->update([
                    'lessonable_type' => VideoContent::class,
                    'lessonable_id' => $videoContent->id,
                ]);

                $this->dispatch('video-created', message: 'Vidéo ajoutée avec succès!');
                logger('VideoUpload: Video content created');
            }

            logger('VideoUpload: Save completed successfully');

            // Redirect to formation show
            return redirect()->route('formateur.formation.show', $this->formation);

        } catch (\Exception $e) {
            logger('VideoUpload: Error in save() - '.$e->getMessage());
            logger('VideoUpload: Error file: '.$e->getFile().' Line: '.$e->getLine());
            $this->dispatch('video-error', message: 'Erreur lors de la sauvegarde: '.$e->getMessage());
        }
    }

    private function detectVideoDuration($videoPath)
    {
        try {
            $fullPath = Storage::disk('public')->path($videoPath);

            // Try FFmpeg/ffprobe if available (most reliable)
            if (function_exists('shell_exec') && ! ini_get('disable_functions')) {
                $command = "ffprobe -v quiet -print_format json -show_format \"$fullPath\" 2>&1";
                $output = shell_exec($command);

                if ($output) {
                    $data = json_decode($output, true);
                    if (isset($data['format']['duration'])) {
                        $duration = (float) $data['format']['duration'];

                        return max(1, ceil($duration / 60)); // Convert to minutes, minimum 1
                    }
                }
            }

            // Fallback: estimate based on file size (rough approximation)
            // For compressed video: ~1-2MB per minute depending on quality
            $fileSize = filesize($fullPath);
            $estimatedMinutes = ceil($fileSize / (1024 * 1024 * 1.5)); // ~1.5MB per minute average

            return max(1, min($estimatedMinutes, 300)); // Between 1 and 300 minutes

        } catch (\Exception $e) {
            // If detection fails, return a default duration
            return 30; // 30 minutes default
        }
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
