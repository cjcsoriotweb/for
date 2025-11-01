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
     * Formate le résultat de création de ticket.
     *
     * @param  array  $result
     * @return string
     */
