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
            'create_support_ticket' => $this->formatTicketCreation($result),
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
    private function formatTicketCreation(array $result): string
    {
        $ticketId = $result['ticket_id'] ?? null;
        $ticketUrl = $ticketId ? url("/mon-compte/support?ticket={$ticketId}") : null;
        
        $response = sprintf(
            "✅ **Ticket créé avec succès !**\n\n📋 Numéro: %s\n📝 Sujet: %s\n📊 Statut: %s",
            $result['ticket_number'] ?? 'N/A',
            $result['subject'] ?? 'N/A',
            $result['status'] ?? 'N/A'
        );
        
        if ($ticketUrl) {
            $response .= "\n\n🔗 [Voir le ticket]({$ticketUrl})";
        }
        
        return $response;
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
        $count = $result['count'] ?? 0;

        if ($count === 0) {
            return "📋 Vous n'avez aucun ticket pour le moment.";
        }

        $lines = ["📋 **Vos tickets** ($count) :\n"];
        
        foreach ($tickets as $ticket) {
            $statusEmoji = match ($ticket['status']) {
                'open' => '🔴',
                'pending' => '🟡',
                'resolved' => '🟢',
                'closed' => '⚫',
                default => '⚪',
            };
            
            $responseInfo = $ticket['has_response'] ? ' ✉️' : '';
            
            $lines[] = sprintf(
                "%s **%s** - %s%s\n   %s\n   *Dernier message: %s*",
                $statusEmoji,
                $ticket['number'],
                $ticket['subject'],
                $responseInfo,
                $ticket['status_label'],
                $ticket['last_message_at'] ?? $ticket['created_at']
            );
        }

        return implode("\n\n", $lines);
    }

    /**
     * Formate les détails d'un ticket.
     *
     * @param  array  $result
     * @return string
     */
    private function formatTicketDetails(array $result): string
    {
        $ticket = $result['ticket'] ?? [];
        
        if (empty($ticket)) {
            return "❌ Ticket non trouvé.";
        }

        $messages = $ticket['messages'] ?? [];
        $messageCount = count($messages);
        
        $lines = [
            sprintf("📋 **Ticket %s**", $ticket['number'] ?? 'N/A'),
            sprintf("📝 %s", $ticket['subject'] ?? 'N/A'),
            sprintf("📊 Statut: %s", $ticket['status_label'] ?? 'N/A'),
            sprintf("📅 Créé: %s", $ticket['created_at'] ?? 'N/A'),
            "",
            sprintf("💬 **Messages** (%d) :", $messageCount),
        ];

        foreach ($messages as $msg) {
            $author = $msg['is_support'] ? '🎧 Support' : '👤 Vous';
            $lines[] = sprintf(
                "\n%s - *%s*\n%s",
                $author,
                $msg['created_at'],
                $msg['content']
            );
        }

        return implode("\n", $lines);
    }

    /**
     * Formate la confirmation d'ajout de message.
     *
     * @param  array  $result
     * @return string
     */
    private function formatTicketMessageAdded(array $result): string
    {
        return "✅ Votre message a été ajouté au ticket.";
    }

    /**
     * Génère un prompt système avec les définitions d'outils.
     *
     * @return string
     */
    public static function getToolsPrompt(): string
    {
        $tools = TicketTools::getToolDefinitions();
        $toolDescriptions = [];

        foreach ($tools as $tool) {
            $params = [];
            foreach ($tool['parameters']['properties'] ?? [] as $paramName => $paramDef) {
                $required = in_array($paramName, $tool['parameters']['required'] ?? []) ? ' (requis)' : ' (optionnel)';
                $params[] = "  - {$paramName}{$required}: {$paramDef['description']}";
            }
            
            $toolDescriptions[] = sprintf(
                "**%s**\n%s\nParamètres:\n%s",
                $tool['name'],
                $tool['description'],
                implode("\n", $params)
            );
        }

        $toolsText = implode("\n\n", $toolDescriptions);

        return <<<PROMPT
**Utilisation des outils :**

Pour utiliser un outil, utilise ce format dans ta réponse :
[TOOL:nom_outil]{"param1":"value1","param2":"value2"}[/TOOL]

Le système exécutera automatiquement l'outil et remplacera ce bloc par le résultat.

**Outils disponibles :**

{$toolsText}

**Exemples d'utilisation :**

1. Créer un ticket :
[TOOL:create_support_ticket]{"subject":"Demande de rappel","message":"L'utilisateur souhaite être rappelé au 06 12 34 56 78","phone_number":"06 12 34 56 78"}[/TOOL]

2. Lister les tickets :
[TOOL:list_user_tickets]{"status":"open","limit":5}[/TOOL]

3. Voir détails d'un ticket :
[TOOL:get_ticket_details]{"ticket_id":123}[/TOOL]

N'hésite pas à utiliser ces outils quand c'est pertinent !
PROMPT;
    }
}
