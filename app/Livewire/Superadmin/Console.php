<?php

namespace App\Livewire\Superadmin;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Throwable;

class Console extends Component
{
    public string $command = '';

    public array $logs = [];

    public array $availableCommands = [
        'cache:clear' => 'Vider le cache de l’application',
        'config:cache' => 'Recompiler la configuration',
        'route:cache' => 'Recompiler les routes',
        'view:clear' => 'Vider les vues compilées',
        'queue:restart' => 'Redémarrer les workers de file d’attente',
        'optimize' => 'Optimiser l’application (cache)',
        'migrate:status' => 'Afficher l’état des migrations',
    ];

    public function runCommand(): void
    {
        $command = trim($this->command);

        if ($command === '') {
            $this->appendLog('—', 'Veuillez saisir une commande.', false);
            return;
        }

        if (! array_key_exists($command, $this->availableCommands)) {
            $this->appendLog($command, 'Cette commande n’est pas autorisée depuis la console.', false);
            return;
        }

        try {
            Artisan::call($command);
            $output = trim(Artisan::output());

            $this->appendLog(
                $command,
                $output === '' ? 'La commande s’est exécutée sans sortie.' : $output,
                true
            );
        } catch (Throwable $exception) {
            $this->appendLog($command, $exception->getMessage(), false);
        }

        $this->command = '';
    }

    public function runNamedCommand(string $command): void
    {
        if (! array_key_exists($command, $this->availableCommands)) {
            $this->appendLog($command, 'Cette commande n’est pas autorisée depuis la console.', false);
            return;
        }

        $this->command = $command;
        $this->runCommand();
    }

    private function appendLog(string $command, string $output, bool $success): void
    {
        array_unshift($this->logs, [
            'command' => $command,
            'output' => $output,
            'success' => $success,
            'timestamp' => now()->format('H:i:s'),
        ]);

        $this->logs = array_slice($this->logs, 0, 12);
    }

    public function render()
    {
        return view('livewire.superadmin.console');
    }
}
