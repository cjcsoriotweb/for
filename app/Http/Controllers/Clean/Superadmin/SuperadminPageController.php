<?php

namespace App\Http\Controllers\Clean\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\FormationUser;
use App\Models\Signature;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuperadminPageController extends Controller
{
    public function overview()
    {
        $stats = [
            'teams' => Team::count(),
            'users' => User::count(),
            'formations' => Formation::count(),
            'invitations' => TeamInvitation::count(),
            'tickets' => SupportTicket::count(),
        ];

        $trainerCount = AiTrainer::count();

        return view('out-application.superadmin.superadmin-overview-page', [
            'stats' => $stats,
            'trainerCount' => $trainerCount,
        ]);
    }

    public function teamsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $teams = Team::query()
            ->with(['owner:id,name,email'])
            ->withCount(['users', 'teamInvitations'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                            $ownerQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('out-application.superadmin.superadmin-teams-page', [
            'teams' => $teams,
            'search' => $search,
        ]);
    }

    public function usersIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::query()
            ->select(['id', 'name', 'email', 'created_at', 'current_team_id'])
            ->with(['currentTeam:id,name'])
            ->withCount('teams')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('out-application.superadmin.superadmin-users-page', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function userShow(User $user)
    {
        $user->load([
            'teams:id,name,user_id,created_at',
            'teams.owner:id,name,email',
            'currentTeam:id,name,user_id',
        ]);

        // Charger les formations avec les données de la table pivot
        $formations = $user->formations()
            ->with(['teams:id,name'])
            ->withPivot([
                'team_id',
                'completed_at',
                'enrolled_at',
                'status'
            ])
            ->get();

        // Statistiques des formations
        $formationStats = [
            'total' => $formations->count(),
            'completed' => $formations->whereNotNull('pivot.completed_at')->count(),
            'in_progress' => $formations->whereNull('pivot.completed_at')->count(),
        ];

        return view('out-application.superadmin.superadmin-user-show-page', [
            'user' => $user,
            'formations' => $formations,
            'formationStats' => $formationStats,
        ]);
    }

    public function formationsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $sort = (string) $request->query('sort', 'revenue_desc');
        $sortKey = Str::beforeLast($sort, '_');
        $sortDirection = Str::endsWith($sort, '_asc') ? 'asc' : 'desc';

        $aggregatesSub = DB::table('formation_user')
            ->selectRaw('formation_user.formation_id, COUNT(*) as enrollments_count, 0 as revenue_sum, MAX(COALESCE(formation_user.enrolled_at, formation_user.created_at)) as last_enrollment_at')
            ->groupBy('formation_user.formation_id');

        $baseQuery = Formation::query()
            ->select([
                'formations.*',
                DB::raw('COALESCE(stats.enrollments_count, 0) as enrollments_count'),
                DB::raw('COALESCE(stats.revenue_sum, 0) as revenue_sum'),
                DB::raw('stats.last_enrollment_at'),
            ])
            ->leftJoinSub($aggregatesSub, 'stats', 'stats.formation_id', '=', 'formations.id')
            ->withCount('teams')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('formations.title', 'like', "%{$search}%")
                        ->orWhere('formations.description', 'like', "%{$search}%");
                });
            });

        $catalog = (clone $baseQuery)
            ->with('teams:id,name')
            ->orderBy('formations.updated_at', 'desc')
            ->paginate(18)
            ->withQueryString();

        $orderColumn = match ($sortKey) {
            'enrollments' => 'enrollments_count',
            'title' => 'formations.title',
            'updated_at' => 'formations.updated_at',
            default => 'revenue_sum',
        };

        $revenueRows = (clone $baseQuery)
            ->orderBy($orderColumn, $sortDirection)
            ->orderBy('formations.title')
            ->limit(50)
            ->get();

        return view('out-application.superadmin.superadmin-formations-page', [
            'formations' => $catalog,
            'search' => $search,
            'revenueRows' => $revenueRows,
            'sort' => $sort,
        ]);
    }

    public function supportIndex()
    {
        return view('out-application.superadmin.superadmin-support-page');
    }

    public function formationShow(Formation $formation)
    {
        $formation->load(['category:id,name,color']);

        $formation->loadCount([
            'teams',
            'teams as active_teams_count' => fn ($query) => $query->where('formation_in_teams.visible', true),
            'learners',
            'chapters',
            'lessons',
        ]);

        $teamRows = $formation->teams()
            ->with(['owner:id,name,email'])
            ->withCount('users')
            ->select([
                'teams.id',
                'teams.name',
                'teams.user_id',
                'formation_in_teams.visible',
                'formation_in_teams.approved_at',
                'formation_in_teams.approved_by',
                'formation_in_teams.created_at',
            ])
            ->orderByDesc('formation_in_teams.visible')
            ->orderBy('teams.name')
            ->get();

        $enrollmentStatsRow = DB::table('formation_user')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN completed_at IS NOT NULL THEN 1 ELSE 0 END) as completed')
            ->selectRaw('SUM(CASE WHEN completed_at IS NULL THEN 1 ELSE 0 END) as in_progress')
            ->selectRaw('0 as revenue_sum')
            ->where('formation_id', $formation->id)
            ->first();

        $enrollmentStats = [
            'total' => (int) ($enrollmentStatsRow->total ?? 0),
            'completed' => (int) ($enrollmentStatsRow->completed ?? 0),
            'in_progress' => (int) ($enrollmentStatsRow->in_progress ?? 0),
            'revenue_sum' => (int) ($enrollmentStatsRow->revenue_sum ?? 0),
        ];

        $recentLearners = $formation->learners()
            ->select(['users.id', 'users.name', 'users.email', 'users.current_team_id'])
            ->with(['currentTeam:id,name'])
            ->orderByDesc('formation_user.created_at')
            ->limit(15)
            ->get();

        return view('out-application.superadmin.superadmin-formation-show-page', [
            'formation' => $formation,
            'teamRows' => $teamRows,
            'enrollmentStats' => $enrollmentStats,
            'recentLearners' => $recentLearners,
        ]);
    }

    /**
     * Afficher la liste des demandes de validation de formation
     */
    public function completionRequestsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'pending');

        $completionRequests = FormationUser::query()
            ->with([
                'formation:id,title',
                'user:id,name,email',
                'team:id,name',
                'completionValidatedBy:id,name'
            ])
            ->whereNotNull('completion_request_at')
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('completion_request_status', $status);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->whereHas('formation', function ($formationQuery) use ($search) {
                            $formationQuery->where('title', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('team', function ($teamQuery) use ($search) {
                            $teamQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('completion_request_at')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'pending' => FormationUser::where('completion_request_status', 'pending')->count(),
            'approved' => FormationUser::where('completion_request_status', 'approved')->count(),
            'rejected' => FormationUser::where('completion_request_status', 'rejected')->count(),
        ];

        return view('out-application.superadmin.superadmin-completion-requests-page', [
            'completionRequests' => $completionRequests,
            'search' => $search,
            'status' => $status,
            'stats' => $stats,
        ]);
    }

    /**
     * Afficher les détails d'une demande de validation
     */
    public function completionRequestShow(FormationUser $formationUser)
    {
        $formationUser->load([
            'formation:id,title,description',
            'user:id,name,email',
            'team:id,name',
            'trainerSignature',
            'completionValidatedBy:id,name'
        ]);

        // Récupérer la signature de l'étudiant
        $studentSignature = $formationUser->user && $formationUser->user->signatures()->exists() ? $formationUser->user->signatures()->latest()->first() : null;

        return view('out-application.superadmin.superadmin-completion-request-show-page', [
            'formationUser' => $formationUser,
            'studentSignature' => $studentSignature,
        ]);
    }

    /**
     * Approuver une demande de validation de formation
     */
    public function approveCompletionRequest(FormationUser $formationUser, Request $request)
    {
        $request->validate([
            'trainer_signature' => 'required|string',
            'completion_documents.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        $user = auth()->user();

        // Créer la signature du formateur
        $trainerSignature = Signature::create([
            'user_id' => $user->id,
            'signature_data' => $request->input('trainer_signature'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'signed_at' => now(),
        ]);

        // Gérer les fichiers joints
        $completionDocuments = [];
        if ($request->hasFile('completion_documents')) {
            foreach ($request->file('completion_documents') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $mimeType = $file->getMimeType();

                // Générer un nom unique pour le fichier
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $path = $file->storeAs('completion-documents', $filename, 'public');

                $completionDocuments[] = [
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $size,
                    'mime_type' => $mimeType,
                    'uploaded_at' => now()->toISOString(),
                ];
            }
        }

        // Mettre à jour la demande
        $formationUser->update([
            'completion_request_status' => 'approved',
            'trainer_signature_id' => $trainerSignature->id,
            'completion_validated_at' => now(),
            'completion_validated_by' => $user->id,
            'completion_documents' => !empty($completionDocuments) ? $completionDocuments : null,
        ]);

        return redirect()->back()->with('success', 'La demande de validation a été approuvée avec succès.');
    }

    /**
     * Télécharger un document joint à une demande de validation
     */
    public function downloadCompletionDocument(FormationUser $formationUser, $index)
    {
        $documents = $formationUser->completion_documents;

        if (!$documents || !isset($documents[$index])) {
            abort(404, 'Document non trouvé.');
        }

        $document = $documents[$index];

        if (!Storage::disk('public')->exists($document['path'])) {
            abort(404, 'Fichier non trouvé sur le serveur.');
        }

        return Storage::disk('public')->download($document['path'], $document['original_name']);
    }

    /**
     * Rejeter une demande de validation de formation
     */
    public function rejectCompletionRequest(FormationUser $formationUser, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        // Mettre à jour la demande
        $formationUser->update([
            'completion_request_status' => 'rejected',
            'completion_validated_at' => now(),
            'completion_validated_by' => $user->id,
        ]);

        // TODO: Envoyer une notification à l'étudiant avec la raison du rejet

        return redirect()->back()->with('success', 'La demande de validation a été rejetée.');
    }
}
