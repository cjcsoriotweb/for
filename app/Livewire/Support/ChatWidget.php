<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatWidget extends Component
{
    public bool $isOpen = false;
    public bool $showNewTicketForm = false;

    public ?int $activeTicketId = null;

    /** @var array<int, array<string, mixed>> */
    public array $tickets = [];

    /** @var array<string, mixed>|null */
    public ?array $activeTicket = null;

    public string $subject = '';
    public string $message = '';

    protected $listeners = [
        'support-ticket-refresh' => 'loadTickets',
    ];

    public function mount(): void
    {
        $this->loadTickets();
    }

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;

        if ($this->isOpen) {
            $this->loadTickets();
        }
    }

    public function showNewTicket(): void
    {
        $this->showNewTicketForm = true;
        $this->activeTicketId = null;
        $this->activeTicket = null;
        $this->message = '';
    }

    public function cancelNewTicket(): void
    {
        $this->showNewTicketForm = false;
        $this->subject = '';
        $this->message = '';
    }

    public function selectTicket(int $ticketId): void
    {
        $userId = Auth::id();

        $ticket = SupportTicket::query()
            ->where('user_id', $userId)
            ->with([
                'messages.author:id,name',
            ])
            ->findOrFail($ticketId);

        $this->showNewTicketForm = false;
        $this->activeTicketId = $ticket->id;
        $this->activeTicket = $this->ticketDetailResource($ticket);
        $this->message = '';
    }

    public function backToList(): void
    {
        $this->activeTicketId = null;
        $this->activeTicket = null;
        $this->message = '';
    }

    public function loadTickets(): void
    {
        $userId = Auth::id();

        if (! $userId) {
            $this->tickets = [];
            $this->activeTicket = null;

            return;
        }

        $tickets = SupportTicket::query()
            ->where('user_id', $userId)
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->limit(25)
            ->get();

        $this->tickets = $tickets
            ->map(fn (SupportTicket $ticket) => $this->ticketListResource($ticket))
            ->all();

        if ($this->activeTicketId) {
            $this->selectTicket($this->activeTicketId);
        }
    }

    public function createTicket(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $validated = $this->validate([
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'subject' => __('Sujet'),
            'message' => __('Message'),
        ]);

        $ticket = DB::transaction(function () use ($user, $validated) {
            /** @var \App\Models\SupportTicket $ticket */
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'subject' => $validated['subject'],
                'status' => SupportTicket::STATUS_OPEN,
                'last_message_at' => now(),
            ]);

            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'is_support' => false,
                'content' => $validated['message'],
                'read_at' => now(),
            ]);

            return $ticket;
        });

        $this->subject = '';
        $this->message = '';
        $this->showNewTicketForm = false;

        $this->loadTickets();
        $this->selectTicket($ticket->id);

        $this->dispatch('support-ticket-created', ticketId: $ticket->id);
    }

    public function sendMessage(): void
    {
        if (! $this->activeTicketId) {
            return;
        }

        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $validated = $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'message' => __('Message'),
        ]);

        $ticket = SupportTicket::query()
            ->where('user_id', $user->id)
            ->findOrFail($this->activeTicketId);

        if (in_array($ticket->status, [
            SupportTicket::STATUS_RESOLVED,
            SupportTicket::STATUS_CLOSED,
        ], true)) {
            $ticket->reopen();
        }

        SupportTicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'is_support' => false,
            'content' => $validated['message'],
            'read_at' => now(),
        ]);

        $this->message = '';

        $this->loadTickets();
        $this->selectTicket($ticket->id);

        $this->dispatch('support-message-sent', ticketId: $ticket->id);
    }

    public function render()
    {
        return view('livewire.support.chat-widget');
    }

    private function ticketListResource(SupportTicket $ticket): array
    {
        $lastTimestamp = $ticket->last_message_at ?? $ticket->created_at;

        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'status_label' => $this->statusLabel($ticket->status),
            'last_message_at' => optional($lastTimestamp)->toIso8601String(),
            'last_message_human' => optional($lastTimestamp)->diffForHumans(),
        ];
    }

    private function ticketDetailResource(SupportTicket $ticket): array
    {
        $ticket->loadMissing('messages.author:id,name');

        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'status_label' => $this->statusLabel($ticket->status),
            'messages' => $ticket->messages->map(function (SupportTicketMessage $message) use ($ticket) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'is_support' => $message->is_support,
                    'author' => $message->author?->name ?? ($message->is_support ? __('Support') : __('Vous')),
                    'created_at' => $message->created_at?->toIso8601String(),
                    'created_at_human' => $message->created_at?->diffForHumans(),
                ];
            })->all(),
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
