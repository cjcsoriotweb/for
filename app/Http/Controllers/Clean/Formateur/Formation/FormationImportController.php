<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\FormationCompletionDocument;
use App\Models\FormationImportExportLog;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Models\TextContent;
use App\Models\TextContentAttachment;
use App\Models\VideoContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FormationImportController extends Controller
{
    /**
     * Maximum file size for ZIP imports in kilobytes (100 MB).
     */
    private const MAX_ZIP_SIZE_KB = 102400;

    /**
     * Display the formation import form.
     *
     * @return View
     */
    public function showImportForm()
    {
        $recentImports = FormationImportExportLog::where('user_id', Auth::id())
            ->where('type', 'import')
            ->with('formation')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('out-application.formateur.formateur-import-page', [
            'recentImports' => $recentImports,
        ]);
    }

    /**
     * Import a formation from a ZIP file.
     *
     * @param Request $request The HTTP request containing the ZIP file
     * @return RedirectResponse
     */
    public function import(Request $request)
    {
        $zipFile = null;
        $formation = null;
        
        try {
            $request->validate([
                'zip_file' => 'required|file|mimes:zip|max:' . self::MAX_ZIP_SIZE_KB,
            ]);

            $zipFile = $request->file('zip_file');
            $tempDir = storage_path('app/temp/import_'.Str::uuid());

            // Créer le dossier temporaire
            mkdir($tempDir, 0755, true);

            // Extraire le ZIP
            $zipPath = $zipFile->getRealPath();
            $zip = new ZipArchive;

            if ($zip->open($zipPath) !== true) {
                throw new \Exception('Impossible d\'ouvrir le fichier ZIP. Le fichier est peut-être corrompu.');
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Trouver le dossier contenant orchestre.json
            $formationDir = $this->findFormationDirectory($tempDir);

            if (! $formationDir) {
                throw new \Exception('Fichier orchestre.json manquant. Ce ZIP n\'est pas compatible avec l\'import. Assurez-vous d\'exporter une formation depuis cette plateforme.');
            }

            // Vérifier la présence du fichier orchestre.json
            $orchestrePath = $formationDir.'/orchestre.json';
            if (! file_exists($orchestrePath)) {
                throw new \Exception('Fichier orchestre.json manquant dans le dossier de formation.');
            }

            // Lire le fichier orchestre
            $orchestreContent = file_get_contents($orchestrePath);
            $orchestre = json_decode($orchestreContent, true);
            
            if (! $orchestre) {
                throw new \Exception('Fichier orchestre.json invalide : ' . json_last_error_msg());
            }

            // Valider la structure orchestre
            if (!isset($orchestre['structure']) || !isset($orchestre['export_info'])) {
                throw new \Exception('Structure du fichier orchestre.json invalide. Version incompatible.');
            }

            // Importer la formation
            $formation = $this->importFormation($formationDir, $orchestre);

            // Calculate stats
            $stats = [
                'chapters_count' => $formation->chapters()->count(),
                'lessons_count' => $formation->chapters()->withCount('lessons')->get()->sum('lessons_count'),
                'completion_documents_count' => $formation->completionDocuments()->count(),
            ];

            // Nettoyer les fichiers temporaires
            $this->cleanupTempDir($tempDir);

            // Log successful import
            FormationImportExportLog::create([
                'user_id' => Auth::id(),
                'formation_id' => $formation->id,
                'type' => 'import',
                'format' => 'zip',
                'filename' => $zipFile->getClientOriginalName(),
                'status' => 'success',
                'stats' => $stats,
                'file_size' => $zipFile->getSize(),
            ]);

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Formation "'.$formation->title.'" importée avec succès ! La formation a été désactivée par défaut, pensez à l\'activer après vérification.');

        } catch (\Exception $e) {
            // Nettoyer en cas d'erreur
            if (isset($tempDir) && is_dir($tempDir)) {
                $this->cleanupTempDir($tempDir);
            }

            // Log failed import
            FormationImportExportLog::create([
                'user_id' => Auth::id(),
                'formation_id' => $formation?->id,
                'type' => 'import',
                'format' => 'zip',
                'filename' => $zipFile?->getClientOriginalName(),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'file_size' => $zipFile ? $zipFile->getSize() : null,
            ]);

            return back()->with('error', 'Erreur lors de l\'import : '.$e->getMessage());
        }
    }

    /**
     * Import a formation from the extracted ZIP directory.
     *
     * @param string $formationDir The directory containing the formation data
     * @param array<string, mixed> $orchestre The orchestre.json structure
     * @return Formation
     * @throws \Exception
     */
    private function importFormation(string $formationDir, array $orchestre): Formation
    {
        // Créer la formation
        $formation = Formation::create([
            'title' => $orchestre['export_info']['formation_title'],
            'description' => $this->getFormationDescription($formationDir),
            'level' => $this->getFormationLevel($formationDir),
            'active' => false, // Désactivée par défaut, à activer manuellement
            'user_id' => Auth::id(),
        ]);

        // Importer les chapitres
        foreach ($orchestre['structure']['chapters'] as $chapterData) {
            $this->importChapter($formation, $formationDir, $chapterData);
        }

        // Importer les documents de completion
        $this->importCompletionDocuments($formation, $formationDir, $orchestre['structure']['completion_documents']);

        return $formation;
    }

    /**
     * Get the formation description from metadata file.
     *
     * @param string $formationDir The formation directory path
     * @return string
     */
    private function getFormationDescription(string $formationDir): string
    {
        $metadataPath = $formationDir.'/formation_metadata.json';
        if (file_exists($metadataPath)) {
            $metadata = json_decode(file_get_contents($metadataPath), true);

            return $metadata['description'] ?? 'Formation importée';
        }

        return 'Formation importée';
    }

    /**
     * Get the formation level from metadata file.
     *
     * @param string $formationDir The formation directory path
     * @return string
     */
    private function getFormationLevel(string $formationDir): string
    {
        $metadataPath = $formationDir.'/formation_metadata.json';
        if (file_exists($metadataPath)) {
            $metadata = json_decode(file_get_contents($metadataPath), true);

            return $metadata['level'] ?? 'beginner';
        }

        return 'beginner';
    }

    /**
     * Import a chapter from the orchestre data.
     *
     * @param Formation $formation The parent formation
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $chapterData The chapter data from orchestre.json
     * @return Chapter
     */
    private function importChapter(Formation $formation, string $formationDir, array $chapterData): Chapter
    {
        $chapter = Chapter::create([
            'formation_id' => $formation->id,
            'title' => $chapterData['slug'], // Utiliser le slug comme titre temporaire
            'position' => 0, // Sera recalculé après
        ]);

        // Importer les leçons
        foreach ($chapterData['lessons'] as $lessonData) {
            $this->importLesson($chapter, $formationDir, $lessonData);
        }

        // Recalculer les positions
        $formation->chapters()->orderBy('id')->get()->each(function ($chap, $index) {
            $chap->update(['position' => $index + 1]);
        });

        return $chapter;
    }

    /**
     * Import a lesson from the chapter data.
     *
     * @param Chapter $chapter The parent chapter
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $lessonData The lesson data
     * @return Lesson
     */
    private function importLesson(Chapter $chapter, string $formationDir, array $lessonData): Lesson
    {
        $lesson = Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => $lessonData['slug'], // Utiliser le slug comme titre temporaire
            'position' => 0, // Sera recalculé après
            'lessonable_type' => $this->getLessonableType($lessonData['type']),
            'lessonable_id' => 0, // Sera mis à jour après création du contenu
        ]);

        // Créer le contenu selon le type
        $lessonable = $this->createLessonContent($lesson, $formationDir, $lessonData);
        $lesson->update(['lessonable_id' => $lessonable->id]);

        return $lesson;
    }

    /**
     * Get the lessonable type class name for the given lesson type.
     *
     * @param string $type The lesson type (text, video, quiz)
     * @return string The fully qualified class name
     * @throws \Exception
     */
    private function getLessonableType(string $type): string
    {
        return match ($type) {
            'video' => VideoContent::class,
            'text' => TextContent::class,
            'quiz' => Quiz::class,
            default => throw new \Exception('Type de leçon inconnu: '.$type),
        };
    }

    /**
     * Create lesson content based on the lesson type.
     *
     * @param Lesson $lesson The lesson object
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $lessonData The lesson data
     * @return VideoContent|TextContent|Quiz
     */
    private function createLessonContent(Lesson $lesson, string $formationDir, array $lessonData)
    {
        return match ($lessonData['type']) {
            'video' => $this->createVideoContent($lesson, $formationDir, $lessonData),
            'text' => $this->createTextContent($lesson, $formationDir, $lessonData),
            'quiz' => $this->createQuizContent($lesson, $formationDir, $lessonData),
        };
    }

    /**
     * Create video content for a lesson.
     *
     * @param Lesson $lesson The lesson object
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $lessonData The lesson data
     * @return VideoContent
     */
    private function createVideoContent(Lesson $lesson, string $formationDir, array $lessonData): VideoContent
    {
        $metadataPath = $this->resolveFilePath($formationDir, $lessonData['metadata_file']);
        $metadata = json_decode(file_get_contents($metadataPath), true);

        $videoPath = null;
        if (isset($lessonData['video_file'])) {
            $sourceVideoPath = $formationDir.'/'.$lessonData['video_file'];
            if (file_exists($sourceVideoPath)) {
                $videoPath = 'formations/videos/'.Str::uuid().'_'.basename($lessonData['video_file']);
                Storage::disk('public')->put($videoPath, file_get_contents($sourceVideoPath));
            }
        }

        return VideoContent::create([
            'lesson_id' => $lesson->id,
            'title' => $metadata['title'] ?? $lessonData['slug'],
            'description' => $metadata['description'] ?? '',
            'video_url' => $metadata['video_url'] ?? '',
            'video_path' => $videoPath,
            'duration_minutes' => $metadata['duration_minutes'] ?? 0,
        ]);
    }

    /**
     * Create text content for a lesson with attachments.
     *
     * @param Lesson $lesson The lesson object
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $lessonData The lesson data
     * @return TextContent
     */
    private function createTextContent(Lesson $lesson, string $formationDir, array $lessonData): TextContent
    {
        $metadataPath = $this->resolveFilePath($formationDir, $lessonData['metadata_file']);
        $metadata = json_decode(file_get_contents($metadataPath), true);

        $contentPath = $this->resolveFilePath($formationDir, $lessonData['content_file']);
        $content = file_exists($contentPath) ? file_get_contents($contentPath) : '';

        $textContent = TextContent::create([
            'lesson_id' => $lesson->id,
            'title' => $metadata['title'] ?? $lessonData['slug'],
            'description' => $metadata['description'] ?? '',
            'content' => $content,
            'estimated_read_time' => $metadata['estimated_read_time'] ?? 0,
            'allow_download' => $metadata['allow_download'] ?? false,
            'show_progress' => $metadata['show_progress'] ?? true,
        ]);

        // Importer les pièces jointes
        if (isset($lessonData['attachments'])) {
            foreach ($lessonData['attachments'] as $attachmentPath) {
                $sourcePath = $formationDir.'/'.$attachmentPath;
                if (file_exists($sourcePath)) {
                    $fileName = basename($attachmentPath);
                    $storagePath = 'formations/attachments/'.Str::uuid().'_'.$fileName;
                    Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));

                    TextContentAttachment::create([
                        'text_content_id' => $textContent->id,
                        'file_path' => $storagePath,
                        'original_name' => $fileName,
                    ]);
                }
            }
        }

        return $textContent;
    }

    /**
     * Create quiz content with questions and choices.
     *
     * @param Lesson $lesson The lesson object
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $lessonData The lesson data
     * @return Quiz
     */
    private function createQuizContent(Lesson $lesson, string $formationDir, array $lessonData): Quiz
    {
        $quizPath = $this->resolveFilePath($formationDir, $lessonData['quiz_file']);
        $quizData = json_decode(file_get_contents($quizPath), true);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'title' => $quizData['title'] ?? $lessonData['slug'],
            'description' => $quizData['description'] ?? '',
            'type' => Quiz::TYPE_LESSON,
            'passing_score' => $quizData['passing_score'] ?? 50,
            'max_attempts' => $quizData['max_attempts'] ?? 3,
        ]);

        // Importer les questions
        foreach ($quizData['questions'] as $questionData) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'] ?? 'multiple_choice',
                'points' => $questionData['points'] ?? 1,
            ]);

            // Importer les choix
            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceData['choice'],
                    'is_correct' => $choiceData['is_correct'] ?? false,
                ]);
            }
        }

        return $quiz;
    }

    /**
     * Import completion documents for the formation.
     *
     * @param Formation $formation The formation object
     * @param string $formationDir The formation directory path
     * @param array<string, mixed> $completionData The completion documents data
     * @return void
     */
    private function importCompletionDocuments(Formation $formation, string $formationDir, array $completionData): void
    {
        foreach ($completionData['documents'] as $documentData) {
            $sourcePath = $formationDir.'/'.$documentData['file_path'];
            if (file_exists($sourcePath)) {
                $fileName = $documentData['original_name'];
                $storagePath = 'formations/completion/'.Str::uuid().'_'.$fileName;
                Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));

                FormationCompletionDocument::create([
                    'formation_id' => $formation->id,
                    'title' => $documentData['title'],
                    'file_path' => $storagePath,
                    'original_name' => $fileName,
                    'mime_type' => $documentData['mime_type'],
                    'size' => $documentData['size'],
                ]);
            }
        }
    }

    /**
     * Resolve the full file path from a relative path.
     *
     * @param string $formationDir The formation directory path
     * @param string $relativePath The relative path from orchestre.json
     * @return string The resolved absolute path
     */
    private function resolveFilePath(string $formationDir, string $relativePath): string
    {
        // Essayer d'abord le chemin tel quel depuis orchestre.json
        $directPath = $formationDir.'/'.$relativePath;
        if (file_exists($directPath)) {
            return $directPath;
        }

        // Si ça ne marche pas, essayer d'ajouter le préfixe chapters/chapter_slug/
        // Extraire le nom de la leçon du chemin relatif
        $pathParts = explode('/', $relativePath);
        if (count($pathParts) >= 1) {
            $lessonSlug = $pathParts[0]; // ex: "lesson_1_text_nouvelle-lecon"

            // Chercher dans tous les chapitres
            $chaptersDir = $formationDir.'/chapters';
            if (is_dir($chaptersDir)) {
                $chapterDirs = scandir($chaptersDir);
                foreach ($chapterDirs as $chapterDir) {
                    if ($chapterDir === '.' || $chapterDir === '..') {
                        continue;
                    }

                    $fullChapterPath = $chaptersDir.'/'.$chapterDir;
                    if (is_dir($fullChapterPath)) {
                        // Chercher la leçon dans ce chapitre
                        $lessonPath = $fullChapterPath.'/'.$lessonSlug;
                        if (is_dir($lessonPath)) {
                            // Reconstruire le chemin complet
                            $remainingPath = implode('/', array_slice($pathParts, 1));
                            $fullPath = $lessonPath.'/'.$remainingPath;
                            if (file_exists($fullPath)) {
                                return $fullPath;
                            }
                        }
                    }
                }
            }
        }

        // Si rien ne marche, retourner le chemin direct (qui va échouer avec une erreur claire)
        return $directPath;
    }

    /**
     * Find the directory containing the orchestre.json file.
     *
     * @param string $baseDir The base directory to search in
     * @return string|null The directory path containing orchestre.json, or null if not found
     */
    private function findFormationDirectory(string $baseDir): ?string
    {
        // Chercher orchestre.json dans ce répertoire
        if (file_exists($baseDir.'/orchestre.json')) {
            return $baseDir;
        }

        // Chercher récursivement dans les sous-répertoires
        $items = scandir($baseDir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $baseDir.'/'.$item;
            if (is_dir($path)) {
                $result = $this->findFormationDirectory($path);
                if ($result) {
                    return $result;
                }
            }
        }

        return null;
    }

    /**
     * Clean up temporary directory and all its contents.
     *
     * @param string $tempDir The temporary directory to clean up
     * @return void
     */
    private function cleanupTempDir(string $tempDir): void
    {
        if (is_dir($tempDir)) {
            $this->deleteDirectory($tempDir);
        }
    }

    /**
     * Recursively delete a directory and all its contents.
     *
     * @param string $dir The directory to delete
     * @return void
     */
    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $dir.'/'.$file;

            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($dir);
    }
}
