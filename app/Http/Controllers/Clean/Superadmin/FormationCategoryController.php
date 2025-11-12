<?php

namespace App\Http\Controllers\Clean\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\FormationCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormationCategoryController extends Controller
{
    public function index()
    {
        $categories = FormationCategory::query()
            ->withCount('formations')
            ->orderBy('name')
            ->get();

        return view('out-application.superadmin.formation-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:formation_categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $request->user()->formationCategories()->create($data);

        return redirect()
            ->route('superadmin.formation-categories.index')
            ->with('success', __('Categorie creee avec succes.'));
    }

    public function update(Request $request, FormationCategory $formationCategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('formation_categories', 'name')->ignore($formationCategory->id)],
            'description' => ['nullable', 'string'],
        ]);

        $formationCategory->update($data);

        return redirect()
            ->route('superadmin.formation-categories.index')
            ->with('success', __('Categorie mise a jour.'));
    }

    public function destroy(FormationCategory $formationCategory)
    {
        $formationCategory->formations()->update(['formation_category_id' => null]);
        $formationCategory->delete();

        return redirect()
            ->route('superadmin.formation-categories.index')
            ->with('success', __('Categorie supprimee.'));
    }
}
