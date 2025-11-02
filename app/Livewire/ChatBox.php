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

        // Sanitize trainer options to ensure valid UTF-8 for JSON encoding (@js directive)
        foreach ($trainerOptions as $slug => $opt) {
            $trainerOptions[$slug] = [
                'name' => $this->sanitizeUtf8String((string) ($opt['name'] ?? $slug)),
                'description' => $this->sanitizeUtf8String((string) ($opt['description'] ?? '')),
            ];
        }

        $currentTrainerMeta = $trainerOptions[$this->trainer] ?? [
            'name' => $this->sanitizeUtf8String((string) ($trainerConfig['name'] ?? 'Assistant')),
            'description' => $this->sanitizeUtf8String((string) ($trainerConfig['description'] ?? '')),
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

                // Sanitize and ensure valid UTF-8 for JSON encoding (Livewire/Blade @js)
                $html = $this->sanitizeUtf8String((string) $html);

                // Remove newlines to keep templates compact
                $html = preg_replace("/\r?\n/", '', $html) ?? $html;

                $templates[strtoupper((string) $name)] = $html;
            } catch (Throwable $exception) {
                report($exception);
                // Fallback: skip this shortcode if it causes issues
                continue;
            }
        }

        return $templates;
    }

    /**
     * Ensure a string is valid UTF-8 and strip invalid/control characters.
     */
    private function sanitizeUtf8String(string $input): string
    {
        // If already valid UTF-8, keep it
        if (mb_check_encoding($input, 'UTF-8')) {
            $s = $input;
        } else {
            // Try to detect common single-byte encodings and convert to UTF-8
            $encoding = mb_detect_encoding($input, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'CP1252'], true) ?: 'ISO-8859-1';
            $s = @mb_convert_encoding($input, 'UTF-8', $encoding);
            if ($s === false) {
                // Last resort: use iconv to drop invalid bytes
                $s = @iconv('UTF-8', 'UTF-8//IGNORE', $input) ?: '';
            }
        }

        // Remove C0 control chars except CR/LF/TAB
        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s) ?? $s;

        // Finally remove any remaining invalid sequences via iconv
        $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s) ?: '';

        return $s;
    }
}
