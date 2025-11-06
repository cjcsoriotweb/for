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
    /**
     * CSV expected headers for import.
     */
    private const CSV_HEADERS = [
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

    /**
     * Display the formateur home page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function home()
    {
        return view('out-application.formateur.formateur-home-page');
    }

    /**
     * Display the import page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function import()
    {
        return view('out-application.formateur.formateur-import-page');
    }

    /**
     * Import a formation from a JSON file.
     *
     * @param Request $request The HTTP request containing the JSON file
     * @return \Illuminate\Http\RedirectResponse
     */
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

                    // Validation spécifique pour les quiz
                    if ($lessonType === 'quiz' && isset($lesson['questions'])) {
                        if (!is_array($lesson['questions']) || empty($lesson['questions'])) {
                            return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}' : un quiz doit contenir au moins une question");
                        }

                        foreach ($lesson['questions'] as $qIndex => $question) {
                            if (!isset($question['question']) || empty(trim($question['question']))) {
                                return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}', question #" . ($qIndex + 1) . " : le texte de la question est requis");
                            }

                            if (!isset($question['choices']) || !is_array($question['choices']) || count($question['choices']) < 2) {
                                return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}', question #" . ($qIndex + 1) . " : une question doit avoir au moins 2 choix");
                            }

                            $correctCount = 0;
                            foreach ($question['choices'] as $cIndex => $choice) {
                                if (!isset($choice['choice']) || empty(trim($choice['choice']))) {
                                    return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}', question #" . ($qIndex + 1) . ", choix #" . ($cIndex + 1) . " : le texte du choix est requis");
                                }

                                if (isset($choice['is_correct']) && $choice['is_correct']) {
                                    $correctCount++;
                                }
                            }

                            if ($correctCount === 0) {
                                return back()->with('error', "Chapitre '{$chapter['title']}', leçon '{$lesson['title']}', question #" . ($qIndex + 1) . " : au moins un choix doit être marqué comme correct");
                            }
                        }
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

    /**
     * Get the lessonable type class name for the given lesson type.
     *
     * @param string $type The lesson type (text, video, quiz)
     * @return string The fully qualified class name
     */
    private function getLessonableType(string $type): string
    {
        return match ($type) {
            'text' => TextContent::class,
            'video' => VideoContent::class,
            'quiz' => Quiz::class,
            default => TextContent::class,
        };
    }

    /**
     * Create lesson content based on the lesson type.
     *
     * @param array<string, mixed> $lessonData The lesson data
     * @param int $lessonId The lesson ID
     * @return VideoContent|TextContent|Quiz
     */
    private function createLessonContent(array $lessonData, int $lessonId)
    {
        \Illuminate\Support\Facades\Log::info('Creating lesson content', [
            'lesson_id' => $lessonId,
            'type' => $lessonData['type'],
            'has_questions' => isset($lessonData['questions']),
            'lesson_data_keys' => array_keys($lessonData)
        ]);

        switch ($lessonData['type']) {
            case 'quiz':
                return $this->createQuizContent($lessonData, $lessonId);

            case 'video':
                return $this->createVideoContent($lessonData, $lessonId);

            case 'text':
            default:
                return $this->createTextContent($lessonData, $lessonId);
        }
    }

    /**
     * Create quiz content with questions and choices.
     *
     * @param array<string, mixed> $lessonData The lesson data
     * @param int $lessonId The lesson ID
     * @return Quiz
     */
    private function createQuizContent(array $lessonData, int $lessonId)
    {
        \Illuminate\Support\Facades\Log::info('Creating quiz content', [
            'lesson_id' => $lessonId,
            'lesson_title' => $lessonData['title'],
            'has_questions' => isset($lessonData['questions']),
            'questions_count' => isset($lessonData['questions']) ? count($lessonData['questions']) : 0,
        ]);

        // Créer le quiz
        $quizData = [
            'lesson_id' => $lessonId,
            'title' => $lessonData['title'],
            'description' => $lessonData['content'] ?? 'Quiz importé',
            'passing_score' => 50,
            'max_attempts' => 3,
            'type' => Quiz::TYPE_LESSON,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $quizId = DB::table('quizzes')->insertGetId($quizData);
        $quiz = Quiz::find($quizId);

        \Illuminate\Support\Facades\Log::info('Quiz created', ['quiz_id' => $quizId]);

        // Créer les questions et choix si présents
        if (isset($lessonData['questions']) && is_array($lessonData['questions'])) {
            \Illuminate\Support\Facades\Log::info('Processing questions', ['count' => count($lessonData['questions'])]);

            foreach ($lessonData['questions'] as $qIndex => $questionData) {
                \Illuminate\Support\Facades\Log::info('Processing question', [
                    'index' => $qIndex,
                    'question_text' => $questionData['question'] ?? 'MISSING',
                    'has_choices' => isset($questionData['choices']),
                    'choices_count' => isset($questionData['choices']) ? count($questionData['choices']) : 0,
                ]);

                $question = \App\Models\QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'] ?? 'multiple_choice',
                    'points' => $questionData['points'] ?? 1,
                ]);

                \Illuminate\Support\Facades\Log::info('Question created', ['question_id' => $question->id]);

                // Créer les choix
                if (isset($questionData['choices']) && is_array($questionData['choices'])) {
                    foreach ($questionData['choices'] as $cIndex => $choiceData) {
                        \Illuminate\Support\Facades\Log::info('Processing choice', [
                            'question_id' => $question->id,
                            'choice_index' => $cIndex,
                            'choice_text' => $choiceData['choice'] ?? 'MISSING',
                            'is_correct' => $choiceData['is_correct'] ?? false,
                        ]);

                        // Validation : s'assurer que choice_text n'est pas null ou vide
                        $choiceText = trim($choiceData['choice'] ?? '');
                        if (empty($choiceText)) {
                            throw new \Exception("Leçon '{$lessonData['title']}', question #" . ($qIndex + 1) . ", choix #" . ($cIndex + 1) . " : le texte du choix ne peut pas être vide");
                        }

                        \App\Models\QuizChoice::create([
                            'question_id' => $question->id,
                            'choice_text' => $choiceText,
                            'is_correct' => $choiceData['is_correct'] ?? false,
                        ]);

                        \Illuminate\Support\Facades\Log::info('Choice created for question', ['question_id' => $question->id]);
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('No choices found for question', ['question_id' => $question->id]);
                }
            }
        } else {
            \Illuminate\Support\Facades\Log::info('No questions to process for quiz', ['quiz_id' => $quizId]);
        }

        return $quiz;
    }

    /**
     * Create video content for a lesson.
     *
     * @param array<string, mixed> $lessonData The lesson data
     * @param int $lessonId The lesson ID
     * @return VideoContent
     */
    private function createVideoContent(array $lessonData, int $lessonId)
    {
        $videoData = [
            'lesson_id' => $lessonId,
            'title' => $lessonData['title'],
            'description' => $lessonData['content'] ?? 'Vidéo importée',
            'video_url' => $lessonData['content'] ?? null,
            'duration_minutes' => $lessonData['duration_minutes'] ?? 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $videoId = DB::table('video_contents')->insertGetId($videoData);
        return VideoContent::find($videoId);
    }

    /**
     * Create text content for a lesson.
     *
     * @param array<string, mixed> $lessonData The lesson data
     * @param int $lessonId The lesson ID
     * @return TextContent
     */
    private function createTextContent(array $lessonData, int $lessonId)
    {
        $textData = [
            'lesson_id' => $lessonId,
            'title' => $lessonData['title'],
            'content' => $lessonData['content'],
            'estimated_read_time' => $lessonData['estimated_read_time'] ?? 5,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $textId = DB::table('text_contents')->insertGetId($textData);
        return TextContent::find($textId);
    }

    /**
     * Import a formation from a CSV file.
     *
     * @param Request $request The HTTP request containing the CSV file
     * @return \Illuminate\Http\RedirectResponse
     */
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
            
            // Support pour séparateur virgule ou point-virgule
            if (count($header) === 1 && strpos($header[0], ';') !== false) {
                $csvData = array_map(function($row) {
                    return str_getcsv($row[0], ';');
                }, $csvData);
                $header = array_map('trim', $csvData[0]);
            }

            // Validation de l'en-tête
            if (count($header) < count(self::CSV_HEADERS) - 2) { // Allow some flexibility
                return back()->with('error', 'En-tête CSV invalide. Veuillez utiliser le template fourni ou vérifier les colonnes requises.');
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
                    // Check if formation exists for this user
                    $existingFormation = Formation::where('title', $formationTitle)
                        ->where('user_id', Auth::id())
                        ->first();
                    
                    if ($existingFormation) {
                        // Use existing formation
                        $currentFormation = $existingFormation;
                    } else {
                        // Create new formation
                        $currentFormation = Formation::create([
                            'title' => $formationTitle,
                            'description' => $formationDesc,
                            'level' => $formationLevel,
                            'active' => false,
                            'user_id' => Auth::id(),
                        ]);
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

    /**
     * Create lesson content from CSV row data.
     *
     * @param int $lessonId The lesson ID
     * @param string $type The lesson type
     * @param string $title The lesson title
     * @param string $content The lesson content
     * @param int $duration The lesson duration
     * @return VideoContent|TextContent|Quiz
     */
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

    /**
     * Import a SCORM package (not yet implemented).
     *
     * @param Request $request The HTTP request containing the SCORM file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importScorm(Request $request)
    {
        $request->validate([
            'scorm_file' => 'required|file|mimes:zip|max:51200', // 50MB max
        ]);

        // TODO: Implement SCORM import logic
        return back()->with('success', 'Import SCORM - Fonctionnalité à implémenter');
    }

    /**
     * Download a CSV template file for formation import.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Download a JSON template file for formation import.
     *
     * @return \Illuminate\Http\Response
     */
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
                            'content' => 'Quiz avec questions détaillées',
                            'position' => 2,
                            'questions' => [
                                [
                                    'question' => 'Quelle est la capitale de la France ?',
                                    'type' => 'multiple_choice',
                                    'points' => 1,
                                    'choices' => [
                                        [
                                            'choice' => 'Paris',
                                            'is_correct' => true,
                                        ],
                                        [
                                            'choice' => 'Lyon',
                                            'is_correct' => false,
                                        ],
                                        [
                                            'choice' => 'Marseille',
                                            'is_correct' => false,
                                        ],
                                        [
                                            'choice' => 'Toulouse',
                                            'is_correct' => false,
                                        ],
                                    ],
                                ],
                                [
                                    'question' => 'Cochez les langages de programmation :',
                                    'type' => 'multiple_choice',
                                    'points' => 2,
                                    'choices' => [
                                        [
                                            'choice' => 'PHP',
                                            'is_correct' => true,
                                        ],
                                        [
                                            'choice' => 'HTML',
                                            'is_correct' => false,
                                        ],
                                        [
                                            'choice' => 'JavaScript',
                                            'is_correct' => true,
                                        ],
                                        [
                                            'choice' => 'CSS',
                                            'is_correct' => false,
                                        ],
                                    ],
                                ],
                            ],
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
