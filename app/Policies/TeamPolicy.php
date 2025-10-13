<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can add team members.
     */
    public function addTeamMember(User $user, Team $team): bool
    {
        // autoriser le propriétaire OU un membre avec la permission explicite
        return $user->ownsTeam($team)
            || $user->hasTeamPermission($team, 'team:invite');
    }

    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            || $user->hasTeamPermission($team, 'team:manage-roles');
    }

    public function removeTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            || $user->hasTeamPermission($team, 'team:remove');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
    }

    public function canAddApprentis(User $user, Team $team): bool
    {
        return $user->hasTeamPermission($team, 'admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function accessAdmin(User $user, Team $team): bool
    {
        if ($user->ownsTeam($team) || $user->hasTeamRole($team, 'admin') || $user->hasTeamRole($team, 'manager')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits administrateur pour cette équipe.');
        }
    }
    
}
