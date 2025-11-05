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
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ]);

        // TODO: Implement CSV import logic
        return back()->with('success', 'Import CSV - Fonctionnalité à implémenter');
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
