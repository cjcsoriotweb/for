<?php

namespace App\Services\Clean\Account;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class TeamService
{
    public function listByUser(User $user)
    {
        // Retourne la liste des applications (teams) liees a l'utilisateur.
        return $user->allTeams();
    }

    public function str_slug($string)
    {
        // Convertit une chaine en slug (format URL-friendly).
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function availableDestinations(User $user, Team $team): array
    {
        $destinations = [];

        if ($user->superadmin) {
            $destinations[] = [
                'key' => 'superadmin',
                'badge' => __('Super administrateur'),
                'title' => __('Espace administration'),
                'description' => __('Gerer la plateforme et les parametres globaux de l equipe.'),
                'icon' => 'workspace_premium',
                'gradient' => 'from-sky-500 via-blue-500 to-indigo-500',
                'route' => route('application.admin.index', ['team' => $team]),
            ];

        }

        if ($user->hasTeamRole($team, 'manager')) {
            $destinations[] = [
                'key' => 'manager',
                'badge' => __('Manager'),
                'title' => __('Espace organisateur'),
                'description' => __('Piloter les formations, les membres et les parcours.'),
                'icon' => 'groups',
                'gradient' => 'from-purple-500 via-fuchsia-500 to-rose-500',
                'route' => route('organisateur.index', ['team' => $team]),
            ];
        }

        if ($user->hasTeamRole($team, 'eleve')) {
            $destinations[] = [
                'key' => 'eleve',
                'badge' => __('Eleve'),
                'title' => __('Espace apprenant'),
                'description' => __('Reprendre vos cours et suivre votre progression.'),
                'icon' => 'school',
                'gradient' => 'from-emerald-500 via-teal-500 to-cyan-500',
                'route' => route('eleve.index', ['team' => $team]),
            ];
        }

        return $destinations;
    }

    public function pendingInvitations(User $user)
    {
        return TeamInvitation::query()
            ->with('team')
            ->where('email', $user->email)
            ->latest('id')
            ->get();
    }

    public function acceptInvitation(User $user, TeamInvitation $invitation): Team
    {
        if (strcasecmp($invitation->email, $user->email) !== 0) {
            throw new AuthorizationException(__('Vous ne pouvez pas accepter cette invitation.'));
        }

        $team = $invitation->team;
        if (! $team) {
            $invitation->delete();

            throw new AuthorizationException(__("Cette invitation n'est plus valide."));
        }

        $team->users()->syncWithoutDetaching([
            $user->id => ['role' => $invitation->role],
        ]);

        $invitation->delete();

        return $team;
    }

    public function switchTeam(User $user, Team $team)
    {
        $destinations = $this->availableDestinations($user, $team);

        if (empty($destinations)) {
            return abort(403, __("Vous n'avez aucun acces ici."));
        }

        $role = $user->teamRole($team)?->key;
        $autoRedirectUrl = count($destinations) === 1 ? $destinations[0]['route'] : null;

        return view('out-application.account.switch-team', [
            'team' => $team,
            'destinations' => $destinations,
            'selectedRole' => $role,
            'autoRedirectUrl' => $autoRedirectUrl,
            'shouldAutoRedirect' => $autoRedirectUrl !== null,
            'countdownSeconds' => 3,
        ]);
    }
}
