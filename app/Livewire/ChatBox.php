<?php

namespace App\Livewire;

use Livewire\Component;
use Throwable;

/**
 * Composant Chat unique pour toutes les interactions IA.
 * Utilise le streaming NDJSON côté JavaScript.
 */
class ChatBox extends Component
{
    public string $trainer = 'default';
    public ?int $conversationId = null;
    public bool $isOpen = false;
    public string $title = 'Assistant IA';
    public array $shortcodeTemplates = [];

    public function mount(?string $trainer = null, ?int $conversationId = null, string $title = 'Assistant IA'): void
    {
        $this->trainer = $trainer ?? config('ai.default_trainer_slug', 'default');
        $this->conversationId = $conversationId;
        $this->title = $title;
        $this->shortcodeTemplates = $this->resolveShortcodeTemplates();

        // Vérifier que le trainer existe
        $trainerConfig = config("ai.trainers.{$this->trainer}");
        if (!$trainerConfig) {
            $this->trainer = config('ai.default_trainer_slug', 'default');
        }
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        $trainerConfig = config("ai.trainers.{$this->trainer}");
        $trainersConfig = config('ai.trainers', []);

        $trainerOptions = [];
        foreach ($trainersConfig as $slug => $config) {
            $trainerOptions[$slug] = [
                'name' => $config['name'] ?? ucfirst($slug),
                'description' => $config['description'] ?? '',
            ];
        }

        $currentTrainerMeta = $trainerOptions[$this->trainer] ?? [
            'name' => $trainerConfig['name'] ?? 'Assistant',
            'description' => $trainerConfig['description'] ?? '',
        ];

        return view('livewire.chat-box', [
            'trainerOptions' => $trainerOptions,
            'currentTrainerMeta' => $currentTrainerMeta,
            'shortcodeTemplates' => $this->shortcodeTemplates,
        ]);
    }

    protected function resolveShortcodeTemplates(): array
    {
        $shortcodes = config('ai.shortcodes', []);
        $templates = [];

        foreach ($shortcodes as $name => $options) {
            $view = $options['view'] ?? null;
            $data = $options['data'] ?? [];

            if (! is_string($view) || $view === '') {
                continue;
            }

            try {
                $html = trim(view($view, $data)->render());
                $sanitized = preg_replace("/\r?\n/", '', $html);
                $templates[strtoupper((string) $name)] = is_string($sanitized) ? $sanitized : $html;
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return $templates;
    }
}
