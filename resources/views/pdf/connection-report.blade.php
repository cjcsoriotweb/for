<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Connexion - {{ $formation->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .report-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 0;
            width: 200px;
            color: #374151;
        }

        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #111827;
        }

        .stats-section {
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }

        .daily-activities {
            margin-bottom: 30px;
        }

        .day-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .day-header {
            background: #f9fafb;
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .day-date {
            font-weight: bold;
            color: #1f2937;
        }

        .day-stats {
            font-size: 14px;
            color: #6b7280;
        }

        .activity-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: #6b7280;
        }

        .activity-url {
            color: #374151;
            font-family: monospace;
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-duration {
            color: #059669;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .report-badge {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }

        @media print {
            body {
                padding: 15mm;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">RAPPORT DE CONNEXION</div>
        <div class="subtitle">Activité de formation détaillée</div>
    </div>

    <div class="report-info">
        <div class="report-badge">
            📊 RAPPORT D'ACTIVITÉ DÉTAILLÉ
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Formation :</div>
                <div class="info-value">{{ $formation->title }}</div>
            </div>

            @if($formation->description)
            <div class="info-row">
                <div class="info-label">Description :</div>
                <div class="info-value">{{ $formation->description }}</div>
            </div>
            @endif

            <div class="info-row">
                <div class="info-label">Apprenant :</div>
                <div class="info-value">{{ $user->name }} ({{ $user->email }})</div>
            </div>

            <div class="info-row">
                <div class="info-label">Équipe :</div>
                <div class="info-value">{{ $team->name }}</div>
            </div>

            @if($formationUser && $formationUser->enrolled_at)
            <div class="info-row">
                <div class="info-label">Date d'inscription :</div>
                <div class="info-value">{{ $formationUser->enrolled_at->format('d/m/Y') }}</div>
            </div>
            @endif

            @if($formationUser && $formationUser->completed_at)
            <div class="info-row">
                <div class="info-label">Date de completion :</div>
                <div class="info-value">{{ $formationUser->completed_at->format('d/m/Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="stats-section">
        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #1f2937;">
            Statistiques générales
        </h3>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalSessions }}</div>
                <div class="stat-label">Sessions de connexion</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ $formattedTotalDuration }}</div>
                <div class="stat-label">Temps total passé</div>
            </div>

            @if($firstConnection)
            <div class="stat-card">
                <div class="stat-value">{{ $firstConnection->format('d/m/Y') }}</div>
                <div class="stat-label">Première connexion</div>
            </div>
            @endif

            @if($lastConnection)
            <div class="stat-card">
                <div class="stat-value">{{ $lastConnection->format('d/m/Y') }}</div>
                <div class="stat-label">Dernière connexion</div>
            </div>
            @endif
        </div>
    </div>

    <div class="daily-activities">
        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #1f2937;">
            Activité par jour ({{ $dailyActivities->count() }} jours d'activité)
        </h3>

        @forelse($dailyActivities as $day)
        <div class="day-card">
            <div class="day-header">
                <div class="day-date">{{ $day['formatted_date'] }}</div>
                <div class="day-stats">
                    {{ $day['session_count'] }} session{{ $day['session_count'] > 1 ? 's' : '' }} • {{ $day['formatted_duration'] }}
                </div>
            </div>

            @if($day['activities']->count() > 0)
            @foreach($day['activities'] as $activity)
            <div class="activity-item">
                <div class="activity-time">{{ $activity->created_at->format('H:i:s') }}</div>
                <div class="activity-url" title="{{ $activity->url }}">
                    {{ $activity->getLessonName() ?: $activity->getPageType() }}
                </div>
                <div class="activity-duration">
                    {{ $activity->formatted_duration }}
                </div>
            </div>
            @endforeach
            @else
            <div class="activity-item">
                <div style="width: 100%; text-align: center; color: #6b7280;">
                    Aucune activité détaillée disponible pour cette journée
                </div>
            </div>
            @endif
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: #6b7280; font-style: italic;">
            Aucune activité enregistrée pour cette période.
        </div>
        @endforelse
    </div>

    <div class="footer">
        <p>Ce rapport détaille l'activité de connexion de l'apprenant pour la formation "{{ $formation->title }}".</p>
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
