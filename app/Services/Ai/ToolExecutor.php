<?php

namespace App\Services\Ai;

use App\Models\User;

/**
 * Exécuteur d'outils pour l'IA.
 * Gère l'exécution des outils et la génération des réponses.
 */
class ToolExecutor
{
    /**
     * Parse le contenu pour détecter les appels d'outils.
     * Format attendu: [TOOL:nom_outil]{"param":"value"}[/TOOL]
     *
     * @param  string  $content
     * @param  User  $user
     * @return array{content: string, tool_results: array}
     */
    public function parseAndExecuteTools(string $content, User $user): array
    {
        $toolResults = [];
        $processedContent = $content;

        // Pattern pour détecter les appels d'outils
        $pattern = '/\[TOOL:(\w+)\](.*?)\[\/TOOL\]/s';

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $fullMatch = $match[0];
                $toolName = $match[1];
                $parametersJson = trim($match[2]);

                // Parser les paramètres JSON
                $parameters = [];
                if (!empty($parametersJson)) {
                    $decoded = json_decode($parametersJson, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $parameters = $decoded;
                    } else {
                        // Log l'erreur de parsing JSON (sans exposer les données sensibles)
                        \Illuminate\Support\Facades\Log::warning('Tool parameter JSON parsing failed', [
                            'tool' => $toolName,
                            'error' => json_last_error_msg(),
                            'json_length' => strlen($parametersJson),
                        ]);
                    }
                }

                // Exécuter l'outil
                $result = TicketTools::executeTool($toolName, $parameters, $user);
                $toolResults[] = [
                    'tool' => $toolName,
                    'parameters' => $parameters,
                    'result' => $result,
                ];

                // Remplacer l'appel d'outil par le résultat formaté
                $resultText = $this->formatToolResult($toolName, $result);
                $processedContent = str_replace($fullMatch, $resultText, $processedContent);
            }
        }

        return [
            'content' => $processedContent,
            'tool_results' => $toolResults,
        ];
    }

    /**
     * Formate le résultat d'un outil pour l'affichage.
     *
     * @param  string  $toolName
     * @param  array  $result
     * @return string
     */
    private function formatToolResult(string $toolName, array $result): string
    {
        if (!($result['success'] ?? false)) {
            return "❌ Erreur: " . ($result['error'] ?? 'Erreur inconnue');
        }

        return match ($toolName) {
            'list_user_tickets' => $this->formatTicketList($result),
            'get_ticket_details' => $this->formatTicketDetails($result),
            'add_ticket_message' => $this->formatTicketMessageAdded($result),
            default => "✅ " . ($result['message'] ?? 'Opération réussie'),
        };
    }

    /**
     * Formate la liste des tickets.
     *
     * @param  array  $result
     * @return string
     */
    private function formatTicketList(array $result): string
    {
        $tickets = $result['tickets'] ?? [];

        if (empty($tickets)) {
            return "Aucun ticket trouvé.";
        }

        $formatted = "Voici vos tickets :\n";
        foreach ($tickets as $ticket) {
            $status = match ($ticket['status']) {
                'open' => '🟢 Ouvert',
                'pending' => '🟡 En attente',
                'resolved' => '🔵 Résolu',
                'closed' => '⚫ Fermé',
                default => '❓ ' . $ticket['status'],
            };

            $formatted .= "- #{$ticket['id']}: {$ticket['title']} ({$status})\n";
        }

        return $formatted;
    }

    /**
     * Formate les détails d'un ticket.
     *
     * @param  array  $result
     * @return string
     */
    private function formatTicketDetails(array $result): string
    {
        $ticket = $result['ticket'] ?? null;

        if (!$ticket) {
            return "Ticket non trouvé.";
        }

        $status = match ($ticket['status']) {
            'open' => '🟢 Ouvert',
            'pending' => '🟡 En attente',
            'resolved' => '🔵 Résolu',
            'closed' => '⚫ Fermé',
            default => '❓ ' . $ticket['status'],
        };

        $formatted = "Ticket #{$ticket['id']}: {$ticket['title']}\n";
        $formatted .= "Statut: {$status}\n";
        $formatted .= "Créé le: {$ticket['created_at']}\n\n";

        if (!empty($ticket['messages'])) {
            $formatted .= "Messages:\n";
            foreach ($ticket['messages'] as $message) {
                $author = $message['is_support'] ? 'Support' : 'Vous';
                $formatted .= "[{$message['created_at']}] {$author}: {$message['message']}\n";
            }
        }

        return $formatted;
    }

    /**
     * Formate l'ajout d'un message à un ticket.
     *
     * @param  array  $result
     * @return string
     */
    private function formatTicketMessageAdded(array $result): string
    {
        return "✅ Message ajouté au ticket avec succès.";
    }

    /**
     * Génère le prompt système avec les définitions d'outils.
     *
     * @return string
     */
    public static function getToolsPrompt(): string
    {
        $tools = TicketTools::getToolDefinitions();

        $prompt = "Tu as accès aux outils suivants pour gérer les tickets de support:\n\n";

        foreach ($tools as $toolName => $tool) {
            $prompt .= "**{$toolName}**: {$tool['description']}\n";
            if (!empty($tool['parameters']['properties'])) {
                $prompt .= "Paramètres:\n";
                foreach ($tool['parameters']['properties'] as $paramName => $param) {
                    $required = in_array($paramName, $tool['parameters']['required'] ?? []) ? ' (requis)' : '';
                    $prompt .= "- {$paramName}: {$param['description']}{$required}\n";
                }
            }
            $prompt .= "\n";
        }

        $prompt .= "Pour utiliser un outil, utilise le format: [TOOL:nom_outil]{\"param\":\"valeur\"}[/TOOL]\n";
        $prompt .= "Le résultat de l'outil remplacera automatiquement l'appel dans ta réponse.\n\n";

        return $prompt;
    }
}
