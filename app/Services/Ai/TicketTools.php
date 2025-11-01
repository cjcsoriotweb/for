<?php

namespace App\Services\Ai;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Service pour les outils IA liés aux tickets de support.
 */
class TicketTools
{
    /**
     * Limite minimum de tickets à retourner.
     */
    private const MIN_TICKET_LIMIT = 1;

    /**
     * Limite maximum de tickets à retourner.
     */
    private const MAX_TICKET_LIMIT = 50;

    /**
     * Limite par défaut de tickets à retourner.
     */
    private const DEFAULT_TICKET_LIMIT = 10;

    /**
     * Définition des outils disponibles pour l'IA.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getToolDefinitions(): array
    {
        return [
            'create_support_ticket' => [
                'name' => 'create_support_ticket',
                'description' => "Crée un nouveau ticket de support pour l'utilisateur. Utilise cet outil quand l'utilisateur demande de l'aide qui nécessite une intervention humaine, veut être rappelé par téléphone, ou rencontre un problème que tu ne peux pas résoudre directement.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'subject' => [
                            'type' => 'string',
                            'description' => 'Sujet du ticket (clair et descriptif)',
                        ],
                        'message' => [
                            'type' => 'string',
                            'description' => 'Message initial du ticket avec les détails du problème ou de la demande',
                        ],
                        'phone_number' => [
                            'type' => 'string',
                            'description' => "Numéro de téléphone de l'utilisateur (optionnel, requis pour les rappels)",
                        ],
                    ],
                    'required' => ['subject', 'message'],
                ],
            ],
            'list_user_tickets' => [
                'name' => 'list_user_tickets',
                'description' => "Liste les tickets de support de l'utilisateur avec leur statut. Utilise cet outil quand l'utilisateur veut voir ses tickets en cours ou l'historique de ses demandes.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => [
                            'type' => 'string',
                            'description' => "Filtrer par statut: 'open', 'pending', 'resolved', 'closed', ou 'all' pour tous",
                            'enum' => ['open', 'pending', 'resolved', 'closed', 'all'],
                        ],
                        'limit' => [
                            'type' => 'integer',
                            'description' => 'Nombre maximum de tickets à retourner (défaut: 10)',
                        ],
                    ],
                    'required' => [],
                ],
            ],
            'get_ticket_details' => [
                'name' => 'get_ticket_details',
                'description' => "Récupère les détails complets d'un ticket spécifique, incluant tous les messages et réponses. Utilise cet outil pour voir l'historique de la conversation d'un ticket.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'ticket_id' => [
                            'type' => 'integer',
                            'description' => 'ID du ticket à consulter',
                        ],
                    ],
                    'required' => ['ticket_id'],
                ],
            ],
            'add_ticket_message' => [
                'name' => 'add_ticket_message',
                'description' => "Ajoute un message à un ticket existant. Utilise cet outil pour répondre ou ajouter des informations à un ticket.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'ticket_id' => [
                            'type' => 'integer',
                            'description' => 'ID du ticket',
                        ],
                        'message' => [
                            'type' => 'string',
                            'description' => 'Message à ajouter au ticket',
                        ],
                    ],
                    'required' => ['ticket_id', 'message'],
                ],
            ],
        ];
    }

    /**
     * Exécute un outil avec les paramètres donnés.
     *
     * @param  string  $toolName
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
    public static function executeTool(string $toolName, array $parameters, User $user): array
    {
        return match ($toolName) {
            'create_support_ticket' => self::createSupportTicket($parameters, $user),
            'list_user_tickets' => self::listUserTickets($parameters, $user),
            'get_ticket_details' => self::getTicketDetails($parameters, $user),
            'add_ticket_message' => self::addTicketMessage($parameters, $user),
            default => [
                'success' => false,
                'error' => "Outil inconnu: {$toolName}",
            ],
        };
    }

    /**
     * Crée un nouveau ticket de support.
     *
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
    private static function createSupportTicket(array $parameters, User $user): array
    {
        $subject = $parameters['subject'] ?? '';
        $message = $parameters['message'] ?? '';
        $phoneNumber = $parameters['phone_number'] ?? null;

        if (empty($subject) || empty($message)) {
            return [
                'success' => false,
                'error' => 'Le sujet et le message sont requis',
            ];
        }

        // Ajouter le numéro de téléphone au message si fourni
        if ($phoneNumber) {
            $message .= "\n\n**Numéro de téléphone pour rappel:** " . $phoneNumber;
        }

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => $subject,
            'status' => SupportTicket::STATUS_OPEN,
            'origin_label' => 'Assistant IA',
            'origin_path' => 'dock/assistant-ia',
        ]);

        SupportTicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'is_support' => false,
            'content' => $message,
            'context_label' => 'Assistant IA',
            'context_path' => 'dock/assistant-ia',
        ]);

        return [
            'success' => true,
            'ticket_id' => $ticket->id,
            'ticket_number' => "#{$ticket->id}",
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'ticket_url' => url("/mon-compte/support?ticket={$ticket->id}"),
            'message' => "Ticket créé avec succès. Numéro: #{$ticket->id}",
        ];
    }

    /**
     * Liste les tickets de l'utilisateur.
     *
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
    private static function listUserTickets(array $parameters, User $user): array
    {
        $status = $parameters['status'] ?? 'all';
        $limit = max(self::MIN_TICKET_LIMIT, min((int)($parameters['limit'] ?? self::DEFAULT_TICKET_LIMIT), self::MAX_TICKET_LIMIT));

        $query = SupportTicket::query()
            ->where('user_id', $user->id)
            ->with('messages')
            ->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tickets = $query->get()->map(function (SupportTicket $ticket) {
            $messages = $ticket->messages;
            $lastMessage = $messages->sortByDesc('created_at')->first();
            $supportMessages = $messages->where('is_support', true);
            $hasResponse = $supportMessages->isNotEmpty();
            $lastSupportMessage = $supportMessages->sortByDesc('created_at')->first();
            
            return [
                'id' => $ticket->id,
                'number' => "#{$ticket->id}",
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'status_label' => self::getStatusLabel($ticket->status),
                'created_at' => $ticket->created_at->format('d/m/Y H:i'),
                'last_message_at' => $ticket->last_message_at?->format('d/m/Y H:i'),
                'has_response' => $hasResponse,
                'message_count' => $messages->count(),
                'last_message_preview' => $lastMessage ? substr($lastMessage->content, 0, 100) : null,
                'last_support_message' => $lastSupportMessage ? [
                    'content' => $lastSupportMessage->content,
                    'created_at' => $lastSupportMessage->created_at->format('d/m/Y H:i'),
                    'preview' => substr($lastSupportMessage->content, 0, 150),
                ] : null,
            ];
        });

        return [
            'success' => true,
            'count' => $tickets->count(),
            'tickets' => $tickets->toArray(),
        ];
    }

    /**
     * Récupère les détails d'un ticket.
     *
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
    private static function getTicketDetails(array $parameters, User $user): array
    {
        $ticketId = $parameters['ticket_id'] ?? null;

        if (!$ticketId) {
            return [
                'success' => false,
                'error' => 'L\'ID du ticket est requis',
            ];
        }

        $ticket = SupportTicket::query()
            ->where('id', $ticketId)
            ->where('user_id', $user->id)
            ->with('messages.author')
            ->first();

        if (!$ticket) {
            return [
                'success' => false,
                'error' => 'Ticket non trouvé',
            ];
        }

        $messages = $ticket->messages->map(function (SupportTicketMessage $message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'is_support' => $message->is_support,
                'author' => $message->is_support ? 'Support' : 'Vous',
                'created_at' => $message->created_at->format('d/m/Y H:i'),
            ];
        });

        $hasResponse = $ticket->messages()->where('is_support', true)->exists();

        return [
            'success' => true,
            'ticket' => [
                'id' => $ticket->id,
                'number' => "#{$ticket->id}",
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'status_label' => self::getStatusLabel($ticket->status),
                'created_at' => $ticket->created_at->format('d/m/Y H:i'),
                'has_response' => $hasResponse,
                'can_close' => in_array($ticket->status, [SupportTicket::STATUS_PENDING, SupportTicket::STATUS_RESOLVED]),
                'ticket_url' => url("/mon-compte/support?ticket={$ticket->id}"),
                'messages' => $messages->toArray(),
            ],
        ];
    }

    /**
     * Ajoute un message à un ticket.
     *
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
    private static function addTicketMessage(array $parameters, User $user): array
    {
        $ticketId = $parameters['ticket_id'] ?? null;
        $message = $parameters['message'] ?? '';

        if (!$ticketId || empty($message)) {
            return [
                'success' => false,
                'error' => 'L\'ID du ticket et le message sont requis',
            ];
        }

        $ticket = SupportTicket::query()
            ->where('id', $ticketId)
            ->where('user_id', $user->id)
            ->first();

        if (!$ticket) {
            return [
                'success' => false,
                'error' => 'Ticket non trouvé',
            ];
        }

        SupportTicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'is_support' => false,
            'content' => $message,
            'context_label' => 'Assistant IA',
            'context_path' => 'dock/assistant-ia',
        ]);

        return [
            'success' => true,
            'message' => 'Message ajouté au ticket avec succès',
            'ticket_id' => $ticket->id,
            'ticket_status' => $ticket->fresh()->status,
            'ticket_url' => url("/mon-compte/support?ticket={$ticket->id}"),
        ];
    }

    /**
     * Retourne le label d'un statut.
     *
     * @param  string  $status
     * @return string
     */
    private static function getStatusLabel(string $status): string
    {
        return match ($status) {
            SupportTicket::STATUS_OPEN => 'Ouvert',
            SupportTicket::STATUS_PENDING => 'En attente de votre réponse',
            SupportTicket::STATUS_RESOLVED => 'Résolu',
            SupportTicket::STATUS_CLOSED => 'Fermé',
            default => ucfirst($status),
        };
    }
}
