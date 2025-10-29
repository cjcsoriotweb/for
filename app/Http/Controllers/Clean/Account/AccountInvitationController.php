<?php

namespace App\Http\Controllers\Clean\Account;

use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Services\Clean\Account\AccountService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AccountInvitationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function accept(TeamInvitation $invitation): RedirectResponse
    {
        $user = Auth::user();

        try {
            $this->accountService->teams()->acceptInvitation($user, $invitation);
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }

        return redirect()
            ->route('user.dashboard')
            ->with('status', __("Invitation acceptee."));
    }
}

