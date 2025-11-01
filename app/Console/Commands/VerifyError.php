<?php

namespace App\Console\Commands;

use App\Services\WebsiteErrorService;
use Illuminate\Console\Command;

class VerifyError extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifyerror
                            {--stats : Afficher les statistiques des erreurs}
                            {--list : Lister les 3 dernières erreurs (défaut)}
                            {--unresolved : Lister uniquement les erreurs non résolues}
                            {--resolve= : Marquer une erreur comme résolue (ID requis)}
                            {--delete= : Supprimer définitivement une erreur (ID requis)}
                            {--code= : Filtrer par code d\'erreur (ex: 404, 500)}
                            {--days=7 : Nombre de jours à analyser pour les stats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gérer et vérifier les erreurs du website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $errorService = app(WebsiteErrorService::class);

        if ($this->option('stats')) {
            $this->showStatistics($errorService);
        } elseif ($this->option('resolve')) {
            $this->resolveError($errorService, (int) $this->option('resolve'));
        } elseif ($this->option('delete')) {
            $this->deleteError($errorService, (int) $this->option('delete'));
        } else {
            $this->listErrors($errorService);
        }

        return 0;
    }

    /**
     * Show error statistics
     */
    private function showStatistics(WebsiteErrorService $errorService): void
    {
        $days = (int) $this->option('days');
        $stats = $errorService->getErrorStatistics($days);

        $this->info("📊 Statistiques des erreurs (derniers {$days} jours)");
        $this->line('='.str_repeat('=', 60));

        $this->line("Total d'erreurs: <comment>{$stats['total_errors']}</comment>");
        $this->line("Erreurs non résolues: <error>{$stats['unresolved_errors']}</error>");
        $this->newLine();

        if (! empty($stats['errors_by_code'])) {
            $this->line('Répartition par code d\'erreur:');
            $this->table(
                ['Code', 'Total', 'Non résolues'],
                $stats['errors_by_code']
            );
        }

        if (! empty($stats['recent_errors'])) {
            $this->newLine();
            $this->line('Erreurs récentes:');
            $this->table(
                ['ID', 'Code', 'Message', 'URL', 'Utilisateur', 'Date', 'Résolu'],
                collect($stats['recent_errors'])->map(function ($error) {
                    return [
                        $error['id'],
                        $error['code'],
                        $error['message'],
                        $this->shortenUrl($error['url']),
                        $error['user'] ?: 'N/A',
                        $error['created_at'],
                        $error['resolved'] ? '✅' : '❌',
                    ];
                })->toArray()
            );
        }
    }

    /**
     * List errors
     */
    private function listErrors(WebsiteErrorService $errorService): void
    {
        $limit = 3; // Limite fixe: 3 dernières erreurs
        $onlyUnresolved = $this->option('unresolved');

        $errors = $errorService->getUnresolvedErrors($limit);

        if ($onlyUnresolved) {
            $this->info('🔍 3 dernières erreurs non résolues');
        } else {
            $this->info('🔍 3 dernières erreurs');
        }

        if ($errors->isEmpty()) {
            $this->warn('Aucune erreur trouvée.');

            return;
        }

        $this->line('='.str_repeat('=', 80));

        // Parser les messages d'erreur pour afficher les données structurées
        $tableData = $errors->map(function ($note) {
            $content = $note->content ?: '';
            $data = $this->parseErrorMessage($content);

            return [
                $note->id,
                $data['code'] ?? 0,
                $data['message'] ?? substr($content, 0, 50).'...',
                $this->shortenUrl($note->path ?: '/'),
                $note->user ? $note->user->name : 'N/A',
                'N/A', // Plus d'IP dans les notes
                $note->created_at->format('Y-m-d H:i:s'),
                $note->is_resolved ? '✅' : '❌',
            ];
        });

        $this->table(
            ['ID', 'Code', 'Message', 'URL', 'Utilisateur', 'IP', 'Date', 'Résolu'],
            $tableData->toArray()
        );

        if ($errors->count() > 0) {
            $this->newLine();
            $this->warn("💡 {$errors->count()} erreur(s) non résolue(s). Utilisez --resolve=ID ou --delete=ID.");
        }
    }

    /**
     * Resolve an error
     */
    private function resolveError(WebsiteErrorService $errorService, int $errorId): void
    {
        if ($errorService->markErrorAsResolved($errorId)) {
            $this->info("✅ Erreur #{$errorId} marquée comme résolue.");
        } else {
            $this->error("❌ Impossible de marquer l'erreur #{$errorId} comme résolue (elle n'existe pas ou est déjà résolue).");
        }
    }

    /**
     * Delete an error
     */
    private function deleteError(WebsiteErrorService $errorService, int $errorId): void
    {
        if ($errorService->deleteError($errorId)) {
            $this->info("🗑️ Erreur #{$errorId} supprimée définitivement.");
        } else {
            $this->error("❌ Impossible de supprimer l'erreur #{$errorId} (elle n'existe pas).");
        }
    }

    /**
     * Parse error message to extract structured data
     */
    private function parseErrorMessage(string $message): array
    {
        $data = [];

        // Extract error code
        if (preg_match('/\*\*Code d\'erreur:\*\* (\d+)/', $message, $matches)) {
            $data['code'] = (int) $matches[1];
        }

        // Extract error message
        if (preg_match('/\*\*Message:\*\* ([^\n]+)/', $message, $matches)) {
            $data['message'] = $matches[1];
        }

        // Extract URL
        if (preg_match('/\*\*URL:\*\* ([^\n]+)/', $message, $matches)) {
            $data['url'] = $matches[1];
        }

        return $data;
    }

    /**
     * Shorten URL for display
     */
    private function shortenUrl(string $url): string
    {
        if (strlen($url) <= 50) {
            return $url;
        }

        return substr($url, 0, 47).'...';
    }
}
