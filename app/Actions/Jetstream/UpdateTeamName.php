<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, Team $team, array $input): void
    {
        Gate::forUser($user)->authorize('update', $team);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('updateTeamName');

        $team->forceFill([
            'name' => $input['name'],
        ])->save();

        $admins = $team->allUsers() // inclut le owner
            ->filter(fn (User $u) => $u->hasTeamPermission($team, 'admin') || $u->id === $team->owner_id)
            ->reject(fn (User $u) => $u->id === auth()->id()); // évite d’auto-notifier l’invitant
            
        Notification::send(
            $admins,
            new \App\Notifications\TeamAdminAvert(
                mentionerId: auth()->id(),
                mentionerName: auth()->user()->name,
                context: "renommé l'équipe en '{$input['name']}'",
                url: route('application.admin.configuration.index', $team)
            )
        );
    }
}