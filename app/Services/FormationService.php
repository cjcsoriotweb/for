<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\Team;
use App\Services\Formation\AdminFormationService;
use App\Services\Formation\ChapterFormationService;
use App\Services\Formation\StudentFormationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FormationService
{
    public function __construct(
        private readonly AdminFormationService $adminService,
        private readonly StudentFormationService $studentService,
        private readonly ChapterFormationService $chapterService
    ) {}


    public function admin(): AdminFormationService
    {
        return $this->adminService;
    }

    public function chapters()
    {
        return $this->chapterService;
    }


    public function createFormation(array $attributes = []): Formation
    {
        $payload = array_replace([
            'title' => 'Titre par defaut',
            'description' => 'Description par defaut',
            'level' => 'debutant',
            'money_amount' => 0,
        ], $attributes);

        return Formation::create($payload);
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

    /* 

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

    */
}
