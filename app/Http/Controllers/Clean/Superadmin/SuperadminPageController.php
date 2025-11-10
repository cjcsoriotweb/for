<?php

namespace App\Http\Controllers\Clean\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\FormationInTeams;
use App\Models\FormationUser;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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

    public function console()
    {
        return view('out-application.superadmin.superadmin-console-page');
    }

    public function comptaDashboard(Request $request)
    {
        $filterMonth = $request->query('filter_month');
        if (is_string($filterMonth) && preg_match('/^\d{4}-\d{2}$/', $filterMonth)) {
            $selectedMonth = Carbon::createFromFormat('Y-m', $filterMonth);
        } else {
            $selectedMonth = now();
            $filterMonth = $selectedMonth->format('Y-m');
        }

        $monthStart = $selectedMonth->copy()->startOfMonth();
        $monthEnd = $selectedMonth->copy()->endOfMonth();

        $filterTeam = $request->query('filter_team');
        $filterTeam = is_numeric($filterTeam) ? (int) $filterTeam : null;
        $filterFormation = $request->query('filter_formation');
        $filterFormation = is_numeric($filterFormation) ? (int) $filterFormation : null;

        // Formations actives: formations ayant des inscriptions commencées dans la période sélectionnée
        $activeFormationsThisMonth = FormationUser::query()
            ->whereNotNull('enrolled_at')
            ->whereBetween('enrolled_at', [$monthStart, $monthEnd])
            ->when($filterTeam, fn ($q) => $q->where('team_id', $filterTeam))
            ->when($filterFormation, fn ($q) => $q->where('formation_id', $filterFormation))
            ->distinct('formation_id')
            ->count('formation_id');

        // Elèves ayant commencé pendant la période + filtres
        $studentsStartedThisMonth = FormationUser::query()
            ->whereNotNull('enrolled_at')
            ->whereBetween('enrolled_at', [$monthStart, $monthEnd])
            ->when($filterTeam, fn ($q) => $q->where('team_id', $filterTeam))
            ->when($filterFormation, fn ($q) => $q->where('formation_id', $filterFormation))
            ->count();

        // Licences non consommées (quand filtré: par équipe et/ou formation)
        $unusedFormationSlots = FormationInTeams::query()
            ->whereNotNull('usage_quota')
            ->whereColumn('usage_consumed', '<', 'usage_quota')
            ->when($filterTeam, fn ($q) => $q->where('team_id', $filterTeam))
            ->when($filterFormation, fn ($q) => $q->where('formation_id', $filterFormation))
            ->count();

        $availableTeams = Team::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $availableFormations = Formation::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $topFormations = Formation::query()
            ->with(['teams'])
            ->when($filterTeam, function (Builder $query) use ($filterTeam) {
                $query->whereHas('teams', function (Builder $query) use ($filterTeam) {
                    $query->where('teams.id', $filterTeam);
                });
            })
            ->when($filterFormation, function (Builder $query) use ($filterFormation) {
                $query->where('formations.id', $filterFormation);
            })
            ->withCount([
                'learners as learners_count' => function (Builder $query) use ($monthStart, $monthEnd, $filterTeam) {
                    $query->whereNotNull('formation_user.enrolled_at')
                        ->whereBetween('formation_user.enrolled_at', [$monthStart, $monthEnd]);
                    if ($filterTeam) {
                        $query->where('formation_user.team_id', $filterTeam);
                    }
                },
                'learners as completed_learners_count' => function (Builder $query) use ($monthStart, $monthEnd, $filterTeam) {
                    $query->whereNotNull('formation_user.enrolled_at')
                        ->whereBetween('formation_user.enrolled_at', [$monthStart, $monthEnd])
                        ->whereNotNull('formation_user.completed_at');
                    if ($filterTeam) {
                        $query->where('formation_user.team_id', $filterTeam);
                    }
                },
            ])
            ->orderByDesc('learners_count')
            ->take(5)
            ->get();

        $formations = $topFormations->map(function (Formation $formation) {
            $team = $formation->teams->first();
            $statusKey = 'not-started';

            if ($formation->learners_count > 0) {
                $statusKey = $formation->completed_learners_count >= $formation->learners_count
                    ? 'completed'
                    : 'in-progress';
            }

            $statusClasses = match ($statusKey) {
                'completed' => [
                    'bg' => 'bg-green-100 dark:bg-green-900/40',
                    'text' => 'text-green-800 dark:text-green-300',
                ],
                'in-progress' => [
                    'bg' => 'bg-blue-100 dark:bg-blue-900/40',
                    'text' => 'text-blue-800 dark:text-blue-300',
                ],
                default => [
                    'bg' => 'bg-gray-100 dark:bg-gray-700/40',
                    'text' => 'text-gray-800 dark:text-gray-300',
                ],
            };

            $statusLabel = match ($statusKey) {
                'completed' => __('Terminée'),
                'in-progress' => __('En cours'),
                default => __('Non commencée'),
            };

            return [
                'id' => $formation->id,
                'name' => $formation->title,
                'team' => $team->name ?? __('Non assignée'),
                'students' => $formation->learners_count,
                'status_label' => $statusLabel,
                'status_classes' => $statusClasses,
                'route' => route('superadmin.formations.show', $formation),
            ];
        });



        // Suivi des élèves inscrits (par période/équipe/formation)
        $sort = (string) $request->query('sort', 'date_desc');
        $allowedSorts = ['date_desc', 'date_asc', 'status_desc', 'status_asc'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'date_desc';
        }

        $enrollments = FormationUser::query()
            ->with([
                'formation:id,title',
                'team:id,name',
                'user:id,name',
            ])
            ->whereNotNull('enrolled_at')
            ->whereBetween('enrolled_at', [$monthStart, $monthEnd])
            ->when($filterTeam, fn ($q) => $q->where('team_id', $filterTeam))
            ->when($filterFormation, fn ($q) => $q->where('formation_id', $filterFormation))
            ->orderByDesc('enrolled_at')
            ->limit(100)
            ->get()
            ->map(function (FormationUser $fu) {
                $statusKey = $fu->completed_at ? 'completed' : (($fu->last_seen_at || $fu->enrolled_at) ? 'in-progress' : 'not-started');

                $statusClasses = match ($statusKey) {
                    'completed' => [
                        'bg' => 'bg-green-100 dark:bg-green-900/40',
                        'text' => 'text-green-800 dark:text-green-300',
                    ],
                    'in-progress' => [
                        'bg' => 'bg-blue-100 dark:bg-blue-900/40',
                        'text' => 'text-blue-800 dark:text-blue-300',
                    ],
                    default => [
                        'bg' => 'bg-gray-100 dark:bg-gray-700/40',
                        'text' => 'text-gray-800 dark:text-gray-300',
                    ],
                };

                $statusLabel = match ($statusKey) {
                    'completed' => __('Terminée'),
                    'in-progress' => __('En cours'),
                    default => __('Non commencée'),
                };

                return [
                    'formation_name' => $fu->formation?->title ?? __('Inconnue'),
                    'team_name' => $fu->team?->name ?? __('Non assignée'),
                    'student_name' => $fu->user?->name ?? __('Utilisateur supprimé'),
                    'status_label' => $statusLabel,
                    'status_classes' => $statusClasses,
                    'status_key' => $statusKey,
                    'enrolled_at' => $fu->enrolled_at,
                    'formation_route' => $fu->formation ? route('superadmin.formations.show', $fu->formation) : '#',
                    'user_route' => $fu->user ? route('superadmin.users.show', $fu->user) : '#',
                    'report_route' => ($fu->team_id && $fu->formation_id && $fu->user_id)
                        ? route('organisateur.formations.students.report', [
                            'team' => $fu->team_id,
                            'formation' => $fu->formation_id,
                            'student' => $fu->user_id,
                        ])
                        : '#',
                ];
            });
        // Tri c�t� collection si demand�
        if (in_array($sort, ['status_desc', 'status_asc'], true)) {
            $rank = [
                'not-started' => 0,
                'in-progress' => 1,
                'completed' => 2,
            ];
            $enrollments = $enrollments->sortBy(
                fn (array $e) => $rank[$e['status_key']] ?? 0,
                SORT_REGULAR,
                $sort === 'status_desc'
            )->values();
        } elseif ($sort === 'date_asc') {
            $enrollments = $enrollments->sortBy('enrolled_at')->values();
        }

        return view('out-application.superadmin.compta.index', [
            'stats' => [
                'active_formations' => $activeFormationsThisMonth,
                'students_started' => $studentsStartedThisMonth,
                'unused_formations' => $unusedFormationSlots,
            ],
            'formations' => $formations,
            'enrollments' => $enrollments,
            'filters' => [
                'month' => $filterMonth,
                'team' => $filterTeam,
                'formation' => $filterFormation,
                'sort' => $sort,
            ],
            'selectedMonthLabel' => $selectedMonth->translatedFormat('F Y'),
            'filterTeams' => $availableTeams,
            'filterFormations' => $availableFormations,
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
            'ownedTeams:id,name,user_id,created_at',
            'ownedTeams.owner:id,name,email',
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

    public function removeUserFormation(Request $request, User $user, Formation $formation)
    {
        $formationUser = FormationUser::where('user_id', $user->id)
            ->where('formation_id', $formation->id)
            ->first();

        if (!$formationUser) {
            return redirect()->back()->with('error', 'Cette formation n\'est pas associée à l\'utilisateur.');
        }

        DB::transaction(function () use ($formation, $user, $formationUser) {
            $lessonIds = $formation->lessons()->pluck('lessons.id');

            if ($lessonIds->isNotEmpty()) {
                DB::table('lesson_user')
                    ->where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->delete();
            }

            $formationUser->delete();
        });

        return redirect()->back()->with('success', 'L\'utilisateur a été retiré de la formation et son historique a été supprimé.');
    }

    public function formationsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

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

        $startOfMonth = now()->startOfMonth();

        $followStats = [
            'activation_total' => FormationInTeams::visible()->count(),
            'activated_this_month' => FormationInTeams::visible()
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'started_this_month' => FormationUser::where('enrolled_at', '>=', $startOfMonth)->count(),
            'completed_this_month' => FormationUser::where('completed_at', '>=', $startOfMonth)->count(),
        ];

        return view('out-application.superadmin.superadmin-formations-page', [
            'formations' => $catalog,
            'search' => $search,
            'followStats' => $followStats,
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
            'completionValidatedBy:id,name'
        ]);

        return view('out-application.superadmin.superadmin-completion-request-show-page', [
            'formationUser' => $formationUser,
        ]);
    }

    /**
     * Approuver une demande de validation de formation
     */
    public function approveCompletionRequest(FormationUser $formationUser, Request $request)
    {
        $request->validate([
            'completion_documents.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        $user = auth()->user();

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

        // Mettre à jour la demande + marquer la formation comme complétée pour l'élève
        $formationUser->update([
            'completion_request_status' => 'approved',
            'completion_validated_at' => now(),
            'completion_validated_by' => $user->id,
            'completion_documents' => !empty($completionDocuments) ? $completionDocuments : null,
            'need_verif' => false,
            'status' => 'completed',
            'completed_at' => now(),
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

    /**
     * Annuler une validation de formation approuvée
     */
    public function cancelCompletionRequest(FormationUser $formationUser, Request $request)
    {
        // Vérifier que la demande est approuvée
        if ($formationUser->completion_request_status !== 'approved') {
            return redirect()->back()->with('error', 'Cette demande ne peut pas être annulée car elle n\'est pas approuvée.');
        }

        // Remettre le statut en attente et supprimer les informations de validation
        $formationUser->update([
            'completion_request_status' => 'pending',
            'completion_validated_at' => null,
            'completion_validated_by' => null,
            'completion_documents' => null, // Supprimer aussi les documents joints lors de l'annulation
        ]);

        return redirect()->back()->with('success', 'La validation de la demande a été annulée avec succès.');
    }

    public function backupDatabase(Request $request)
    {
        $connectionName = config('database.default');
        $connection = config("database.connections.{$connectionName}", []);

        if (($connection['driver'] ?? '') !== 'mysql') {
            return redirect()
                ->route('superadmin.db')
                ->with('status', __('La sauvegarde n’est pas disponible pour la connexion « :connection ».', [
                    'connection' => $connectionName,
                ]));
        }

        $database = $connection['database'] ?? null;
        if (!$database) {
            return redirect()
                ->route('superadmin.db')
                ->with('status', __('Impossible de déterminer la base de données à sauvegarder.'));
        }

        $dumpCommand = env('DB_BACKUP_COMMAND', 'mysqldump');

        $arguments = [
            $dumpCommand,
            '--single-transaction',
            '--quick',
            '--skip-lock-tables',
            '--default-character-set=utf8mb4',
            '--host=' . ($connection['host'] ?? '127.0.0.1'),
            '--port=' . ($connection['port'] ?? 3306),
            '--user=' . ($connection['username'] ?? 'root'),
        ];

        if (!empty($connection['password'])) {
            $arguments[] = '--password=' . $connection['password'];
        }

        $arguments[] = $database;

        $process = new Process($arguments);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            $errorMessage = trim($process->getErrorOutput() ?: $process->getOutput());
            $statusMessage = __('La sauvegarde a échoué : :message', [
                'message' => $errorMessage ?: __('Erreur inconnue'),
            ]);

            if (str_contains($errorMessage, 'n\'est pas reconnu')) {
                $statusMessage = __('La commande « :command » est introuvable. Installez l\'outil MySQL CLI ou spécifiez son chemin via l\'env `DB_BACKUP_COMMAND`.', [
                    'command' => $dumpCommand,
                ]);
            }

            return redirect()
                ->route('superadmin.db')
                ->with('status', $statusMessage);
        }

        $disk = Storage::disk('local');
        $directory = 'db-backups';
        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        $fileName = 'db-backup-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = "{$directory}/{$fileName}";
        $disk->put($path, $process->getOutput());

        return redirect()
            ->route('superadmin.db')
            ->with('status', __('Sauvegarde créée : :path', [
                'path' => $path,
            ]))
            ->with('backup_file', $fileName);
    }

    public function downloadBackup(string $file)
    {
        $disk = Storage::disk('local');
        $path = "db-backups/{$file}";

        if (!$disk->exists($path)) {
            abort(404);
        }

        return $disk->download($path, $file);
    }
}
