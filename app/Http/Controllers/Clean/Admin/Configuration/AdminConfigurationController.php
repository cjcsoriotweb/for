<?php

namespace App\Http\Controllers\Clean\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Configuration\CreditUpdate;
use App\Http\Requests\Admin\Configuration\PhotoUpdate;
use App\Models\Team;
use App\Models\TeamCreditTransaction;
use App\Services\Clean\Account\AccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminConfigurationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function credits(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        $transactions = TeamCreditTransaction::query()
            ->with('actor:id,name')
            ->where('team_id', $team->id)
            ->latest()
            ->paginate(15);

        return view('clean.admin.AdminCreditsPage', [
            'organisations' => $organisations,
            'team' => $team,
            'transactions' => $transactions,
        ]);
    }

    public function updatePhoto(PhotoUpdate $request, Team $team)
    {
        $data = $request->validated();
        $path = "teams/{$team->id}/photo.".$data['photo']->extension();
        $data['photo']->storeAs("teams/{$team->id}", basename($path), 'public');
        $team->profile_photo_path = $path;
        $team->save();

        return back()->with('ok', 'Photo mise a jour.');
    }

    public function addCredit(CreditUpdate $request, Team $team)
    {
        $data = $request->validated();

        DB::transaction(function () use ($team, $data) {
            $team->increment('money', (int) $data['montant']);

            TeamCreditTransaction::create([
                'team_id' => $team->id,
                'user_id' => Auth::id(),
                'amount' => (int) $data['montant'],
                'reason' => $data['raison'],
            ]);
        });

        return redirect()
            ->route('application.admin.configuration.credits', $team)
            ->with('ok', 'Credit ajoute.');
    }
}

