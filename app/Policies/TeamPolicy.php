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
            || $user->hasTeamPermission($team, 'invite');
    }

    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            || $user->hasTeamPermission($team, 'manage-roles');
    }

    public function removeTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            || $user->hasTeamPermission($team, 'action:users_invite');
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
        return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'admin');
    }


    public function configuration(User $user, Team $team): bool
    {
        if ($user->ownsTeam($team) || $user->hasTeamPermission($team, 'configuration')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour modifier la configuration de cette équipe.');
        }
    }
    /**
     * Determine whether the user can update the model.
     */
    public function board(User $user, Team $team): bool
    {
        if ($user->ownsTeam($team) || $user->hasTeamPermission($team, 'board')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour le tableau de bord de cette équipe.');
        }
    }
    /**
     * Determine whether the user can manage formations.
     */

    public function manage_formation(User $user, Team $team): bool
    {
        if ($user->hasTeamPermission($team, 'manage_formation')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour gérer les formations de cette équipe.');
        }
    }

    /**
     * Determine whether the user can manage users.
     */
    public function manage_roles(User $user, Team $team): bool
    {
        if ($user->hasTeamPermission($team, 'manage_roles')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour gérer les utilisateurs de cette équipe.');
        }
    }
    /**
     * Determine whether the user can list users.
     */
    public function list_users(User $user, Team $team): bool
    {
        if ($user->hasTeamPermission($team, 'list_users')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'êtes pas membre de cette équipe.');
        }
    }
    /**
     * Determine whether the user can invite users.
     */

    public function invite_users(User $user, Team $team): bool
    {
        if ($user->hasTeamPermission($team, 'invite_users')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour inviter des membres dans cette équipe.');
        }
    }
    /**
     * Determine whether the user can manage users.
     */

    public function manage_users(User $user, Team $team): bool
    {
        if ($user->hasTeamPermission($team, 'manage_users')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour gérer les utilisateurs de cette équipe.');
        }
    }
    /**
     * Determine whether the user can invite users.
     */
    
    public function invite(User $user, Team $team): bool
    {
        if ($user->hasTeamPermission($team, 'invite_users')) {
            return true;
        } else {
            return abort(403, 'Accès refusé. Vous n\'avez pas les droits pour inviter des membres dans cette équipe.');
        }
    }
    
}
