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

    /** @var array<int, array<string, string|null>> */
    public array $recentTickets = [];

    public function mount(?string $originPath = null, ?string $originLabel = null): void
    {
        $this->ensureAuthorized();

        $this->originPath = $originPath ?: $this->guessOriginPath();
        $this->originLabel = $originLabel ?: 'Dock Signaler un bug';

        $this->loadRecentTickets();
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

        DB::transaction(function () use ($user, $validated, $originLabel, $originPath): void {
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
        });

        $this->subject = '';
        $this->description = '';
        $this->sent = true;

        $this->loadRecentTickets();
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

    private function loadRecentTickets(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->recentTickets = [];

            return;
        }

        $this->recentTickets = SupportTicket::query()
            ->select(['id', 'subject', 'status', 'created_at', 'last_message_at'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function (SupportTicket $ticket): array {
                $lastTimestamp = $ticket->last_message_at ?? $ticket->created_at;

                return [
                    'id' => (string) $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'status_label' => $this->statusLabel($ticket->status),
                    'created_at_human' => optional($ticket->created_at)->diffForHumans(),
                    'last_message_human' => optional($lastTimestamp)->diffForHumans(),
                ];
            })
            ->all();
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

    private function guessOriginPath(): ?string
    {
        $referer = (string) request()->headers->get('Referer');

        if ($referer !== '') {
            return $referer;
        }

        $previous = url()->previous();

        return $previous !== url()->current() ? $previous : null;
    }
}

