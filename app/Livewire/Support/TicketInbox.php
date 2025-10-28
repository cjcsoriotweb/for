<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class TicketInbox extends Component
{
    public string $statusFilter = SupportTicket::STATUS_OPEN;
    public string $search = '';

    public ?int $activeTicketId = null;

    /** @var array<int, array<string, mixed>> */
    public array $tickets = [];

    /** @var array<string, mixed>|null */
    public ?array $activeTicket = null;

    public string $message = '';

    protected $queryString = [
        'statusFilter' => ['except' => SupportTicket::STATUS_OPEN],
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->ensureAuthorized();
        $this->loadTickets();
    }

    public function updatedStatusFilter(): void
    {
        $this->loadTickets();
    }

    public function updatedSearch(): void
    {
        $this->loadTickets();
    }

    public function selectTicket(int $ticketId): void
    {
        $this->ensureAuthorized();

        $ticket = SupportTicket::query()
            ->with([
                'owner:id,name,email',
                'messages.author:id,name,email',
            ])
            ->findOrFail($ticketId);

        $this->activeTicketId = $ticket->id;
        $this->activeTicket = $this->ticketDetailResource($ticket);
        $this->message = '';
    }

    public function sendResponse(): void
    {
        $this->ensureAuthorized();

        if (! $this->activeTicketId) {
            return;
        }

        $agent = Auth::user();

        $validated = $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'message' => __('Message'),
        ]);

        $ticket = SupportTicket::query()->findOrFail($this->activeTicketId);

        DB::transaction(function () use ($ticket, $agent, $validated): void {
            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => optional($agent)->id,
                'is_support' => true,
                'content' => $validated['message'],
                'read_at' => now(),
                'context_label' => 'Support',
                'context_path' => request()->fullUrl() ?? 'superadmin/support',
            ]);
        });

        $this->message = '';
        $this->loadTickets();
        $this->selectTicket($ticket->id);
    }

    public function markResolved(): void
    {
        $this->updateTicketStatus(function (SupportTicket $ticket, $user): void {
            $ticket->markAsResolved($user);
        });
    }

    public function closeTicket(): void
    {
        $this->updateTicketStatus(function (SupportTicket $ticket, $user): void {
            $ticket->close($user);
        });
    }

    public function reopenTicket(): void
    {
        $this->updateTicketStatus(function (SupportTicket $ticket): void {
            $ticket->reopen();
        });
    }

    public function loadTickets(): void
    {
        $this->ensureAuthorized();

        $ticketsQuery = SupportTicket::query()
            ->with('owner:id,name,email')
            ->when($this->statusFilter !== 'all', function ($query): void {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->search !== '', function ($query): void {
                $term = '%' . Str::lower($this->search) . '%';
                $query->where(function ($inner) use ($term): void {
                    $inner
                        ->whereRaw('LOWER(subject) like ?', [$term])
                        ->orWhereHas('owner', function ($ownerQuery) use ($term): void {
                            $ownerQuery
                                ->whereRaw('LOWER(name) like ?', [$term])
                                ->orWhereRaw('LOWER(email) like ?', [$term]);
                        });
                });
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->limit(50);

        $this->tickets = $ticketsQuery
            ->get()
            ->map(fn (SupportTicket $ticket) => $this->ticketListResource($ticket))
            ->all();

        if ($this->activeTicketId) {
            $this->selectTicket($this->activeTicketId);
        }
    }

    public function render()
    {
        return view('livewire.support.ticket-inbox', [
            'statusOptions' => [
                SupportTicket::STATUS_OPEN => __('Ouverts'),
                SupportTicket::STATUS_PENDING => __('En attente utilisateur'),
                SupportTicket::STATUS_RESOLVED => __('Resolus'),
                SupportTicket::STATUS_CLOSED => __('Fermes'),
                'all' => __('Tous les tickets'),
            ],
        ]);
    }

    private function ensureAuthorized(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->superadmin) {
            abort(403);
        }
    }

    private function updateTicketStatus(callable $callback): void
    {
        $this->ensureAuthorized();

        if (! $this->activeTicketId) {
            return;
        }

        $ticket = SupportTicket::query()->findOrFail($this->activeTicketId);

        $callback($ticket, Auth::user());

        $this->loadTickets();
        $this->selectTicket($ticket->id);
    }

    private function ticketListResource(SupportTicket $ticket): array
    {
        $lastTimestamp = $ticket->last_message_at ?? $ticket->created_at;

        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'status_label' => $this->statusLabel($ticket->status),
            'owner' => [
                'name' => $ticket->owner?->name,
                'email' => $ticket->owner?->email,
            ],
            'origin_label' => $ticket->origin_label,
            'origin_path' => $ticket->origin_path,
            'last_message_at' => optional($lastTimestamp)->toIso8601String(),
            'last_message_human' => optional($lastTimestamp)->diffForHumans(),
        ];
    }

    private function ticketDetailResource(SupportTicket $ticket): array
    {
        $ticket->loadMissing([
            'owner:id,name,email',
            'messages.author:id,name,email',
        ]);

        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'status_label' => $this->statusLabel($ticket->status),
            'owner' => [
                'name' => $ticket->owner?->name,
                'email' => $ticket->owner?->email,
            ],
            'origin' => [
                'label' => $ticket->origin_label,
                'path' => $ticket->origin_path,
            ],
            'messages' => $ticket->messages->map(fn (SupportTicketMessage $message) => [
                'id' => $message->id,
                'content' => $message->content,
                'is_support' => $message->is_support,
                'author' => $message->author?->name ?? ($message->is_support ? __('Support') : __('Utilisateur')),
                'created_at' => $message->created_at?->toIso8601String(),
                'created_at_human' => $message->created_at?->diffForHumans(),
                'context_label' => $message->context_label,
                'context_path' => $message->context_path,
            ])->all(),
        ];
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
}
