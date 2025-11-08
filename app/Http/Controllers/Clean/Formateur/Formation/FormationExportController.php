<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationImportExportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FormationExportController extends Controller
{
    public function export(Formation $formation, Request $request)
    {
        $format = $request->query('format', 'zip');
        
        // Validate format
        $allowedFormats = ['zip', 'json', 'csv'];
        if (!in_array($format, $allowedFormats)) {
            return back()->with('error', 'Format d\'export invalide. Formats acceptés : ' . implode(', ', $allowedFormats));
        }
        
        try {
            // Charger toutes les données nécessaires
            $formation->load([
                'chapters.lessons.lessonable',
                'chapters.lessons.quizzes.quizQuestions.quizChoices',
                'completionDocuments',
            ]);

            $stats = [
                'chapters_count' => $formation->chapters->count(),
                'lessons_count' => $formation->chapters->sum(fn ($chapter) => $chapter->lessons->count()),
                'completion_documents_count' => $formation->completionDocuments->count(),
            ];

            $response = match ($format) {
                'json' => $this->exportJson($formation),
                'csv' => $this->exportCsv($formation),
                default => $this->exportZip($formation),
            };

            // Log successful export
            FormationImportExportLog::create([
                'user_id' => Auth::id(),
                'formation_id' => $formation->id,
                'type' => 'export',
                'format' => $format,
                'filename' => $this->getExportFilename($formation, $format),
                'status' => 'success',
                'stats' => $stats,
            ]);

            return $response;
        } catch (\Exception $e) {
            // Log failed export
            FormationImportExportLog::create([
                'user_id' => Auth::id(),
                'formation_id' => $formation->id,
                'type' => 'export',
                'format' => $format,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erreur lors de l\'export : ' . $e->getMessage());
        }
    }

    private function getExportFilename(Formation $formation, string $format): string
    {
        $extension = match ($format) {
            'json' => 'json',
            'csv' => 'csv',
            default => 'zip',
        };
        
        return Str::slug($formation->title).'_export_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
    }

    private function exportZip(Formation $formation)
    {
        // Créer un dossier temporaire pour l'export
        $tempDir = storage_path('app/temp/'.Str::uuid());
        $formationDir = $tempDir.'/'.Str::slug($formation->title);

        // Créer les dossiers nécessaires
        $this->createDirectories($formationDir);

        // Exporter les métadonnées de la formation
        $this->exportFormationMetadata($formation, $formationDir);

        // Exporter les chapitres et leçons
        $chaptersStructure = $this->exportChapters($formation, $formationDir);

        // Exporter les documents de fin de formation
        $completionDocumentsStructure = $this->exportCompletionDocuments($formation, $formationDir);

        // Créer le fichier orchestre
        $this->createOrchestreFile($formation, $formationDir, $chaptersStructure, $completionDocumentsStructure);

        // Créer le fichier ZIP
        $zipFileName = Str::slug($formation->title).'_export_'.now()->format('Y-m-d_H-i-s').'.zip';
        $zipPath = storage_path('app/temp/'.$zipFileName);

        $this->createZip($tempDir, $zipPath);

        // Nettoyer le dossier temporaire
        $this->cleanupTempDir($tempDir);

        // Retourner le téléchargement
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend();
    }

    private function exportJson(Formation $formation)
    {
        $data = [
            'title' => $formation->title,
            'description' => $formation->description,
            'level' => $formation->level,
            'exported_at' => now()->toISOString(),
            'export_version' => '2.0',
            'chapters' => $formation->chapters->map(function ($chapter) {
                return [
                    'title' => $chapter->title,
                    'position' => $chapter->position,
                    'lessons' => $chapter->lessons->map(function ($lesson) {
                        return $this->serializeLessonToJson($lesson);
                    })->toArray(),
                ];
            })->toArray(),
            'completion_documents' => $formation->completionDocuments->map(function ($doc) {
                return [
                    'title' => $doc->title,
                    'original_name' => $doc->original_name,
                    'mime_type' => $doc->mime_type,
                    'size' => $doc->size,
                ];
            })->toArray(),
        ];

        $fileName = Str::slug($formation->title).'_export_'.now()->format('Y-m-d_H-i-s').'.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"')
            ->header('Content-Type', 'application/json');
    }

    private function exportCsv(Formation $formation)
    {
        $rows = [];
        
        // En-tête CSV
        $rows[] = [
            'Formation',
            'Description Formation',
            'Niveau',
            'Chapitre',
            'Position Chapitre',
            'Leçon',
            'Type Leçon',
            'Contenu',
            'Durée (minutes)',
            'Position Leçon',
        ];

        // Données
        foreach ($formation->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonData = $this->serializeLessonToCsv($lesson);
                $rows[] = [
                    $formation->title,
                    $formation->description ?? '',
                    $formation->level ?? '',
                    $chapter->title,
                    $chapter->position ?? 0,
                    $lesson->title,
                    $lessonData['type'],
                    $lessonData['content'],
                    $lessonData['duration'],
                    $lesson->position ?? 0,
                ];
            }
        }

        $fileName = Str::slug($formation->title).'_export_'.now()->format('Y-m-d_H-i-s').'.csv';
        $handle = fopen('php://temp', 'r+');
        
        foreach ($rows as $row) {
            fputcsv($handle, $row, ';');
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"')
            ->header('Content-Transfer-Encoding', 'binary');
    }

    private function serializeLessonToJson($lesson)
    {
        $lessonable = $lesson->lessonable;
        $baseData = [
            'title' => $lesson->title,
            'position' => $lesson->position ?? 0,
        ];

        if ($lessonable instanceof \App\Models\VideoContent) {
            return array_merge($baseData, [
                'type' => 'video',
                'description' => $lessonable->description,
                'video_url' => $lessonable->video_url,
                'duration_minutes' => $lessonable->duration_minutes,
            ]);
        } elseif ($lessonable instanceof \App\Models\TextContent) {
            return array_merge($baseData, [
                'type' => 'text',
                'description' => $lessonable->description,
                'content' => $lessonable->content,
                'estimated_read_time' => $lessonable->estimated_read_time,
            ]);
        } elseif ($lessonable instanceof \App\Models\Quiz) {
            return array_merge($baseData, [
                'type' => 'quiz',
                'description' => $lessonable->description,
                'passing_score' => $lessonable->passing_score,
                'max_attempts' => $lessonable->max_attempts,
                'questions' => $lessonable->quizQuestions->map(function ($question) {
                    return [
                        'question' => $question->question,
                        'type' => $question->type,
                        'points' => $question->points,
                        'choices' => $question->quizChoices->map(function ($choice) {
                            return [
                                'choice' => $choice->choice,
                                'is_correct' => $choice->is_correct,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ]);
        }

        return $baseData;
    }

    private function serializeLessonToCsv($lesson)
    {
        $lessonable = $lesson->lessonable;

        if ($lessonable instanceof \App\Models\VideoContent) {
            return [
                'type' => 'video',
                'content' => $lessonable->video_url ?? '',
                'duration' => $lessonable->duration_minutes ?? 0,
            ];
        } elseif ($lessonable instanceof \App\Models\TextContent) {
            return [
                'type' => 'text',
                'content' => strip_tags($lessonable->content ?? ''),
                'duration' => $lessonable->estimated_read_time ?? 0,
            ];
        } elseif ($lessonable instanceof \App\Models\Quiz) {
            return [
                'type' => 'quiz',
                'content' => $lessonable->quizQuestions->count() . ' questions',
                'duration' => 15,
            ];
        }

        return [
            'type' => 'unknown',
            'content' => '',
            'duration' => 0,
        ];
    }

    private function createDirectories(string $formationDir): void
    {
        $directories = [
            $formationDir,
            $formationDir.'/videos',
            $formationDir.'/documents',
            $formationDir.'/quizzes',
            $formationDir.'/texts',
            $formationDir.'/completion_documents',
        ];

        foreach ($directories as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    private function exportFormationMetadata(Formation $formation, string $formationDir): void
    {
        $metadata = [
            'title' => $formation->title,
            'description' => $formation->description,
            'level' => $formation->level,
            'chapters_count' => $formation->chapters->count(),
            'lessons_count' => $formation->chapters->sum(fn ($chapter) => $chapter->lessons->count()),
            'exported_at' => now()->toISOString(),
        ];

        file_put_contents(
            $formationDir.'/formation_metadata.json',
            json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function exportChapters(Formation $formation, string $formationDir): array
    {
        $chaptersStructure = [];

        foreach ($formation->chapters as $chapterIndex => $chapter) {
            $chapterSlug = 'chapter_'.($chapterIndex + 1).'_'.Str::slug($chapter->title);
            $chapterDir = $formationDir.'/chapters/'.$chapterSlug;

            if (! is_dir($chapterDir)) {
                mkdir($chapterDir, 0755, true);
            }

            // Exporter les métadonnées du chapitre
            $chapterData = [
                'title' => $chapter->title,
                'position' => $chapter->position,
                'lessons_count' => $chapter->lessons->count(),
            ];

            file_put_contents(
                $chapterDir.'/chapter_metadata.json',
                json_encode($chapterData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            // Exporter les leçons
            $lessonsStructure = [];
            foreach ($chapter->lessons as $lessonIndex => $lesson) {
                $lessonStructure = $this->exportLesson($lesson, $chapterDir, $chapterSlug, $lessonIndex + 1);
                if ($lessonStructure) {
                    $lessonsStructure[] = $lessonStructure;
                }
            }

            $chaptersStructure[] = [
                'id' => $chapter->id,
                'slug' => $chapterSlug,
                'metadata_file' => 'chapters/'.$chapterSlug.'/chapter_metadata.json',
                'lessons' => $lessonsStructure,
            ];
        }

        return $chaptersStructure;
    }

    private function exportLesson($lesson, string $chapterDir, string $chapterSlug, int $lessonNumber): ?array
    {
        $lessonable = $lesson->lessonable;

        if ($lessonable instanceof \App\Models\VideoContent) {
            return $this->exportVideoLesson($lesson, $lessonable, $chapterDir, $chapterSlug, $lessonNumber);
        } elseif ($lessonable instanceof \App\Models\TextContent) {
            return $this->exportTextLesson($lesson, $lessonable, $chapterDir, $chapterSlug, $lessonNumber);
        } elseif ($lessonable instanceof \App\Models\Quiz) {
            return $this->exportQuizLesson($lesson, $lessonable, $chapterDir, $chapterSlug, $lessonNumber);
        }

        return null;
    }

    private function exportVideoLesson($lesson, $videoContent, string $chapterDir, string $chapterSlug, int $lessonNumber): array
    {
        $lessonSlug = 'lesson_'.$lessonNumber.'_video_'.Str::slug($lesson->title);
        $lessonDir = $chapterDir.'/'.$lessonSlug;

        if (! is_dir($lessonDir)) {
            mkdir($lessonDir, 0755, true);
        }

        $files = [];
        $videoFile = null;

        // Copier la vidéo si elle existe
        if ($videoContent->video_path && Storage::disk('public')->exists($videoContent->video_path)) {
            $sourcePath = Storage::disk('public')->path($videoContent->video_path);
            $fileName = basename($videoContent->video_path);

            if (! empty($fileName)) {
                $destPath = $lessonDir.'/'.$fileName;

                // S'assurer que le répertoire destination existe
                if (! is_dir($lessonDir)) {
                    mkdir($lessonDir, 0755, true);
                }

                // Vérifier que le fichier source est bien un fichier
                if (is_file($sourcePath)) {
                    copy($sourcePath, $destPath);
                    $videoFile = $lessonSlug.'/'.$fileName;
                    $files[] = $videoFile;
                }
            }
        }

        // Exporter les métadonnées
        $metadata = [
            'type' => 'video',
            'title' => $lesson->title,
            'description' => $videoContent->description,
            'video_url' => $videoContent->video_url,
            'video_path' => $videoContent->video_path,
            'duration_minutes' => $videoContent->duration_minutes,
        ];

        $metadataFile = 'chapters/'.$chapterSlug.'/'.$lessonSlug.'/metadata.json';
        file_put_contents(
            $lessonDir.'/metadata.json',
            json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return [
            'id' => $lesson->id,
            'type' => 'video',
            'slug' => $lessonSlug,
            'metadata_file' => $metadataFile,
            'files' => array_merge([$metadataFile], array_map(fn ($f) => 'chapters/'.$chapterSlug.'/'.$f, $files)),
            'video_file' => $videoFile ? 'chapters/'.$chapterSlug.'/'.$videoFile : null,
        ];
    }

    private function exportTextLesson($lesson, $textContent, string $chapterDir, string $chapterSlug, int $lessonNumber): array
    {
        $lessonSlug = 'lesson_'.$lessonNumber.'_text_'.Str::slug($lesson->title);
        $lessonDir = $chapterDir.'/'.$lessonSlug;

        if (! is_dir($lessonDir)) {
            mkdir($lessonDir, 0755, true);
        }

        $files = [];

        // Exporter le contenu texte
        $contentFile = $lessonSlug.'/content.html';
        file_put_contents($lessonDir.'/content.html', $textContent->content);
        $files[] = $contentFile;

        // Copier les pièces jointes
        $attachments = [];
        foreach ($textContent->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path) && ! empty($attachment->original_name)) {
                $sourcePath = Storage::disk('public')->path($attachment->file_path);
                $destPath = $lessonDir.'/'.$attachment->original_name;

                // S'assurer que le répertoire destination existe
                if (! is_dir($lessonDir)) {
                    mkdir($lessonDir, 0755, true);
                }

                // Vérifier que le fichier source est bien un fichier et pas un répertoire
                if (is_file($sourcePath)) {
                    copy($sourcePath, $destPath);
                    $attachmentFile = $lessonSlug.'/'.$attachment->original_name;
                    $files[] = $attachmentFile;
                    $attachments[] = $attachmentFile;
                }
            }
        }

        // Exporter les métadonnées
        $metadata = [
            'type' => 'text',
            'title' => $lesson->title,
            'description' => $textContent->description,
            'estimated_read_time' => $textContent->estimated_read_time,
            'allow_download' => $textContent->allow_download,
            'show_progress' => $textContent->show_progress,
            'attachments_count' => $textContent->attachments->count(),
        ];

        $metadataFile = 'chapters/'.$chapterSlug.'/'.$lessonSlug.'/metadata.json';
        file_put_contents(
            $lessonDir.'/metadata.json',
            json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return [
            'id' => $lesson->id,
            'type' => 'text',
            'slug' => $lessonSlug,
            'metadata_file' => $metadataFile,
            'files' => array_merge([$metadataFile], array_map(fn ($f) => 'chapters/'.$chapterSlug.'/'.$f, $files)),
            'content_file' => 'chapters/'.$chapterSlug.'/'.$contentFile,
            'attachments' => array_map(fn ($f) => 'chapters/'.$chapterSlug.'/'.$f, $attachments),
        ];
    }

    private function exportQuizLesson($lesson, $quiz, string $chapterDir, string $chapterSlug, int $lessonNumber): array
    {
        $lessonSlug = 'lesson_'.$lessonNumber.'_quiz_'.Str::slug($lesson->title);
        $lessonDir = $chapterDir.'/'.$lessonSlug;

        if (! is_dir($lessonDir)) {
            mkdir($lessonDir, 0755, true);
        }

        // Exporter les données du quiz
        $quizData = [
            'title' => $lesson->title,
            'description' => $quiz->description,
            'passing_score' => $quiz->passing_score,
            'max_attempts' => $quiz->max_attempts,
            'estimated_duration_minutes' => $quiz->estimated_duration_minutes,
            'questions' => $quiz->quizQuestions->map(function ($question) {
                return [
                    'question' => $question->question,
                    'type' => $question->type,
                    'points' => $question->points,
                    'choices' => $question->quizChoices->map(function ($choice) {
                        return [
                            'choice' => $choice->choice,
                            'is_correct' => $choice->is_correct,
                        ];
                    }),
                ];
            }),
        ];

        $quizFile = $lessonSlug.'/quiz.json';
        file_put_contents(
            $lessonDir.'/quiz.json',
            json_encode($quizData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // Exporter les métadonnées
        $metadata = [
            'type' => 'quiz',
            'title' => $lesson->title,
            'questions_count' => $quiz->quizQuestions->count(),
            'estimated_duration_minutes' => $quiz->estimated_duration_minutes,
        ];

        $metadataFile = 'chapters/'.$chapterSlug.'/'.$lessonSlug.'/metadata.json';
        file_put_contents(
            $lessonDir.'/metadata.json',
            json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return [
            'id' => $lesson->id,
            'type' => 'quiz',
            'slug' => $lessonSlug,
            'metadata_file' => $metadataFile,
            'files' => [$metadataFile, 'chapters/'.$chapterSlug.'/'.$quizFile],
            'quiz_file' => 'chapters/'.$chapterSlug.'/'.$quizFile,
        ];
    }

    private function exportCompletionDocuments(Formation $formation, string $formationDir): array
    {
        $completionDir = $formationDir.'/completion_documents';
        $files = [];
        $documents = [];

        foreach ($formation->completionDocuments as $document) {
            if (Storage::disk('public')->exists($document->file_path) && ! empty($document->original_name)) {
                $sourcePath = Storage::disk('public')->path($document->file_path);
                $destPath = $completionDir.'/'.$document->original_name;

                // S'assurer que le répertoire destination existe
                if (! is_dir($completionDir)) {
                    mkdir($completionDir, 0755, true);
                }

                // Vérifier que le fichier source est bien un fichier
                if (is_file($sourcePath)) {
                    copy($sourcePath, $destPath);
                    $documentFile = 'completion_documents/'.$document->original_name;
                    $files[] = $documentFile;

                    $documents[] = [
                        'id' => $document->id,
                        'title' => $document->title,
                        'original_name' => $document->original_name,
                        'file_path' => $documentFile,
                        'mime_type' => $document->mime_type,
                        'size' => $document->size,
                    ];
                }
            }
        }

        // Liste des documents
        $documentsListFile = 'completion_documents/documents_list.json';
        $documentsList = $formation->completionDocuments->map(function ($document) {
            return [
                'title' => $document->title,
                'original_name' => $document->original_name,
                'mime_type' => $document->mime_type,
                'size' => $document->size,
            ];
        });

        file_put_contents(
            $completionDir.'/documents_list.json',
            json_encode($documentsList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return [
            'directory' => 'completion_documents',
            'documents_list_file' => $documentsListFile,
            'files' => array_merge([$documentsListFile], $files),
            'documents' => $documents,
        ];
    }

    private function createZip(string $sourceDir, string $zipPath): void
    {
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $this->addDirectoryToZip($zip, $sourceDir, basename($sourceDir));
            $zip->close();
        } else {
            throw new \Exception('Impossible de créer le fichier ZIP');
        }
    }

    private function addDirectoryToZip(ZipArchive $zip, string $dir, string $relativePath = ''): void
    {
        $dirHandle = opendir($dir);

        while (($file = readdir($dirHandle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $dir.'/'.$file;
            $relativeFilePath = $relativePath ? $relativePath.'/'.$file : $file;

            if (is_dir($filePath)) {
                $zip->addEmptyDir($relativeFilePath);
                $this->addDirectoryToZip($zip, $filePath, $relativeFilePath);
            } else {
                $zip->addFile($filePath, $relativeFilePath);
            }
        }

        closedir($dirHandle);
    }

    private function cleanupTempDir(string $tempDir): void
    {
        if (is_dir($tempDir)) {
            $this->deleteDirectory($tempDir);
        }
    }

    private function createOrchestreFile(Formation $formation, string $formationDir, array $chaptersStructure, array $completionDocumentsStructure): void
    {
        // Collecter tous les fichiers exportés
        $allFiles = [];

        // Fichier de métadonnées de la formation
        $allFiles[] = 'formation_metadata.json';

        // Fichiers des chapitres
        foreach ($chaptersStructure as $chapter) {
            $allFiles[] = $chapter['metadata_file'];

            foreach ($chapter['lessons'] as $lesson) {
                $allFiles = array_merge($allFiles, $lesson['files']);
            }
        }

        // Fichiers des documents de completion
        $allFiles = array_merge($allFiles, $completionDocumentsStructure['files']);

        // Créer la structure orchestre
        $orchestre = [
            'version' => '1.0',
            'export_info' => [
                'formation_id' => $formation->id,
                'formation_title' => $formation->title,
                'exported_at' => now()->toISOString(),
                'export_version' => '1.0',
                'total_files' => count($allFiles),
            ],
            'structure' => [
                'formation_metadata' => 'formation_metadata.json',
                'chapters' => $chaptersStructure,
                'completion_documents' => $completionDocumentsStructure,
            ],
            'files_index' => $allFiles,
            'import_instructions' => [
                'step_1' => 'Lire formation_metadata.json pour les informations générales',
                'step_2' => 'Pour chaque chapitre dans chapters[], lire le metadata_file',
                'step_3' => 'Pour chaque leçon, traiter selon le type (video/text/quiz)',
                'step_4' => 'Traiter les documents de completion depuis completion_documents/',
                'step_5' => 'Recréer la structure de données en base',
            ],
        ];

        file_put_contents(
            $formationDir.'/orchestre.json',
            json_encode($orchestre, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

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
