<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\Team;
use App\Services\Formation\AdminFormationService;
use App\Services\Formation\StudentFormationService;
use App\Services\Formation\SuperAdminFormationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FormationService
{
    public function __construct(
        private readonly SuperAdminFormationService $superAdminService,
        private readonly AdminFormationService $adminService,
        private readonly StudentFormationService $studentService
    ) {
    }

    public function superAdmin(): SuperAdminFormationService
    {
        return $this->superAdminService;
    }

    public function admin(): AdminFormationService
    {
        return $this->adminService;
    }

    /**
     * Alias utile pour conserver la compatibilite du code existant.
     */
    public function team(): AdminFormationService
    {
        return $this->adminService;
    }

    public function student(): StudentFormationService
    {
        return $this->studentService;
    }

    public function makeFormationVisibleForTeam(Formation $formation, Team $team)
    {
        return $this->adminService->makeFormationVisibleForTeam($formation, $team);
    }

    public function makeFormationInvisibleForTeam(Formation $formation, Team $team)
    {
        return $this->adminService->makeFormationInvisibleForTeam($formation, $team);
    }

    public function listWithTeamFlags(Team $team): Collection
    {
        return $this->adminService->listWithTeamFlags($team);
    }

    public function paginateWithTeamFlags(
        Team $team,
        int $perPage = 15,
        ?string $search = null,
        ?string $orderBy = 'title',
        string $direction = 'asc'
    ): LengthAwarePaginator {
        return $this->adminService->paginateWithTeamFlags($team, $perPage, $search, $orderBy, $direction);
    }

    public function createFormation(
        $title = 'Titre par defaut',
        $description = 'Description par defaut',
        $level = 'debutant',
        $moneyAmount = 0
    ) {
        return $this->superAdminService->createFormation([
            'title' => $title,
            'description' => $description,
            'level' => $level,
            'money_amount' => $moneyAmount,
        ]);
    }
}

