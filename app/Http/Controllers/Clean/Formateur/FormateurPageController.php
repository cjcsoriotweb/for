<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Formation;
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

            // Valider la structure attendue
            if (!isset($data['title']) || !isset($data['chapters']) || !is_array($data['chapters'])) {
                return back()->with('error', 'Structure JSON invalide. Le fichier doit contenir au minimum "title" et "chapters" (array).');
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
                'user_id' => Auth::id(),
                'active' => false, // Désactivée par défaut jusqu'à validation
            ]);

            $totalLessons = 0;

            // Traiter chaque chapitre
            foreach ($data['chapters'] as $chapterIndex => $chapterData) {
                if (!isset($chapterData['title']) || !isset($chapterData['lessons']) || !is_array($chapterData['lessons'])) {
                    DB::rollBack();
                    return back()->with('error', "Chapitre " . ($chapterIndex + 1) . " : structure invalide (title et lessons requis).");
                }

                // Créer le chapitre
                $chapter = Chapter::create([
                    'formation_id' => $formation->id,
                    'title' => $chapterData['title'],
                    'description' => $chapterData['description'] ?? '',
                    'order' => $chapterIndex + 1,
                ]);

                // Traiter chaque leçon du chapitre
                foreach ($chapterData['lessons'] as $lessonIndex => $lessonData) {
                    if (!isset($lessonData['title']) || !isset($lessonData['type']) || !isset($lessonData['content'])) {
                        DB::rollBack();
                        return back()->with('error', "Chapitre '{$chapterData['title']}', leçon " . ($lessonIndex + 1) . " : structure invalide (title, type et content requis).");
                    }

                    // Valider le type de leçon
                    $validTypes = ['text', 'video', 'quiz'];
                    $lessonType = trim(strtolower($lessonData['type'])); // Normaliser le type

                    Log::info('Processing lesson type', [
                        'original_type' => $lessonData['type'],
                        'normalized_type' => $lessonType,
                        'valid_types' => $validTypes,
                        'is_valid' => in_array($lessonType, $validTypes)
                    ]);

                    if (!in_array($lessonType, $validTypes)) {
                        DB::rollBack();
                        return back()->with('error', "Chapitre '{$chapterData['title']}', leçon '{$lessonData['title']}' : type '{$lessonData['type']}' invalide. Types acceptés : " . implode(', ', $validTypes));
                    }

                    // Créer la leçon d'abord (sans lessonable_id pour l'instant)
                    $lesson = Lesson::create([
                        'chapter_id' => $chapter->id,
                        'title' => $lessonData['title'],
                        'lessonable_type' => $this->getLessonableType($lessonType),
                        'order' => $lessonIndex + 1,
                    ]);

                    // Créer le contenu selon le type normalisé avec lesson_id
                    $lessonData['type'] = $lessonType; // Utiliser le type normalisé
                    $content = $this->createLessonContent($lessonData, $lesson->id);

                    // Mettre à jour la leçon avec lessonable_id
                    $lesson->update([
                        'lessonable_id' => $content->id,
                    ]);

                    $totalLessons++;
                }
            }

            DB::commit();

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', "Formation '{$formation->title}' importée avec succès ! {$totalLessons} leçons créées dans " . count($data['chapters']) . " chapitres.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
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

            $message = "Import CSV réussi ! {$stats['formations']} formation(s), {$stats['chapters']} chapitre(s), {$stats['lessons']} leçon(s) importées.";
            return redirect()->route('formateur.home')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
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
}
