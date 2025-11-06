<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\FormationImportExportLog;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\TextContent;
use App\Models\VideoContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormateurPageController extends Controller
{
    public function home()
    {
        return view('out-application.formateur.formateur-home-page');
    }

    public function import()
    {
        return view('out-application.formateur.formateur-import-page');
    }

    public function importJson(Request $request)
    {
        try {
            // Validation du fichier
            $request->validate([
                'json_file' => 'required|file|mimes:json|max:10240', // 10MB max
            ]);

            Log::info('Import JSON started', [
                'user_id' => Auth::id(),
                'file_name' => $request->file('json_file')->getClientOriginalName(),
                'file_size' => $request->file('json_file')->getSize()
            ]);

            // Lire le contenu du fichier JSON
            $jsonContent = file_get_contents($request->file('json_file')->getRealPath());

            if ($jsonContent === false) {
                return back()->with('error', 'Impossible de lire le contenu du fichier JSON.');
            }

            $data = json_decode($jsonContent, true);

            // Valider la structure JSON de base
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Le fichier JSON n\'est pas valide : ' . json_last_error_msg());
            }

            // Valider la structure attendue avec messages détaillés
            $errors = [];
            
            if (!isset($data['title']) || empty(trim($data['title']))) {
                $errors[] = 'Le champ "title" est requis et ne peut pas être vide';
            }
            
            if (!isset($data['chapters'])) {
                $errors[] = 'Le champ "chapters" est requis';
            } elseif (!is_array($data['chapters'])) {
                $errors[] = 'Le champ "chapters" doit être un tableau';
            } elseif (empty($data['chapters'])) {
                $errors[] = 'La formation doit contenir au moins un chapitre';
            }
            
            if (!empty($errors)) {
                return back()->with('error', 'Structure JSON invalide : ' . implode(', ', $errors));
            }

            // Valider chaque chapitre
            foreach ($data['chapters'] as $index => $chapter) {
                if (!isset($chapter['title']) || empty(trim($chapter['title']))) {
                    return back()->with('error', "Chapitre #" . ($index + 1) . " : le champ 'title' est requis");
                }
                
                if (!isset($chapter['lessons']) || !is_array($chapter['lessons'])) {
                    return back()->with('error', "Chapitre '{$chapter['title']}' : le champ 'lessons' doit être un tableau");
                }
                
                if (empty($chapter['lessons'])) {
                    return back()->with('error', "Chapitre '{$chapter['title']}' : au moins une leçon est requise");
                }
                
                // Valider chaque leçon
                foreach ($chapter['lessons'] as $lessonIndex => $lesson) {
                    if (!isset($lesson['title']) || empty(trim($lesson['title']))) {
                        return back()->with('error', "Chapitre '{$chapter['title']}', leçon #" . ($lessonIndex + 1) . " : le champ 'title' est requis");
                    }
                    
                    if (!isset($lesson['type'])) {
                        return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}' : le champ 'type' est requis");
                    }
                    
                    $validTypes = ['text', 'video', 'quiz'];
                    $lessonType = trim(strtolower($lesson['type']));
                    
                    if (!in_array($lessonType, $validTypes)) {
                        return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}' : type '{$lesson['type']}' invalide. Types acceptés : " . implode(', ', $validTypes));
                    }
                    
                    if (!isset($lesson['content'])) {
                        return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}' : le champ 'content' est requis");
                    }
                }
            }

            Log::info('JSON structure validated', [
                'title' => $data['title'],
                'chapters_count' => count($data['chapters'])
            ]);

            DB::beginTransaction();

            // Créer la formation
            $formation = Formation::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? '',
                'level' => $data['level'] ?? 'beginner',
                'user_id' => Auth::id(),
                'active' => false, // Désactivée par défaut jusqu'à validation
            ]);

            $totalLessons = 0;

            // Traiter chaque chapitre
            foreach ($data['chapters'] as $chapterIndex => $chapterData) {
                // Créer le chapitre
                $chapter = Chapter::create([
                    'formation_id' => $formation->id,
                    'title' => $chapterData['title'],
                    'description' => $chapterData['description'] ?? '',
                    'position' => $chapterData['position'] ?? ($chapterIndex + 1),
                ]);

                // Traiter chaque leçon du chapitre
                foreach ($chapterData['lessons'] as $lessonIndex => $lessonData) {
                    $lessonType = trim(strtolower($lessonData['type']));

                    // Créer la leçon d'abord (sans lessonable_id pour l'instant)
                    $lesson = Lesson::create([
                        'chapter_id' => $chapter->id,
                        'title' => $lessonData['title'],
                        'lessonable_type' => $this->getLessonableType($lessonType),
                        'position' => $lessonData['position'] ?? ($lessonIndex + 1),
                    ]);

                    // Créer le contenu selon le type avec lesson_id
                    $lessonData['type'] = $lessonType;
                    $content = $this->createLessonContent($lessonData, $lesson->id);

                    // Mettre à jour la leçon avec lessonable_id
                    $lesson->update([
                        'lessonable_id' => $content->id,
                    ]);

                    $totalLessons++;
                }
            }

            DB::commit();

            Log::info('JSON Import completed', [
                'formation_id' => $formation->id,
                'total_lessons' => $totalLessons
            ]);

            // Log successful import
            FormationImportExportLog::create([
                'user_id' => Auth::id(),
                'formation_id' => $formation->id,
                'type' => 'import',
                'format' => 'json',
                'filename' => $request->file('json_file')->getClientOriginalName(),
                'status' => 'success',
                'stats' => [
                    'chapters_count' => count($data['chapters']),
                    'lessons_count' => $totalLessons,
                ],
                'file_size' => $request->file('json_file')->getSize(),
            ]);

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', "Formation '{$formation->title}' importée avec succès ! {$totalLessons} leçons créées dans " . count($data['chapters']) . " chapitres.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log failed import
            if (isset($request) && $request->hasFile('json_file')) {
                FormationImportExportLog::create([
                    'user_id' => Auth::id(),
                    'type' => 'import',
                    'format' => 'json',
                    'filename' => $request->file('json_file')->getClientOriginalName(),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'file_size' => $request->file('json_file')->getSize(),
                ]);
            }
            
            Log::error('JSON Import Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'import JSON : ' . $e->getMessage());
        }
    }

    private function getLessonableType(string $type): string
    {
        return match ($type) {
            'text' => TextContent::class,
            'video' => VideoContent::class,
            'quiz' => Quiz::class,
            default => TextContent::class,
        };
    }

    private function createLessonContent(array $lessonData, int $lessonId)
    {
        // Utiliser DB::insert pour contourner complètement le fillable
        $data = [
            'lesson_id' => $lessonId,
            'title' => $lessonData['title'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        switch ($lessonData['type']) {
            case 'text':
                $data['content'] = $lessonData['content'];
                $data['estimated_read_time'] = $lessonData['estimated_read_time'] ?? 5;
                break;

            case 'video':
                // Pour l'instant, créer un contenu texte avec l'URL de la vidéo
                $data['content'] = "Vidéo : " . ($lessonData['content'] ?? 'URL non spécifiée');
                $data['estimated_read_time'] = $lessonData['duration_minutes'] ?? 10;
                break;

            case 'quiz':
                // Pour l'instant, créer un contenu texte avec les questions
                $data['content'] = "Quiz : " . ($lessonData['content'] ?? 'Questions à définir');
                $data['estimated_read_time'] = 15;
                break;

            default:
                $data['content'] = $lessonData['content'] ?? 'Contenu non défini';
                $data['estimated_read_time'] = 5;
                break;
        }

        // Insérer directement en base pour éviter le fillable
        $id = DB::table('text_contents')->insertGetId($data);

        // Retourner l'instance du modèle
        return TextContent::find($id);
    }

    public function importCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
            ]);

            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            
            if (empty($csvData)) {
                return back()->with('error', 'Le fichier CSV est vide.');
            }

            // Vérifier l'en-tête
            $header = array_map('trim', $csvData[0]);
            $expectedHeaders = ['Formation', 'Description Formation', 'Niveau', 'Chapitre', 'Position Chapitre', 'Leçon', 'Type Leçon', 'Contenu', 'Durée (minutes)', 'Position Leçon'];
            
            // Support pour séparateur virgule ou point-virgule
            if (count($header) === 1 && strpos($header[0], ';') !== false) {
                $csvData = array_map(function($row) {
                    return str_getcsv($row[0], ';');
                }, $csvData);
                $header = array_map('trim', $csvData[0]);
            }

            Log::info('CSV Import - Headers found', ['headers' => $header]);

            DB::beginTransaction();

            $formations = [];
            $currentFormation = null;
            $currentChapter = null;
            $stats = ['formations' => 0, 'chapters' => 0, 'lessons' => 0];

            // Traiter chaque ligne (skip header)
            for ($i = 1; $i < count($csvData); $i++) {
                $row = $csvData[$i];
                
                if (count($row) < 7) {
                    continue; // Ligne incomplète
                }

                $formationTitle = trim($row[0] ?? '');
                $formationDesc = trim($row[1] ?? '');
                $formationLevel = trim($row[2] ?? 'beginner');
                $chapterTitle = trim($row[3] ?? '');
                $chapterPosition = (int)($row[4] ?? 0);
                $lessonTitle = trim($row[5] ?? '');
                $lessonType = strtolower(trim($row[6] ?? 'text'));
                $lessonContent = trim($row[7] ?? '');
                $lessonDuration = (int)($row[8] ?? 5);
                $lessonPosition = (int)($row[9] ?? 0);

                if (empty($formationTitle) || empty($chapterTitle) || empty($lessonTitle)) {
                    continue; // Ligne invalide
                }

                // Créer ou récupérer la formation
                if (!$currentFormation || $currentFormation->title !== $formationTitle) {
                    $currentFormation = Formation::firstOrCreate(
                        [
                            'title' => $formationTitle,
                            'user_id' => Auth::id(),
                        ],
                        [
                            'description' => $formationDesc,
                            'level' => $formationLevel,
                            'active' => false,
                        ]
                    );
                    
                    if ($currentFormation->wasRecentlyCreated) {
                        $stats['formations']++;
                    }
                }

                // Créer ou récupérer le chapitre
                if (!$currentChapter || $currentChapter->title !== $chapterTitle || $currentChapter->formation_id !== $currentFormation->id) {
                    $currentChapter = Chapter::firstOrCreate(
                        [
                            'formation_id' => $currentFormation->id,
                            'title' => $chapterTitle,
                        ],
                        [
                            'position' => $chapterPosition ?: ($currentFormation->chapters()->count() + 1),
                        ]
                    );
                    
                    if ($currentChapter->wasRecentlyCreated) {
                        $stats['chapters']++;
                    }
                }

                // Créer la leçon
                $lesson = Lesson::create([
                    'chapter_id' => $currentChapter->id,
                    'title' => $lessonTitle,
                    'position' => $lessonPosition ?: ($currentChapter->lessons()->count() + 1),
                    'lessonable_type' => $this->getLessonableType($lessonType),
                ]);

                // Créer le contenu de la leçon
                $content = $this->createLessonContentFromCsv($lesson->id, $lessonType, $lessonTitle, $lessonContent, $lessonDuration);
                $lesson->update(['lessonable_id' => $content->id]);
                
                $stats['lessons']++;
            }

            DB::commit();

            // Log successful import
            FormationImportExportLog::create([
                'user_id' => Auth::id(),
                'type' => 'import',
                'format' => 'csv',
                'filename' => $request->file('csv_file')->getClientOriginalName(),
                'status' => 'success',
                'stats' => $stats,
                'file_size' => $request->file('csv_file')->getSize(),
            ]);

            $message = "Import CSV réussi ! {$stats['formations']} formation(s), {$stats['chapters']} chapitre(s), {$stats['lessons']} leçon(s) importées.";
            return redirect()->route('formateur.home')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log failed import
            if (isset($request) && $request->hasFile('csv_file')) {
                FormationImportExportLog::create([
                    'user_id' => Auth::id(),
                    'type' => 'import',
                    'format' => 'csv',
                    'filename' => $request->file('csv_file')->getClientOriginalName(),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'file_size' => $request->file('csv_file')->getSize(),
                ]);
            }
            
            Log::error('CSV Import Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Erreur lors de l\'import CSV : ' . $e->getMessage());
        }
    }

    private function createLessonContentFromCsv(int $lessonId, string $type, string $title, string $content, int $duration)
    {
        $data = [
            'lesson_id' => $lessonId,
            'title' => $title,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        switch ($type) {
            case 'video':
                $data['video_url'] = $content;
                $data['duration_minutes'] = $duration;
                $data['description'] = 'Vidéo importée depuis CSV';
                $id = DB::table('video_contents')->insertGetId($data);
                return VideoContent::find($id);

            case 'quiz':
                $data['description'] = $content;
                $data['passing_score'] = 50;
                $data['max_attempts'] = 3;
                $data['type'] = Quiz::TYPE_LESSON;
                $id = DB::table('quizzes')->insertGetId($data);
                return Quiz::find($id);

            case 'text':
            default:
                $data['content'] = $content;
                $data['estimated_read_time'] = $duration;
                $data['description'] = 'Contenu importé depuis CSV';
                $id = DB::table('text_contents')->insertGetId($data);
                return TextContent::find($id);
        }
    }

    public function importScorm(Request $request)
    {
        $request->validate([
            'scorm_file' => 'required|file|mimes:zip|max:51200', // 50MB max
        ]);

        // TODO: Implement SCORM import logic
        return back()->with('success', 'Import SCORM - Fonctionnalité à implémenter');
    }

    public function downloadCsvTemplate()
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

        // Exemples de données
        $rows[] = [
            'Formation de démonstration',
            'Ceci est un exemple de formation pour montrer le format CSV',
            'beginner',
            'Introduction',
            '1',
            'Bienvenue dans cette formation',
            'text',
            'Bienvenue ! Cette leçon d\'introduction vous explique les objectifs de la formation.',
            '5',
            '1',
        ];

        $rows[] = [
            'Formation de démonstration',
            'Ceci est un exemple de formation pour montrer le format CSV',
            'beginner',
            'Introduction',
            '1',
            'Vidéo de présentation',
            'video',
            'https://www.youtube.com/watch?v=exemple',
            '10',
            '2',
        ];

        $rows[] = [
            'Formation de démonstration',
            'Ceci est un exemple de formation pour montrer le format CSV',
            'beginner',
            'Chapitre 1 - Les bases',
            '2',
            'Premiers concepts',
            'text',
            'Dans cette leçon nous allons voir les concepts fondamentaux.',
            '15',
            '1',
        ];

        $rows[] = [
            'Formation de démonstration',
            'Ceci est un exemple de formation pour montrer le format CSV',
            'beginner',
            'Chapitre 1 - Les bases',
            '2',
            'Quiz de validation',
            'quiz',
            'Quiz avec 5 questions pour valider vos connaissances',
            '10',
            '2',
        ];

        $fileName = 'modele_import_formation_'.now()->format('Y-m-d').'.csv';
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

    public function downloadJsonTemplate()
    {
        $template = [
            'title' => 'Formation de démonstration',
            'description' => 'Ceci est un exemple de formation pour montrer le format JSON',
            'level' => 'beginner',
            'chapters' => [
                [
                    'title' => 'Introduction',
                    'description' => 'Chapitre d\'introduction',
                    'position' => 1,
                    'lessons' => [
                        [
                            'title' => 'Bienvenue dans cette formation',
                            'type' => 'text',
                            'content' => 'Bienvenue ! Cette leçon d\'introduction vous explique les objectifs de la formation.',
                            'estimated_read_time' => 5,
                            'position' => 1,
                        ],
                        [
                            'title' => 'Vidéo de présentation',
                            'type' => 'video',
                            'content' => 'https://www.youtube.com/watch?v=exemple',
                            'duration_minutes' => 10,
                            'position' => 2,
                        ],
                    ],
                ],
                [
                    'title' => 'Chapitre 1 - Les bases',
                    'description' => 'Premier chapitre du contenu',
                    'position' => 2,
                    'lessons' => [
                        [
                            'title' => 'Premiers concepts',
                            'type' => 'text',
                            'content' => 'Dans cette leçon nous allons voir les concepts fondamentaux.',
                            'estimated_read_time' => 15,
                            'position' => 1,
                        ],
                        [
                            'title' => 'Quiz de validation',
                            'type' => 'quiz',
                            'content' => 'Quiz avec 5 questions pour valider vos connaissances',
                            'position' => 2,
                        ],
                    ],
                ],
            ],
        ];

        $fileName = 'modele_import_formation_'.now()->format('Y-m-d').'.json';

        return response()->json($template, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
