<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Events\TeamMemberRemoved;

class RemoveTeamMember implements RemovesTeamMembers
{
    /**
     * Remove the team member from the given team.
     */
    public function remove(User $user, Team $team, User $teamMember): void
    {
        $this->authorize($user, $team, $teamMember);

        $this->ensureUserDoesNotOwnTeam($teamMember, $team);

        $team->removeUser($teamMember);

        TeamMemberRemoved::dispatch($team, $teamMember);
        
        $admins = $team->allUsers() // inclut le owner
            ->filter(fn (User $u) => $u->hasTeamPermission($team, 'admin') || $u->id === $team->owner_id)
            ->reject(fn (User $u) => $u->id === auth()->id()); // évite d’auto-notifier l’invitant
        Notification::send(
            $admins,
            new \App\Notifications\TeamAdminAvert(
                mentionerId: auth()->id(),
                mentionerName: auth()->user()->name,
                context: "retiré {$teamMember->email} de l'application",
                url: route('application.admin.users.manager', $team)
            )
        );
    }

    /**
     * Authorize that the user can remove the team member.
     */
    protected function authorize(User $user, Team $team, User $teamMember): void
    {
        if (! Gate::forUser($user)->check('removeTeamMember', $team) &&
            $user->id !== $teamMember->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the team.
     */
    protected function ensureUserDoesNotOwnTeam(User $teamMember, Team $team): void
    {
        if ($teamMember->id === $team->owner->id) {
            throw ValidationException::withMessages([
                'team' => [__('You may not leave a team that you created.')],
            ])->errorBag('removeTeamMember');
        }
    }
}
