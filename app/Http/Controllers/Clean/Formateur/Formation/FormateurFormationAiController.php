<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormateurFormationAiController extends Controller
{
    public function edit(Formation $formation)
    {
        $categories = FormationCategory::query()
            ->with('aiTrainer')
            ->orderBy('name')
            ->get();

        return view('out-application.formateur.formation.formation-ia', [
            'formation' => $formation,
            'categories' => $categories,
            'selectedCategoryId' => $formation->formation_category_id,
        ]);
    }

    public function update(Request $request, Formation $formation)
    {
        $data = $request->validate([
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('formation_categories', 'id'),
            ],
        ]);

        $formation->update([
            'formation_category_id' => $data['category_id'] ?? null,
        ]);

        return redirect()
            ->route('formateur.formation.ai.edit', $formation)
            ->with('success', __('La categorie de formation a ete mise a jour.'));
    }
}
