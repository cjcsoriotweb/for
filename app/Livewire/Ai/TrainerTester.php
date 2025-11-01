<?php

namespace App\Livewire\Ai;

use App\Models\AiTrainer;
use App\Services\Ai\ChatCompletionClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RuntimeException;
use Throwable;

class TrainerTester extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $trainers = [];

    public ?int $trainerId = null;

    public string $message = '';

    public ?string $response = null;

    public ?array $usage = null;

    public ?string $error = null;

    public function mount(): void
    {
        $this->ensureAuthorized();

        $this->trainers = AiTrainer::query()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'model', 'provider'])
            ->map(fn (AiTrainer $trainer) => [
                'id' => $trainer->id,
                'name' => $trainer->name,
                'description' => $trainer->description,
                'model' => $trainer->model,
                'provider' => $trainer->provider,
            ])
            ->all();

        $this->trainerId = $this->trainers[0]['id'] ?? null;
    }

    public function testTrainer(): void
    {
        $this->ensureAuthorized();

        $this->validate([
            'trainerId' => ['required', 'integer'],
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'trainerId' => __('Formateur IA'),
            'message' => __('Message'),
        ]);

        $this->error = null;
        $this->response = null;
        $this->usage = null;

        try {
            $trainer = AiTrainer::query()->active()->findOrFail($this->trainerId);
            $providerConfig = config('ai.providers.'.$trainer->provider);

            if (! $providerConfig) {
                throw new RuntimeException(sprintf('Provider [%s] is not configured.', $trainer->provider));
            }

            $client = ChatCompletionClient::fromConfig($providerConfig);

            $messages = [];

            if ($trainer->prompt) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $trainer->prompt,
                ];
            }

            $messages[] = [
                'role' => 'user',
                'content' => $this->message,
            ];

            $payload = [
                'model' => $trainer->model ?: Arr::get($providerConfig, 'default_model', 'llama3'),
                'messages' => $messages,
            ];

            $temperature = Arr::get($trainer->settings, 'temperature', Arr::get($providerConfig, 'temperature'));
            if ($temperature !== null) {
                $payload['temperature'] = (float) $temperature;
            }

            $result = $client->chat($payload);

            $choice = Arr::get($result, 'choices.0.message.content');

            if (! is_string($choice)) {
                throw new RuntimeException('Aucune reponse du modele.');
            }

            $this->response = $choice;
            $this->usage = Arr::get($result, 'usage');
        } catch (Throwable $exception) {
            $this->error = $exception->getMessage();
        }
    }

    public function render()
    {
        $this->ensureAuthorized();

        return view('livewire.ai.trainer-tester');
    }

    protected function ensureAuthorized(): void
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'superadmin') || ! $user->superadmin()) {
            abort(403);
        }
    }
}
