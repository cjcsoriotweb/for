<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

    public string $contextLabel = '';

    public string $contextPath = '';

    public bool $showLauncher = true;

    protected $listeners = [
        'support-ticket-refresh' => 'loadTickets',
        'support-toggle' => 'toggle',
        'support-open' => 'open',
    ];

    public function mount(bool $showLauncher = true): void
    {
        $this->showLauncher = $showLauncher;
        [$this->contextLabel, $this->contextPath] = $this->resolveContext();
        $this->loadTickets();
    }

    public function toggle(): void
    {
        if ($this->isOpen) {
            $this->isOpen = false;

            return;
        }

        $this->open();
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
                'origin_label' => $this->contextLabel,
                'origin_path' => $this->contextPath,
            ]);

            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'is_support' => false,
                'content' => $validated['message'],
                'read_at' => now(),
                'context_label' => $this->contextLabel,
                'context_path' => $this->contextPath,
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
            'context_label' => $this->contextLabel,
            'context_path' => $this->contextPath,
        ]);

        if (! $ticket->origin_label || ! $ticket->origin_path) {
            $ticket->forceFill([
                'origin_label' => $ticket->origin_label ?: $this->contextLabel,
                'origin_path' => $ticket->origin_path ?: $this->contextPath,
            ])->save();
        }

        $this->message = '';

        $this->loadTickets();
        $this->selectTicket($ticket->id);

        $this->dispatch('support-message-sent', ticketId: $ticket->id);
    }

    public function render()
    {
        return view('livewire.support.chat-widget');
    }

    public function open(): void
    {
        if (! $this->isOpen) {
            $this->isOpen = true;
        }

        $this->loadTickets();
    }

    private function ticketListResource(SupportTicket $ticket): array
    {
        $lastTimestamp = $ticket->last_message_at ?? $ticket->created_at;

        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'status_label' => $this->statusLabel($ticket->status),
            'origin_label' => $ticket->origin_label,
            'origin_path' => $ticket->origin_path,
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
            'origin' => [
                'label' => $ticket->origin_label,
                'path' => $ticket->origin_path,
            ],
            'messages' => $ticket->messages->map(function (SupportTicketMessage $message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'is_support' => $message->is_support,
                    'author' => $message->author?->name ?? ($message->is_support ? __('Support') : __('Vous')),
                    'created_at' => $message->created_at?->toIso8601String(),
                    'created_at_human' => $message->created_at?->diffForHumans(),
                    'context_label' => $message->context_label,
                    'context_path' => $message->context_path,
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

    /**
     * Determine the current page context to send alongside support messages.
     */
    private function resolveContext(): array
    {
        $routeName = optional(request()->route())->getName() ?? '';
        $fullUrl = request()->fullUrl() ?? '/';
        $path = Str::limit($fullUrl, 255, '');

        $source = Str::lower($routeName.' '.request()->path());

        $label = match (true) {
            str_contains($source, 'quiz') => 'Quiz',
            str_contains($source, 'video') => 'Video',
            str_contains($source, 'lesson') => 'Lesson',
            str_contains($source, 'formation') => 'Formation',
            str_contains($source, 'join') => 'Join',
            default => $this->fallbackContextLabel($source),
        };

        return [
            Str::limit($label, 60, ''),
            $path ?: '/',
        ];
    }

    private function fallbackContextLabel(string $source): string
    {
        $path = request()->path() ?: '';
        $segments = array_values(array_filter(explode('/', $path)));

        $candidate = null;
        foreach (array_reverse($segments) as $segment) {
            if ($segment === '' || is_numeric($segment)) {
                continue;
            }

            $candidate = $segment;
            break;
        }

        if (! $candidate) {
            $candidate = $segments[0] ?? ($source ?: 'Page');
        }

        return Str::title(str_replace(['-', '_'], ' ', $candidate));
    }
}
