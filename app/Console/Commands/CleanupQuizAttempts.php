<?php

namespace App\Console\Commands;

use App\Services\Quiz\QuizService;
use Illuminate\Console\Command;

class CleanupQuizAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:cleanup-attempts
                            {--days=365 : Nombre de jours à conserver (par défaut 365 jours)}
                            {--dry-run : Afficher ce qui serait supprimé sans supprimer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les anciennes tentatives de quiz pour libérer de l\'espace';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysToKeep = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $this->info("Nettoyage des tentatives de quiz de plus de {$daysToKeep} jours...");

        if ($dryRun) {
            $this->info('MODE SIMULATION - Aucun élément ne sera supprimé');
        }

        try {
            $quizService = app(QuizService::class);
            $deletedCount = $quizService->cleanupOldAttempts($daysToKeep);

            if ($dryRun) {
                $this->info("{$deletedCount} tentatives seraient supprimées");
            } else {
                $this->info("{$deletedCount} tentatives supprimées avec succès");
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors du nettoyage : ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
