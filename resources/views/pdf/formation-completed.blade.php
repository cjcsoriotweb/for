<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Formation - {{ $formationWithProgress->title }}</title>
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

        .certificate-info {
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

        .progress-section {
            margin-bottom: 30px;
        }

        .progress-bar {
            background: #e5e7eb;
            height: 20px;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, #10b981, #059669);
            height: 100%;
            border-radius: 10px;
            width: {{ $progress['progress_percent'] ?? 100 }}%;
        }

        .chapters-section {
            margin-bottom: 30px;
        }

        .chapter {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .chapter-header {
            background: #f9fafb;
            padding: 15px;
            font-weight: bold;
            border-bottom: 1px solid #e5e7eb;
        }

        .chapter-completed .chapter-header {
            background: #ecfdf5;
            color: #065f46;
        }

        .lesson {
            padding: 10px 15px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .lesson:last-child {
            border-bottom: none;
        }

        .lesson-completed {
            background: #f0fdf4;
        }

        .lesson-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            display: inline-block;
            text-align: center;
            line-height: 20px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }

        .lesson-completed-icon {
            background: #10b981;
            color: white;
        }

        .lesson-pending-icon {
            background: #e5e7eb;
            color: #6b7280;
        }



        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .completion-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981, #059669);
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
        <div class="title">CERTIFICAT DE FORMATION</div>
        <div class="subtitle">Attestation de réussite</div>
    </div>

    <div class="certificate-info">
        <div class="completion-badge">
            ✓ FORMATION TERMINÉE AVEC SUCCÈS
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Formation :</div>
                <div class="info-value">{{ $formationWithProgress->title }}</div>
            </div>

            @if($formationWithProgress->description)
            <div class="info-row">
                <div class="info-label">Description :</div>
                <div class="info-value">{{ $formationWithProgress->description }}</div>
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

            <div class="info-row">
                <div class="info-label">Date d'inscription :</div>
                <div class="info-value">
                    {{ $progress['enrolled_at'] ? \Carbon\Carbon::parse($progress['enrolled_at'])->format('d/m/Y') : 'N/A' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Date de completion :</div>
                <div class="info-value">
                    {{ $progress['completed_at'] ? \Carbon\Carbon::parse($progress['completed_at'])->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="progress-section">
        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #1f2937;">
            Progression globale
        </h3>
        <div style="font-size: 24px; font-weight: bold; color: #059669; margin-bottom: 10px;">
            {{ $progress['progress_percent'] ?? 100 }}% Complété
        </div>
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
    </div>

    <div class="chapters-section">
        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #1f2937;">
            Détail des chapitres et leçons
        </h3>

        @foreach($chaptersWithLessons as $chapter)
        <div class="chapter {{ $chapter['completed_count'] === $chapter['total_count'] ? 'chapter-completed' : '' }}">
            <div class="chapter-header">
                {{ $chapter['title'] }}
                <span style="float: right; font-weight: normal;">
                    {{ $chapter['completed_count'] }}/{{ $chapter['total_count'] }} leçons terminées
                </span>
            </div>

            @foreach($chapter['lessons'] as $lesson)
            <div class="lesson {{ $lesson['is_completed'] ? 'lesson-completed' : '' }}">
                <div style="display: flex; align-items: center;">
                    <span class="lesson-icon {{ $lesson['is_completed'] ? 'lesson-completed-icon' : 'lesson-pending-icon' }}">
                        {{ $lesson['is_completed'] ? '✓' : '○' }}
                    </span>
                    <span>{{ $lesson['lesson_title'] }}</span>
                </div>
                <div style="font-size: 12px; color: #6b7280;">
                    @if($lesson['lesson_type'] === 'App\Models\VideoContent')
                        Vidéo
                    @elseif($lesson['lesson_type'] === 'App\Models\TextContent')
                        Contenu texte
                    @elseif($lesson['lesson_type'] === 'App\Models\Quiz')
                        Quiz
                    @else
                        Contenu
                    @endif
                    @if($lesson['is_completed'] && $lesson['completed_at'])
                        • Terminé le {{ \Carbon\Carbon::parse($lesson['completed_at'])->format('d/m/Y') }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>



    <div class="footer">
        <p>Ce certificat atteste que l'apprenant a successfully terminé la formation "{{ $formationWithProgress->title }}".</p>
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
