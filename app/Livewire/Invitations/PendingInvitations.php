<?php

namespace App\Livewire\Invitations;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\TeamInvitation;
use App\Actions\Jetstream\AddTeamMember;
use App\Models\Team;

class PendingInvitations extends Component
{
    public $invitations = [];

    protected $listeners = ['refreshInvites' => '$refresh'];

    public function mount()
    {
        $this->loadInvites();
    }

    public function loadInvites(): void
    {
        $email = Auth::user()->email;

        $this->invitations = TeamInvitation::query()
            ->with('team:id,name')
            ->where('email', $email)
            ->latest()
            ->get()
            ->toArray();
    }

    public function accept(int $invitationId): void
    {
        $user = Auth::user();
        $inv = TeamInvitation::findOrFail($invitationId);

        // sécurité : l’invitation doit être pour l’e-mail connecté
        if ($inv->email !== $user->email) {
            abort(403);
        }

        $team = Team::find($inv->team_id);
        if (! $team) {
            // équipe supprimée : on jette l’invitation
            $inv->delete();
            $this->dispatch('toast', type:'warning', message: "L'équipe n'existe plus.");
            $this->loadInvites();
            return;
        }

        app(AddTeamMember::class)->add(
            $team->owner,            // acteur autorisé
            $team,
            $user->email,
            $inv->role ?: 'eleve'
        );

        if (is_null($user->current_team_id)) {
            $user->forceFill(['current_team_id' => $team->id])->save();
        }

        $inv->delete();

        $this->dispatch('toast', type:'success', message:'Invitation acceptée ✅');
        $this->loadInvites();

    }

    public function decline(int $invitationId): void
    {
        $user = Auth::user();
        $inv = TeamInvitation::findOrFail($invitationId);

        if ($inv->email !== $user->email) {
            abort(403);
        }

        $inv->delete();

        $this->dispatch('toast', type:'info', message:'Invitation refusée ✋');
        $this->loadInvites();
    }

    public function render()
    {
        return view('livewire.invitations.pending-invitations');
    }
}
