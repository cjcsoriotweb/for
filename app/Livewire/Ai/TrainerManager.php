<?php

namespace App\Livewire\Ai;

use App\Models\AiTrainer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TrainerManager extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $trainers = [];

    public string $name = '';
    public string $model = 'gpt-4o-mini';
    public string $provider = 'openai';
    public ?string $description = null;
    public ?string $prompt = null;
    public ?string $avatarPath = null;
    public float $temperature = 0.7;
    public bool $isDefault = false;
    public bool $isActive = true;
    /** @var array<int, string> */
    public array $providerOptions = [];
    public ?int $editingTrainerId = null;

    public function mount(): void
    {
        $this->ensureAuthorized();
        $this->providerOptions = $this->resolveProviderOptions();
        $this->provider = $this->providerOptions[0] ?? 'openai';
        $this->loadTrainers();
    }

    public function updated($property): void
    {
        $this->ensureAuthorized();
        $this->validateOnly($property, $this->rules());
    }

    public function submitTrainer(): void
    {
        $this->ensureAuthorized();

        $validated = $this->validate($this->rules(), [], [
            'name' => __('Nom'),
            'model' => __('Modele'),
            'provider' => __('Fournisseur'),
            'temperature' => __('Temperature'),
        ]);

        if ($this->editingTrainerId) {
            $this->updateTrainer($validated);
        } else {
            $this->createTrainer($validated);
        }
    }

    public function setDefault(int $trainerId): void
    {
        $this->ensureAuthorized();

        $trainer = AiTrainer::query()->findOrFail($trainerId);
        $trainer->forceFill(['is_default' => true])->save();

        $this->loadTrainers();
        session()->flash('ai_trainer_updated', __('Le formateur par defaut a ete mis a jour.'));
    }

    public function toggleActive(int $trainerId): void
    {
        $this->ensureAuthorized();

        $trainer = AiTrainer::query()->findOrFail($trainerId);
        $trainer->forceFill(['is_active' => ! $trainer->is_active])->save();

        $this->loadTrainers();
        session()->flash('ai_trainer_updated', __('Statut du formateur mis a jour.'));
    }

    public function render()
    {
        $this->ensureAuthorized();

        return view('livewire.ai.trainer-manager');
    }

    private function loadTrainers(): void
    {
        $this->trainers = AiTrainer::query()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get(['id', 'name', 'provider', 'model', 'description', 'is_default', 'is_active', 'updated_at'])
            ->map(fn (AiTrainer $trainer) => [
                'id' => $trainer->id,
                'name' => $trainer->name,
                'provider' => $trainer->provider,
                'model' => $trainer->model,
                'description' => $trainer->description,
                'is_default' => (bool) $trainer->is_default,
                'is_active' => (bool) $trainer->is_active,
                'updated_at_human' => optional($trainer->updated_at)->diffForHumans(),
            ])
            ->all();
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->model = 'gpt-4o-mini';
        $this->provider = $this->providerOptions[0] ?? 'openai';
        $this->description = null;
        $this->prompt = null;
        $this->avatarPath = null;
        $this->temperature = 0.7;
        $this->isDefault = false;
        $this->isActive = true;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->editingTrainerId = null;
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'provider' => ['required', 'string', Rule::in($this->providerOptions)],
            'model' => ['required', 'string', 'max:120'],
            'temperature' => ['nullable', 'numeric', 'min:0', 'max:2'],
            'description' => ['nullable', 'string'],
            'prompt' => ['nullable', 'string'],
            'avatarPath' => ['nullable', 'string', 'max:255'],
            'isDefault' => ['boolean'],
            'isActive' => ['boolean'],
        ];
    }

    private function createTrainer(array $validated): void
    {
        $trainer = AiTrainer::create([
            'name' => $validated['name'],
            'provider' => $validated['provider'],
            'model' => $validated['model'],
            'description' => $this->description,
            'prompt' => $this->prompt,
            'avatar_path' => $this->avatarPath,
            'is_default' => $this->isDefault,
            'is_active' => $this->isActive,
            'settings' => [
                'temperature' => Arr::get($validated, 'temperature'),
            ],
        ]);

        $this->resetForm();
        $this->loadTrainers();

        $this->dispatch('ai-trainer-created', id: $trainer->id);
        session()->flash('ai_trainer_created', __('Formateur IA cree avec succes.'));
    }

    private function updateTrainer(array $validated): void
    {
        $trainer = AiTrainer::query()->findOrFail($this->editingTrainerId);

        $trainer->forceFill([
            'name' => $validated['name'],
            'provider' => $validated['provider'],
            'model' => $validated['model'],
            'description' => $this->description,
            'prompt' => $this->prompt,
            'avatar_path' => $this->avatarPath,
            'is_default' => $this->isDefault,
            'is_active' => $this->isActive,
            'settings' => [
                'temperature' => Arr::get($validated, 'temperature'),
            ],
        ])->save();

        $this->resetForm();
        $this->loadTrainers();

        session()->flash('ai_trainer_updated', __('Formateur IA mis a jour.'));
    }

    private function ensureAuthorized(): void
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'superadmin') || ! $user->superadmin()) {
            abort(403);
        }
    }

    /**
     * Resolve provider options from configuration.
     *
     * @return array<int, string>
     */
    private function resolveProviderOptions(): array
    {
        return array_keys(config('ai.providers', []));
    }

    public function editTrainer(int $trainerId): void
    {
        $this->ensureAuthorized();

        $trainer = AiTrainer::query()->findOrFail($trainerId);

        $this->editingTrainerId = $trainer->id;
        $this->name = $trainer->name;
        $this->model = $trainer->model;
        $this->provider = $trainer->provider;
        $this->description = $trainer->description;
        $this->prompt = $trainer->prompt;
        $this->avatarPath = $trainer->avatar_path;
        $this->isDefault = (bool) $trainer->is_default;
        $this->isActive = (bool) $trainer->is_active;
        $this->temperature = (float) ($trainer->settings['temperature'] ?? 0.7);
    }

    public function cancelEdit(): void
    {
        $this->ensureAuthorized();
        $this->resetForm();
    }
}
