<?php

namespace App\Livewire\Superadmin;

use App\Models\FormationCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FormationCategoryManager extends Component
{
    use AuthorizesRequests;

    public ?int $categoryId = null;

    public array $form = [
        'name' => '',
        'description' => '',
    ];

    public bool $showForm = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->superadmin(), 403);
    }

    public function render()
    {
        return view('livewire.superadmin.formation-category-manager', [
            'categories' => FormationCategory::query()
                ->withCount('formations')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $categoryId): void
    {
        $category = FormationCategory::query()->findOrFail($categoryId);

        $this->categoryId = $category->id;
        $this->form = [
            'name' => $category->name,
            'description' => $category->description,
        ];

        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate($this->rules())['form'];

        $attributes = [
            'name' => trim($data['name']),
            'description' => $data['description'] !== null ? trim($data['description']) : null,
        ];

        if ($this->categoryId) {
            $category = FormationCategory::query()->findOrFail($this->categoryId);
            $category->update($attributes);
        } else {
            $attributes['created_by'] = Auth::id();
            FormationCategory::query()->create($attributes);
        }

        $this->resetForm();
        $this->showForm = false;
        session()->flash('status', __('Categorie sauvegardee.'));
    }

    public function delete(int $categoryId): void
    {
        $category = FormationCategory::query()->findOrFail($categoryId);
        $category->formations()->update(['formation_category_id' => null]);
        $category->delete();

        $this->resetForm();
        $this->showForm = false;
        session()->flash('status', __('Categorie supprimee.'));
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    protected function rules(): array
    {
        return [
            'form.name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('formation_categories', 'name')->ignore($this->categoryId),
            ],
            'form.description' => ['nullable', 'string'],
        ];
    }

    protected function resetForm(): void
    {
        $this->categoryId = null;
        $this->form = [
            'name' => '',
            'description' => '',
        ];
    }
}
