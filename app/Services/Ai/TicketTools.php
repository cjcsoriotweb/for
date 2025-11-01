<?php

namespace App\Services\Ai;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Service pour les outils IA li+�s aux tickets de support.
 */
class TicketTools
{
    /**
     * Limite minimum de tickets +� retourner.
     */
    private const MIN_TICKET_LIMIT = 1;

    /**
     * Limite maximum de tickets +� retourner.
     */
    private const MAX_TICKET_LIMIT = 50;

    /**
     * Limite par d+�faut de tickets +� retourner.
     */
    private const DEFAULT_TICKET_LIMIT = 10;

    /**
     * D+�finition des outils disponibles pour l'IA.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getToolDefinitions(): array
    {
        return [
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
                            'description' => 'Nombre maximum de tickets +� retourner (d+�faut: 10)',
                        ],
                    ],
                    'required' => [],
                ],
            ],
            'get_ticket_details' => [
                'name' => 'get_ticket_details',
                'description' => "R+�cup+�re les d+�tails complets d'un ticket sp+�cifique, incluant tous les messages et r+�ponses. Utilise cet outil pour voir l'historique de la conversation d'un ticket.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'ticket_id' => [
                            'type' => 'integer',
                            'description' => 'ID du ticket +� consulter',
                        ],
                    ],
                    'required' => ['ticket_id'],
                ],
            ],
            'add_ticket_message' => [
                'name' => 'add_ticket_message',
                'description' => "Ajoute un message +� un ticket existant. Utilise cet outil pour r+�pondre ou ajouter des informations +� un ticket.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'ticket_id' => [
                            'type' => 'integer',
                            'description' => 'ID du ticket',
                        ],
                        'message' => [
                            'type' => 'string',
                            'description' => 'Message +� ajouter au ticket',
                        ],
                    ],
                    'required' => ['ticket_id', 'message'],
                ],
            ],
        ];
    }

    /**
     * Ex+�cute un outil avec les param+�tres donn+�s.
     *
     * @param  string  $toolName
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
    public static function executeTool(string $toolName, array $parameters, User $user): array
    {
        return match ($toolName) {
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
     * Cr+�e un nouveau ticket de support.
     *
     * @param  array<string, mixed>  $parameters
     * @param  User  $user
     * @return array<string, mixed>
     */
