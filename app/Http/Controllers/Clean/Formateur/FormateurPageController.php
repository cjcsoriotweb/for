<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;
use App\Services\Clean\Account\AccountService;

class FormateurPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function home()
    {
        return view('out-application.formateur.formateur-home-page');
    }
}
