<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TicketReporter extends Component
{
    public string $subject = '';

    public string $description = '';

    public ?string $originLabel = null;

    public ?string $originPath = null;

    public bool $sent = false;

    /** @var array<int, array<string, mixed>> */
    public array $recentTickets = [];

    public ?int $activeTicketId = null;

    /** @var array<string, mixed>|null */
    public ?array $activeTicket = null;

    public string $reply = '';

    public string $mode = 'overview';

    public ?int $defaultTicketId = null;

    public function mount(?string $originPath = null, ?string $originLabel = null, ?int $defaultTicketId = null, string $mode = 'overview'): void
    {
        $this->ensureAuthorized();

        $this->mode = in_array($mode, ['overview', 'create', 'detail'], true) ? $mode : 'overview';
        $this->defaultTicketId = $defaultTicketId;

        $this->originPath = $originPath ?: $this->guessOriginPath();
        $this->originLabel = $originLabel ?: 'Dock Signaler un bug';

        $requestedTicketId = $defaultTicketId ?: $this->requestedTicketFromQuery();

        $this->loadRecentTickets($requestedTicketId);

        // Check if a specific ticket is requested via query parameter
        if ($requestedTicketId) {
            $ticketExists = collect($this->recentTickets)->contains('id', $requestedTicketId);
            if ($ticketExists) {
                $this->selectTicket($requestedTicketId);
                return;
            }
        }

        // Otherwise, select the first ticket if available
        if ($this->mode !== 'create' && $this->activeTicketId === null && count($this->recentTickets) > 0) {
            $firstTicket = $this->recentTickets[0]['id'] ?? null;
            if ($firstTicket) {
                $this->selectTicket((int) $firstTicket);
            }
        }
    }

    public function submit(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $validated = $this->validate(
            [
                'subject' => ['required', 'string', 'min:6', 'max:160'],
                'description' => ['required', 'string', 'min:20', 'max:4000'],
            ],
            [],
            [
                'subject' => __('Sujet'),
                'description' => __('Description'),
            ]
        );

        $originLabel = $this->originLabel ?: 'Dock Signaler un bug';
        $originPath = $this->originPath ?: $this->guessOriginPath();

        $ticketId = null;

        DB::transaction(function () use (&$ticketId, $user, $validated, $originLabel, $originPath): void {
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'subject' => $validated['subject'],
                'status' => SupportTicket::STATUS_OPEN,
                'origin_label' => $originLabel,
                'origin_path' => $originPath,
            ]);

            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'is_support' => false,
                'content' => $validated['description'],
                'context_label' => $originLabel,
                'context_path' => $originPath,
            ]);

            $ticketId = $ticket->id;
        });

        $this->subject = '';
        $this->description = '';
        $this->sent = true;

        $this->loadRecentTickets($ticketId ? (int) $ticketId : null);

        if ($ticketId) {
            $this->selectTicket((int) $ticketId);
        }
    }

    public function selectTicket(int $ticketId): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $ticket = SupportTicket::query()
            ->with([
                'messages.author:id,name',
            ])
            ->where('user_id', $user->id)
            ->findOrFail($ticketId);

        $this->activeTicketId = $ticket->id;
        $this->activeTicket = $this->ticketDetailResource($ticket);
        $this->reply = '';
    }

    public function sendReply(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if (! $this->activeTicketId) {
            return;
        }

        $validated = $this->validate(
            [
                'reply' => ['required', 'string', 'min:2', 'max:4000'],
            ],
            [],
            [
                'reply' => __('Message'),
            ]
        );

        $ticket = SupportTicket::query()
            ->where('user_id', $user->id)
            ->findOrFail($this->activeTicketId);

        $originLabel = $ticket->origin_label ?? 'Dock Signaler un bug';
        $originPath = $ticket->origin_path ?? $this->guessOriginPath();

        DB::transaction(function () use ($user, $ticket, $validated, $originLabel, $originPath): void {
            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'is_support' => false,
                'content' => $validated['reply'],
                'context_label' => $originLabel,
                'context_path' => $originPath,
            ]);
        });

        $this->reply = '';

        $this->loadRecentTickets($ticket->id);
        $this->selectTicket($ticket->id);
    }

    public function render()
    {
        return view('livewire.support.ticket-reporter', [
            'statusLabels' => [
                SupportTicket::STATUS_OPEN => __('Ouvert'),
                SupportTicket::STATUS_PENDING => __('En attente'),
                SupportTicket::STATUS_RESOLVED => __('Resolu'),
                SupportTicket::STATUS_CLOSED => __('Ferme'),
            ],
        ]);
    }

    private function ensureAuthorized(): void
    {
        if (! Auth::check()) {
            abort(403);
        }
    }

    private function loadRecentTickets(?int $ensureTicketId = null): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->recentTickets = [];
            $this->activeTicket = null;
            $this->activeTicketId = null;

            return;
        }

        $limit = $this->mode === 'detail' ? 10 : 5;

        $tickets = SupportTicket::query()
            ->select(['id', 'subject', 'status', 'created_at', 'last_message_at'])
            ->where('user_id', $user->id)
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        if ($ensureTicketId && ! $tickets->contains('id', $ensureTicketId)) {
            $extraTicket = SupportTicket::query()
                ->select(['id', 'subject', 'status', 'created_at', 'last_message_at'])
                ->where('user_id', $user->id)
                ->where('id', $ensureTicketId)
                ->first();

            if ($extraTicket) {
                $tickets->push($extraTicket);
            }
        }

        $this->recentTickets = $tickets
            ->unique('id')
            ->map(function (SupportTicket $ticket): array {
                $lastTimestamp = $ticket->last_message_at ?? $ticket->created_at;

                return [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'status_label' => $this->statusLabel($ticket->status),
                    'created_at_human' => optional($ticket->created_at)->diffForHumans(),
                    'last_message_human' => optional($lastTimestamp)->diffForHumans(),
                ];
            })
            ->values()
            ->all();

        if ($this->activeTicketId) {
            $this->activeTicketId = in_array($this->activeTicketId, array_column($this->recentTickets, 'id'), true)
                ? $this->activeTicketId
                : null;
        }

        if ($this->activeTicketId === null) {
            $this->activeTicket = null;
        }
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            SupportTicket::STATUS_OPEN => __('Ouvert'),
            SupportTicket::STATUS_PENDING => __('En attente'),
            SupportTicket::STATUS_RESOLVED => __('Resolu'),
            SupportTicket::STATUS_CLOSED => __('Ferme'),
            default => ucfirst($status),
        };
    }

    private function ticketDetailResource(SupportTicket $ticket): array
    {
        $ticket->loadMissing([
            'messages.author:id,name',
        ]);

        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'status_label' => $this->statusLabel($ticket->status),
            'created_at_human' => optional($ticket->created_at)->diffForHumans(),
            'messages' => $ticket->messages->map(function (SupportTicketMessage $message): array {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'is_support' => $message->is_support,
                    'author' => $message->author?->name ?? ($message->is_support ? __('Support') : __('Vous')),
                    'created_at_human' => optional($message->created_at)->diffForHumans(),
                ];
            })->all(),
        ];
    }

    private function guessOriginPath(): ?string
    {
        $referer = (string) request()->headers->get('Referer');

        if ($referer !== '') {
            return $referer;
        }

        $previous = url()->previous();

        return $previous !== url()->current() ? $previous : null;
    }

    private function requestedTicketFromQuery(): ?int
    {
        $requested = request()->query('ticket');

        if ($requested !== null && $requested !== '' && is_numeric($requested)) {
            return (int) $requested;
        }

        return null;
    }
}
