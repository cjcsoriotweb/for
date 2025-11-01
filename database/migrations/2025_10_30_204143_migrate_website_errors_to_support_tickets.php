<?php

use App\Models\PageNote;
use App\Models\WebsiteError;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrer toutes les erreurs existantes vers des notes individuelles
        WebsiteError::chunk(100, function ($errors) {
            foreach ($errors as $error) {
                $this->createErrorNote($error);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer toutes les notes d'erreur système
        PageNote::where('content', 'like', '%🚨 **Erreur système migrée**%')->delete();
    }

    /**
     * Create an error note from a WebsiteError
     */
    private function createErrorNote(WebsiteError $error): void
    {
        $content = "🚨 **Erreur système migrée**\n\n";
        $content .= "**Code d'erreur:** {$error->error_code}\n";
        $content .= "**Message:** {$error->message}\n";
        $content .= "**URL:** {$error->url}\n";
        $content .= '**Utilisateur:** '.($error->user_id ? "ID: {$error->user_id}" : 'Non connecté')."\n";
        $content .= "**IP:** {$error->ip_address}\n";

        if ($error->user_agent) {
            $content .= "**Navigateur:** {$error->user_agent}\n";
        }

        if ($error->request_data) {
            $requestData = is_string($error->request_data) ? json_decode($error->request_data, true) : $error->request_data;
            if (is_array($requestData)) {
                if (isset($requestData['method'])) {
                    $content .= "**Méthode:** {$requestData['method']}\n";
                }
                if (isset($requestData['route_name'])) {
                    $content .= "**Route:** {$requestData['route_name']}\n";
                }
            }
        }

        if ($error->stack_trace) {
            $content .= "\n**Stack trace:**\n```\n".$error->stack_trace."\n```";
        }

        $codeType = match ($error->error_code) {
            404 => 'Page non trouvée',
            403 => 'Accès interdit',
            500 => 'Erreur serveur',
            default => "Erreur {$error->error_code}"
        };

        // Appliquer la même logique que dans le service
        $shortMessage = $error->message;
        if (preg_match('/^View \[([^\]]+)\] not found/', $error->message, $matches)) {
            $shortMessage = 'View ['.substr($matches[1], 0, 50).'...]';
        } elseif (strlen($error->message) > 60) {
            $shortMessage = substr($error->message, 0, 57).'...';
        }

        $title = "🚨 {$codeType}: {$shortMessage}";

        PageNote::create([
            'user_id' => $error->user_id ?? 1, // Utilisateur système par défaut
            'path' => $error->url,
            'title' => $title,
            'content' => $content,
            'is_resolved' => $error->resolved_at ? true : false,
            'created_at' => $error->created_at,
            'updated_at' => $error->updated_at,
        ]);
    }
};
