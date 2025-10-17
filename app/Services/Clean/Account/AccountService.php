<?php

namespace App\Services\Clean\Account;

class AccountService 
{
    public function __construct(
        private readonly TeamService $teamService,
    ) {
    }

    public function teams(): TeamService
    {
        return $this->teamService;
    }
}