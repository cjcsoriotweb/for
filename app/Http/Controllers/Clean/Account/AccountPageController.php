<?php

namespace App\Http\Controllers\Clean\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\account\team\SwitchTeamRequest;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function dashboard()
    {
        $user = Auth::user();
        $organisations = $this->accountService->teams()->listByUser($user);
        $invitationsPending = $this->accountService->teams()->pendingInvitations($user);

        // Auto-redirect: if no pending invitations and exactly one team
        if (method_exists($organisations, 'count') && $invitationsPending->isEmpty() && $organisations->count() === 1) {
            $team = $organisations->first();

            // Compute available destinations for this team (role-based)
            $destinations = $this->accountService->teams()->availableDestinations($user, $team);

            if (count($destinations) === 1) {
                return redirect()->to($destinations[0]['route']);
            }

            // If multiple destinations, reuse the switch team view to let user choose
            return $this->accountService->teams()->switchTeam($user, $team);
        }

        return view('out-application.account.dashboard', [
            'organisations' => $organisations,
            'invitations_pending' => $invitationsPending,
        ]);
    }

    public function profile(): View
    {
        $user = Auth::user();

        return view('out-application.account.profile', [
            'user' => $user,
        ]);
    }

    public function editProfileInformation(): View
    {
        $user = Auth::user();

        return view('out-application.account.profile-information', [
            'user' => $user,
        ]);
    }

    public function editPassword(): View
    {
        $user = Auth::user();

        return view('out-application.account.password', [
            'user' => $user,
        ]);
    }

    public function switch(SwitchTeamRequest $request)
    {
        $request = $request->validated();

        $team = Team::find($request['team_id']);
        if (! $team) {
            abort(404);
        }

        return $this->accountService->teams()->switchTeam(Auth::user(), $team);
    }

    public function tickets(): View
    {
        $user = Auth::user();

        $tickets = SupportTicket::query()
            ->where('user_id', optional($user)->id)
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->get();

        $statusLabels = $this->statusLabels();
        $openStatuses = [
            SupportTicket::STATUS_OPEN,
            SupportTicket::STATUS_PENDING,
        ];

        $openTickets = $tickets->filter(fn (SupportTicket $ticket): bool => in_array($ticket->status, $openStatuses, true));

        return view('out-application.account.tickets.index', [
            'tickets' => $tickets,
            'openTickets' => $openTickets,
            'statusLabels' => $statusLabels,
        ]);
    }

    public function ticketsCreate(): View
    {
        return view('out-application.account.tickets.create');
    }

    public function ticketsShow(SupportTicket $ticket): View
    {
        $user = Auth::user();

        if ($ticket->user_id !== optional($user)->id) {
            abort(403);
        }

        $ticket->loadMissing(['messages.author:id,name']);

        return view('out-application.account.tickets.show', [
            'ticket' => $ticket,
            'statusLabel' => $this->statusLabel($ticket->status),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            SupportTicket::STATUS_OPEN => __('Ouvert'),
            SupportTicket::STATUS_PENDING => __('En attente'),
            SupportTicket::STATUS_RESOLVED => __('Resolu'),
            SupportTicket::STATUS_CLOSED => __('Ferme'),
        ];
    }

    private function statusLabel(string $status): string
    {
        return $this->statusLabels()[$status] ?? ucfirst($status);
    }
}
