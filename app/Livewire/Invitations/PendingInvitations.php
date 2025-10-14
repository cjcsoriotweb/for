<?php

namespace App\Livewire\Invitations;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\TeamInvitation;
use App\Actions\Jetstream\AddTeamMember;
use App\Models\Team;

class PendingInvitations extends Component
{
    /**
     * Liste des invitations (Collection Eloquent)
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    public $invitations;

    protected $listeners = [
        // Permet de rafraîchir depuis ailleurs si tu veux : $this->dispatch('refreshInvites')
        'refreshInvites' => 'loadInvites',
    ];

    public function mount(): void
    {
        $this->invitations = collect();
        $this->loadInvites(); // premier chargement
    }

    /**
     * Appelée par wire:poll dans la vue
     */
    public function loadInvites(): void
    {
        if (! Auth::check()) {
            $this->invitations = collect();
            return;
        }

        $email = Auth::user()->email;

        $this->invitations = TeamInvitation::query()
            ->select(['id', 'team_id', 'email', 'role', 'created_at'])
            ->with(['team:id,name'])
            ->where('email', $email)
            ->latest('id')
            ->get();
    }

    public function accept(int $invitationId): void
    {
        $user = Auth::user();
        abort_unless($user, 401);

        // Sécurise : on ne traite que les invitations de l’email connecté
        $inv = TeamInvitation::whereKey($invitationId)
            ->where('email', $user->email)
            ->firstOrFail();

        $team = Team::find($inv->team_id);
        if (! $team) {
            $inv->delete();
            $this->dispatch('toast', type: 'warning', message: "L'équipe n'existe plus.");
            $this->loadInvites();
            return;
        }

        app(AddTeamMember::class)->add(
            $team->owner,           // acteur autorisé (owner)
            $team,
            $user->email,
            $inv->role ?: 'eleve'   // rôle par défaut
        );

        if (is_null($user->current_team_id)) {
            $user->forceFill(['current_team_id' => $team->id])->save();
        }

        $inv->delete();

        $this->dispatch('toast', type: 'success', message: 'Invitation acceptée ✅');
        $this->loadInvites(); // met à jour la liste immédiatement

        // Redirige vers la page de l’équipe
        $this->redirect(route('application.index', $team));
    }

    public function decline(int $invitationId): void
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $inv = TeamInvitation::whereKey($invitationId)
            ->where('email', $user->email)
            ->firstOrFail();

        $inv->delete();

        $this->dispatch('toast', type: 'info', message: 'Invitation refusée ✋');
        $this->loadInvites(); // met à jour la liste immédiatement
    }

    public function render()
    {
        // On passe la collection à la vue (facultatif, la propriété est déjà accessible)
        return view('livewire.invitations.pending-invitations', [
            'invitations' => $this->invitations,
        ]);
    }
}
