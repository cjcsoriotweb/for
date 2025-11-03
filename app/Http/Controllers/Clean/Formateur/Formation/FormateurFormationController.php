<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formateur\Formation\UpdateFormationCoverImageRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationDescriptionRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationRequest;
use App\Http\Requests\Formateur\Formation\UpdateFormationTitleRequest;
use App\Models\Formation;
use App\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormateurFormationController extends Controller
{
    public function showFormation(Formation $formation)
    {
        return view('out-application.formateur.formation.formation-show', compact('formation'));
    }

    public function editFormation(Formation $formation)
    {
        return view('out-application.formateur.formation.formation-edit', compact('formation'));
    }

    public function editFormationTitle(Formation $formation)
    {
        return view('out-application.formateur.formation.edit.title', compact('formation'));
    }

    public function updateFormationTitle(UpdateFormationTitleRequest $request, Formation $formation)
    {
        $formation->update([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Titre mis à jour avec succès.');
    }

    public function editFormationDescription(Formation $formation)
    {
        return view('out-application.formateur.formation.edit.description', compact('formation'));
    }

    public function updateFormationDescription(UpdateFormationDescriptionRequest $request, Formation $formation)
    {
        $formation->update([
            'description' => $request->description,
        ]);

        return back()->with('success', 'Description mise à jour avec succès.');
    }

    public function editFormationCoverImage(Formation $formation)
    {
        return view('out-application.formateur.formation.edit.cover-image', compact('formation'));
    }

    public function updateFormationCoverImage(UpdateFormationCoverImageRequest $request, Formation $formation)
    {
        if ($formation->cover_image_path && Storage::disk('public')->exists($formation->cover_image_path)) {
            Storage::disk('public')->delete($formation->cover_image_path);
        }

        $formation->update([
            'cover_image_path' => $request->file('cover_image')->store('formations/covers', 'public'),
        ]);

        return back()->with('success', 'Image de couverture mise à jour avec succès.');
    }

    public function editPricing(Formation $formation)
    {
        return view('out-application.formateur.formation.formation-pricing', compact('formation'));
    }

    public function manageChapters(Formation $formation)
    {
        return view('out-application.formateur.formation.formation-chapters', compact('formation'));
    }

    // editAi and updateAi methods removed - trainers are now managed in config/ai.php

    public function updateFormation(UpdateFormationRequest $request, Formation $formation)
    {
        $updatePayload = [
            'title' => $request->title,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : $formation->active,
        ];

        if ($request->hasFile('cover_image')) {
            if ($formation->cover_image_path && Storage::disk('public')->exists($formation->cover_image_path)) {
                Storage::disk('public')->delete($formation->cover_image_path);
            }

            $updatePayload['cover_image_path'] = $request->file('cover_image')->store('formations/covers', 'public');
        }

        $formation->update($updatePayload);

        return back()->with('success', 'Formation mise à jour avec succès.');
    }

    public function toggleStatus(Formation $formation)
    {
        $formation->update([
            'active' => ! $formation->active,
        ]);

        $status = $formation->active ? 'activée' : 'désactivée';

        return response()->json([
            'success' => true,
            'message' => "Formation {$status} avec succès.",
            'active' => $formation->active,
        ]);
    }

    public function createFormation(FormationService $formationService)
    {
        $formation = $formationService->createFormation();

        return redirect()->route('formateur.formation.show', [$formation]);
    }
}
