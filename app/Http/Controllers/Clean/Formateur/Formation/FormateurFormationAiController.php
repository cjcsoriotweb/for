<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Models\AiTrainer;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormateurFormationAiController extends Controller
{
    public function edit(Formation $formation)
    {
        $trainers = AiTrainer::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('out-application.formateur.formation.formation-ia', [
            'formation' => $formation,
            'trainers' => $trainers,
            'primaryTrainerId' => $formation->primary_ai_trainer_id,
        ]);
    }

    public function update(Request $request, Formation $formation)
    {
        $data = $request->validate([
            'primary_trainer_id' => [
                'nullable',
                'integer',
                Rule::exists('ai_trainers', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
        ]);

        $formation->update([
            'primary_ai_trainer_id' => $data['primary_trainer_id'] ?? null,
        ]);

        return redirect()
            ->route('formateur.formation.ai.edit', $formation)
            ->with('success', __('Le formateur IA a ete mis a jour.'));
    }
}
