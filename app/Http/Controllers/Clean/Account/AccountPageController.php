<?php

namespace App\Http\Controllers\Clean\Account;

use App\Http\Controllers\Controller;
use App\Services\Clean\Account\AccountService;
use Illuminate\Support\Facades\Auth;

class AccountPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function dashboard()
    {
        dd($this->accountService->teams()->listByUser(Auth::user()));

        return view('clean.account.dashboard');
    }
}