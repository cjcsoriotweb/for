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
                            {--list : Lister les erreurs récentes (défaut)}
                            {--unresolved : Lister uniquement les erreurs non résolues}
                            {--resolve= : Marquer une erreur comme résolue (ID requis)}
                            {--code= : Filtrer par code d\'erreur (ex: 404, 500)}
                            {--limit=10 : Nombre maximum d\'erreurs à afficher}
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
        $limit = (int) $this->option('limit');
        $onlyUnresolved = $this->option('unresolved');
        $codeFilter = $this->option('code');

        if ($onlyUnresolved) {
            $errors = $errorService->getUnresolvedErrors($limit);
            $this->info("🔍 Erreurs non résolues (limite: {$limit})");
        } else {
            $query = \App\Models\WebsiteError::with('user')->orderBy('created_at', 'desc');

            if ($codeFilter) {
                $query->where('error_code', (int) $codeFilter);
                $this->info("🔍 Erreurs {$codeFilter} (limite: {$limit})");
            } else {
                $this->info("🔍 Toutes les erreurs récentes (limite: {$limit})");
            }

            $errors = $query->limit($limit)->get();
        }

        if ($errors->isEmpty()) {
            $this->warn('Aucune erreur trouvée.');

            return;
        }

        $this->line('='.str_repeat('=', 80));
        $tableData = $errors->map(function ($error) {
            return [
                $error->id,
                $error->error_code,
                $error->message,
                $this->shortenUrl($error->url),
                $error->user ? $error->user->name : 'N/A',
                $error->ip_address,
                $error->created_at->format('Y-m-d H:i:s'),
                $error->isResolved() ? '✅' : '❌',
            ];
        });

        $this->table(
            ['ID', 'Code', 'Message', 'URL', 'Utilisateur', 'IP', 'Date', 'Résolu'],
            $tableData
        );

        $totalUnresolved = $errors->where('resolved_at', null)->count();
        if ($totalUnresolved > 0) {
            $this->newLine();
            $this->warn("💡 {$totalUnresolved} erreur(s) non résolue(s). Utilisez --resolve=ID pour les marquer comme résolues.");
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
