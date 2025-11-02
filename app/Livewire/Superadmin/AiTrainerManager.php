<?php

namespace App\Livewire\Superadmin;

use App\Models\AiTrainer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AiTrainerManager extends Component
{
    use AuthorizesRequests;

    public array $form = [
        'slug' => '',
        'name' => '',
        'description' => '',
        'model' => '',
        'temperature' => 0.7,
        'use_tools' => false,
        'guard' => '',
        'prompt_purpose' => '',
        'prompt_allowed' => '',
        'prompt_not_allowed' => '',
        'prompt_rules' => '',
        'prompt_custom' => '',
        'is_active' => true,
        'sort_order' => 0,
        'show_everywhere' => true,
    ];

    public ?int $trainerId = null;
    public bool $showForm = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->superadmin(), 403);
    }

    public function render()
    {
        return view('livewire.superadmin.ai-trainer-manager', [
            'trainers' => AiTrainer::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $trainerId): void
    {
        $trainer = AiTrainer::query()->findOrFail($trainerId);

        $this->trainerId = $trainer->id;
        $this->form = [
            'slug' => $trainer->slug,
            'name' => $trainer->name,
            'description' => $trainer->description,
            'model' => $trainer->model,
            'temperature' => $trainer->temperature,
            'use_tools' => $trainer->use_tools,
            'guard' => $trainer->guard,
            'prompt_purpose' => $trainer->prompt_purpose,
            'prompt_allowed' => $trainer->prompt_allowed,
            'prompt_not_allowed' => $trainer->prompt_not_allowed,
            'prompt_rules' => $trainer->prompt_rules,
            'prompt_custom' => $trainer->prompt_custom,
            'is_active' => $trainer->is_active,
            'sort_order' => $trainer->sort_order,
            'show_everywhere' => $trainer->show_everywhere,
        ];

        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate($this->rules())['form'];

        $data['slug'] = strtolower(trim($data['slug']));
        $data['name'] = trim($data['name']);
        $data['description'] = $data['description'] !== null ? trim($data['description']) : null;
        $data['model'] = $data['model'] !== null ? trim($data['model']) : null;
        $data['guard'] = $data['guard'] !== null ? trim($data['guard']) : null;
        $data['prompt_purpose'] = $data['prompt_purpose'] !== null ? trim($data['prompt_purpose']) : null;
        $data['prompt_allowed'] = $data['prompt_allowed'] !== null ? trim($data['prompt_allowed']) : null;
        $data['prompt_not_allowed'] = $data['prompt_not_allowed'] !== null ? trim($data['prompt_not_allowed']) : null;
        $data['prompt_rules'] = $data['prompt_rules'] !== null ? trim($data['prompt_rules']) : null;
        $data['prompt_custom'] = $data['prompt_custom'] !== null ? trim($data['prompt_custom']) : null;
        $data['temperature'] = isset($data['temperature']) ? (float) $data['temperature'] : 0.7;
        $data['use_tools'] = (bool) ($data['use_tools'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['show_everywhere'] = (bool) ($data['show_everywhere'] ?? false);

        if ($this->trainerId) {
            $trainer = AiTrainer::query()->findOrFail($this->trainerId);
            $trainer->fill($data);
            $trainer->save();
        } else {
            AiTrainer::query()->create($data);
        }

        $this->resetForm();
        $this->showForm = false;
        session()->flash('status', __('Assistant IA sauvegarde.'));
    }

    public function delete(int $trainerId): void
    {
        $trainer = AiTrainer::query()->findOrFail($trainerId);
        $trainer->delete();
    }


    public function toggleShowEverywhere(int $trainerId): void
    {
        $trainer = AiTrainer::query()->findOrFail($trainerId);
        $trainer->show_everywhere = ! $trainer->show_everywhere;
        $trainer->save();
    }
    public function toggleActive(int $trainerId): void
    {
        $trainer = AiTrainer::query()->findOrFail($trainerId);
        $trainer->is_active = ! $trainer->is_active;
        $trainer->save();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    protected function rules(): array
    {
        return [
            'form.slug' => [
                'required',
                'string',
                'regex:/^[a-z0-9_-]+$/',
                Rule::unique('ai_trainers', 'slug')->ignore($this->trainerId),
            ],
            'form.name' => ['required', 'string', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.model' => ['nullable', 'string', 'max:255'],
            'form.temperature' => ['nullable', 'numeric', 'between:0,2'],
            'form.use_tools' => ['boolean'],
            'form.guard' => ['nullable', 'string', 'max:255'],
            'form.prompt_purpose' => ['nullable', 'string'],
            'form.prompt_allowed' => ['nullable', 'string'],
            'form.prompt_not_allowed' => ['nullable', 'string'],
            'form.prompt_rules' => ['nullable', 'string'],
            'form.prompt_custom' => ['nullable', 'string'],
            'form.is_active' => ['boolean'],
            'form.sort_order' => ['integer', 'min:0'],
            'form.show_everywhere' => ['boolean'],
        ];
    }

    protected function resetForm(): void
    {
        $this->trainerId = null;
        $this->form = [
            'slug' => '',
            'name' => '',
            'description' => '',
            'model' => '',
            'temperature' => 0.7,
            'use_tools' => false,
            'guard' => '',
            'prompt_purpose' => '',
            'prompt_allowed' => '',
            'prompt_not_allowed' => '',
            'prompt_rules' => '',
            'prompt_custom' => '',
            'is_active' => true,
            'sort_order' => 0,
            'show_everywhere' => true,
        ];
    }
}
