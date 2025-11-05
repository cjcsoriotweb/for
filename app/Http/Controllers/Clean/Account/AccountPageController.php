<?php

namespace App\Http\Controllers\Clean\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\account\team\SwitchTeamRequest;
use App\Models\Signature;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use Illuminate\Http\Request;
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

        return view('out-application.account.dashboard', [
            'organisations' => $organisations,
            'invitations_pending' => $invitationsPending,
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

    /**
     * Afficher la page de gestion de signature
     */
    public function signature(): View
    {
        $user = Auth::user();

        return view('out-application.account.signature', compact('user'));
    }

    /**
     * Enregistrer la signature de l'utilisateur
     */
    public function storeSignature(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'signature' => 'required|string',
        ]);

        try {
            Signature::create([
                'user_id' => $user->id,
                'signature_data' => $request->signature,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'signed_at' => now(),
            ]);

            return back()->with('success', 'Votre signature a été enregistrée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre signature.');
        }
    }
}
