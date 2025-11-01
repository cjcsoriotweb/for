<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rapport détaillé - {{ $student->name }}</title>
  <style>
    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 12px;
      line-height: 1.4;
      color: #333;
      margin: 0;
      padding: 20px;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #2563eb;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .header h1 {
      font-size: 24px;
      color: #2563eb;
      margin: 0;
      font-weight: bold;
    }

    .header p {
      font-size: 14px;
      color: #666;
      margin: 5px 0 0 0;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-bottom: 25px;
    }

    .stat-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 15px;
      text-align: center;
    }

    .stat-card h3 {
      font-size: 12px;
      color: #64748b;
      margin: 0 0 8px 0;
      font-weight: normal;
    }

    .stat-card .value {
      font-size: 18px;
      font-weight: bold;
      color: #1e293b;
      margin: 0;
    }

    .section {
      margin-bottom: 25px;
    }

    .section-title {
      font-size: 16px;
      font-weight: bold;
      color: #1e293b;
      margin: 0 0 15px 0;
      padding-bottom: 8px;
      border-bottom: 1px solid #e2e8f0;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-bottom: 20px;
    }

    .info-item {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 12px;
    }

    .info-item .label {
      font-size: 11px;
      color: #64748b;
      margin: 0 0 4px 0;
      font-weight: 500;
    }

    .info-item .value {
      font-size: 13px;
      color: #1e293b;
      margin: 0;
      font-weight: 500;
    }

    .status-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 10px;
      font-weight: 500;
      text-transform: uppercase;
    }

    .status-completed {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #bbf7d0;
    }

    .status-in-progress {
      background: #dbeafe;
      color: #1e40af;
      border: 1px solid #93c5fd;
    }

    .status-enrolled {
      background: #f3f4f6;
      color: #374151;
      border: 1px solid #d1d5db;
    }

    .lessons-grid {
      margin-bottom: 20px;
    }

    .chapter-section {
      margin-bottom: 20px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
    }

    .chapter-header {
      background: #f1f5f9;
      padding: 12px 15px;
      border-bottom: 1px solid #e2e8f0;
    }

    .chapter-title {
      font-size: 14px;
      font-weight: bold;
      color: #1e293b;
      margin: 0;
    }

    .lesson-item {
      display: grid;
      grid-template-columns: 30px 1fr auto;
      gap: 12px;
      padding: 12px 15px;
      border-bottom: 1px solid #f1f5f9;
      align-items: center;
    }

    .lesson-item:last-child {
      border-bottom: none;
    }

    .lesson-status {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: bold;
    }

    .status-completed-icon {
      background: #dcfce7;
      color: #166534;
    }

    .status-in-progress-icon {
      background: #dbeafe;
      color: #1e40af;
    }

    .status-enrolled-icon {
      background: #f3f4f6;
      color: #6b7280;
    }

    .lesson-info h4 {
      font-size: 13px;
      color: #1e293b;
      margin: 0 0 4px 0;
      font-weight: 500;
    }

    .lesson-info p {
      font-size: 11px;
      color: #64748b;
      margin: 0;
    }

    .lesson-stats {
      font-size: 11px;
      color: #64748b;
      text-align: right;
    }

    .quiz-attempt {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .quiz-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .quiz-title {
      font-size: 14px;
      font-weight: bold;
      color: #1e293b;
      margin: 0;
    }

    .quiz-date {
      font-size: 11px;
      color: #64748b;
    }

    .quiz-stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-bottom: 12px;
    }

    .quiz-stat {
      text-align: center;
    }

    .quiz-stat .label {
      font-size: 10px;
      color: #64748b;
      margin: 0 0 4px 0;
    }

    .quiz-stat .value {
      font-size: 12px;
      font-weight: bold;
      color: #1e293b;
      margin: 0;
    }

    .answers-section {
      border-top: 1px solid #e2e8f0;
      padding-top: 12px;
    }

    .answers-title {
      font-size: 12px;
      font-weight: bold;
      color: #1e293b;
      margin: 0 0 10px 0;
    }

    .answer-item {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 10px;
      margin-bottom: 8px;
    }

    .answer-header {
      display: flex;
      align-items: flex-start;
      gap: 8px;
    }

    .answer-status {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: bold;
      flex-shrink: 0;
    }

    .answer-correct {
      background: #dcfce7;
      color: #166534;
    }

    .answer-incorrect {
      background: #fef2f2;
      color: #dc2626;
    }

    .answer-content {
      flex: 1;
    }

    .answer-question {
      font-size: 12px;
      font-weight: 500;
      color: #1e293b;
      margin: 0 0 6px 0;
    }

    .answer-choice {
      font-size: 11px;
      margin: 0 0 4px 0;
    }

    .answer-correct-choices {
      font-size: 11px;
      margin: 0;
    }

    .correct-badge {
      display: inline-block;
      background: #dcfce7;
      color: #166534;
      padding: 2px 6px;
      border-radius: 10px;
      font-size: 9px;
      font-weight: 500;
      margin: 1px;
    }

    .footer {
      margin-top: 40px;
      padding-top: 15px;
      border-top: 1px solid #e2e8f0;
      text-align: center;
      font-size: 10px;
      color: #64748b;
    }

    @media print {
      body {
        padding: 0;
      }

      .header {
        margin-bottom: 15px;
      }

      .stats-grid {
        margin-bottom: 20px;
      }

      .section {
        margin-bottom: 20px;
        page-break-inside: avoid;
      }

      .chapter-section {
        page-break-inside: avoid;
      }

      .quiz-attempt {
        page-break-inside: avoid;
      }
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Rapport détaillé</h1>
    <p>{{ $student->name }} - {{ $student->email }}</p>
    <p><strong>Formation:</strong> {{ $formation->title }}</p>
    <p><strong>Équipe:</strong> {{ $team->name }}</p>
    <p><strong>Date du rapport:</strong> {{ now()->format('d/m/Y à H:i') }}</p>
  </div>

  {{-- Statistiques générales --}}
  <div class="stats-grid">
    <div class="stat-card">
      <h3>Leçons</h3>
      <p class="value">{{ $completedLessons }}/{{ $totalLessons }}</p>
    </div>

    <div class="stat-card">
      <h3>Temps passé</h3>
      <p class="value">{{ $totalHours }}h {{ $totalMinutes }}min</p>
    </div>

    <div class="stat-card">
      <h3>Score moyen quiz</h3>
      <p class="value">{{ $averageQuizScore }}%</p>
    </div>

    <div class="stat-card">
      <h3>Tentatives quiz</h3>
      <p class="value">{{ $quizAttempts->count() }}</p>
    </div>
  </div>

  {{-- Informations générales --}}
  <div class="section">
    <h2 class="section-title">Informations générales</h2>
    <div class="info-grid">
      <div class="info-item">
        <div class="label">Statut</div>
        <div class="value">
          @if($studentData->pivot->status === 'completed')
          <span class="status-badge status-completed">Terminée</span>
          @elseif($studentData->pivot->status === 'in_progress')
          <span class="status-badge status-in-progress">En cours</span>
          @else
          <span class="status-badge status-enrolled">Inscrit</span>
          @endif
        </div>
      </div>

      <div class="info-item">
        <div class="label">Score global</div>
        <div class="value">
          @if($studentData->pivot->score_total && $studentData->pivot->max_score_total)
          {{ round(($studentData->pivot->score_total / $studentData->pivot->max_score_total) * 100, 1) }}%
          @else
          N/A
          @endif
        </div>
      </div>

      <div class="info-item">
        <div class="label">Date d'inscription</div>
        <div class="value">
          {{ $studentData->pivot->enrolled_at && $studentData->pivot->enrolled_at instanceof \Carbon\Carbon ?
          $studentData->pivot->enrolled_at->format('d/m/Y à H:i:s') : 'N/A' }}
        </div>
      </div>

      <div class="info-item">
        <div class="label">Dernière activité</div>
        <div class="value">
          {{ $studentData->pivot->last_seen_at && $studentData->pivot->last_seen_at instanceof \Carbon\Carbon ?
          $studentData->pivot->last_seen_at->format('d/m/Y à H:i:s') : 'N/A' }}
        </div>
      </div>

      @if($studentData->pivot->completed_at && $studentData->pivot->completed_at instanceof \Carbon\Carbon)
      <div class="info-item">
        <div class="label">Date de completion</div>
        <div class="value">
          {{ $studentData->pivot->completed_at->format('d/m/Y à H:i:s') }}
        </div>
      </div>
      @endif

      <div class="info-item">
        <div class="label">Progression</div>
        <div class="value">
          {{ $completedLessons }} leçons terminées sur {{ $totalLessons }}
          ({{ $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 1) : 0 }}%)
        </div>
      </div>
    </div>
  </div>

  {{-- Progression par leçon --}}
  <div class="section">
    <h2 class="section-title">Progression détaillée par leçon</h2>
    @if($lessons->count() > 0)
    @foreach($lessons->groupBy('chapters.title') as $chapterTitle => $chapterLessons)
    <div class="chapter-section">
      <div class="chapter-header">
        <h3 class="chapter-title">{{ $chapterTitle }}</h3>
      </div>
      @foreach($chapterLessons as $lesson)
      <div class="lesson-item">
        <div
          class="lesson-status {{ $lesson->pivot->status === 'completed' ? 'status-completed-icon' : ($lesson->pivot->status === 'in_progress' ? 'status-in-progress-icon' : 'status-enrolled-icon') }}">
          @if($lesson->pivot->status === 'completed')
          ✓
          @elseif($lesson->pivot->status === 'in_progress')
          ○
          @else
          ○
          @endif
        </div>
        <div class="lesson-info">
          <h4>{{ $lesson->title }}</h4>
          <p>{{ $lesson->lessonable_type === 'App\Models\VideoContent' ? 'Vidéo' : ($lesson->lessonable_type ===
            'App\Models\TextContent' ? 'Texte' : 'Quiz') }}</p>
        </div>
        <div class="lesson-stats">
          @if($lesson->pivot->watched_seconds)
          {{ floor($lesson->pivot->watched_seconds / 60) }}min
          @endif
          @if($lesson->pivot->read_percent)
          {{ $lesson->pivot->read_percent }}%
          @endif
          @if($lesson->pivot->best_score && $lesson->pivot->max_score)
          {{ round(($lesson->pivot->best_score / $lesson->pivot->max_score) * 100, 1) }}%
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endforeach
    @else
    <p>Aucune leçon disponible.</p>
    @endif
  </div>

  {{-- Historique des quiz --}}
  @if($quizAttempts->count() > 0)
  <div class="section">
    <h2 class="section-title">Historique des quiz</h2>
    @foreach($quizAttempts as $attempt)
    <div class="quiz-attempt">
      <div class="quiz-header">
        <h3 class="quiz-title">{{ $attempt->lesson->title }}</h3>
        <span class="quiz-date">{{ $attempt->created_at->format('d/m/Y H:i') }}</span>
      </div>

      <div class="quiz-stats-grid">
        <div class="quiz-stat">
          <div class="label">Score</div>
          <div class="value">{{ $attempt->score ? round($attempt->score, 1) : 0 }}%</div>
        </div>

        <div class="quiz-stat">
          <div class="label">Temps passé</div>
          <div class="value">{{ $attempt->duration_seconds ? floor($attempt->duration_seconds / 60) . 'min' : 'N/A' }}
          </div>
        </div>

        <div class="quiz-stat">
          <div class="label">Réponses</div>
          <div class="value">{{ $attempt->answers->count() }}/{{ $attempt->lesson->lessonable &&
            $attempt->lesson->lessonable->quizQuestions ? $attempt->lesson->lessonable->quizQuestions->count() : 0 }}
          </div>
        </div>
      </div>

      @if($attempt->answers->count() > 0)
      <div class="answers-section">
        <h4 class="answers-title">Réponses détaillées:</h4>
        @foreach($attempt->answers as $answer)
        <div class="answer-item">
          <div class="answer-header">
            <div class="answer-status {{ $answer->is_correct ? 'answer-correct' : 'answer-incorrect' }}">
              {{ $answer->is_correct ? '✓' : '✗' }}
            </div>
            <div class="answer-content">
              <div class="answer-question">{{ $answer->question ? $answer->question->question : 'Question non trouvée'
                }}</div>

              @if($answer->choice)
              <div class="answer-choice">
                <strong>Réponse choisie:</strong>
                <span style="color: {{ $answer->is_correct ? '#166534' : '#dc2626' }}">
                  {{ $answer->choice->choice_text }}
                </span>
              </div>
              @endif

              @if($answer->question && $answer->question->quizChoices)
              <div class="answer-correct-choices">
                <strong>Réponses correctes:</strong>
                @foreach($answer->question->quizChoices->where('is_correct', true) as $correctChoice)
                <span class="correct-badge">{{ $correctChoice->choice_text }}</span>
                @endforeach
              </div>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
    @endforeach
  </div>
  @endif

  {{-- Rapport de connexion et activité --}}
  @php
  $activityLogs = isset($activityLogs) ? $activityLogs : collect();
  $activitySummary = isset($activitySummary) ? $activitySummary : [];
  @endphp

  @if($activityLogs->count() > 0)
  <div class="section">
    <h2 class="section-title">Rapport de connexion</h2>

    {{-- Résumé d'activité --}}
    @if(isset($activitySummary) && $activitySummary['total_sessions'] > 0)
    <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
      <div class="stat-card">
        <h3>Sessions</h3>
        <p class="value">{{ $activitySummary['total_sessions'] }}</p>
      </div>
      <div class="stat-card">
        <h3>Pages vues</h3>
        <p class="value">{{ $activitySummary['total_page_views'] }}</p>
      </div>
      <div class="stat-card">
        <h3>IPs uniques</h3>
        <p class="value">{{ $activitySummary['unique_ips'] }}</p>
      </div>
      <div class="stat-card">
        <h3>Temps moyen</h3>
        <p class="value">
          @if($activitySummary['average_session_duration'] > 0)
          {{ floor($activitySummary['average_session_duration'] / 60) }}min
          @else
          N/A
          @endif
        </p>
      </div>
    </div>
    @endif

    {{-- Tableau des connexions --}}
    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
      <thead>
        <tr style="background-color: #f1f5f9;">
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">Date/Heure</th>
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">IP</th>
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">Navigateur</th>
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">Appareil</th>
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">Page</th>
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">Durée</th>
          <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-weight: bold;">Méthode</th>
        </tr>
      </thead>
      <tbody>
        @foreach($activityLogs as $activity)
        <tr>
          <td style="border: 1px solid #e2e8f0; padding: 6px;">{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
          <td style="border: 1px solid #e2e8f0; padding: 6px;">{{ $activity->formatted_ip }}</td>
          <td style="border: 1px solid #e2e8f0; padding: 6px;">{{ $activity->browser_info ?? 'N/A' }}</td>
          <td style="border: 1px solid #e2e8f0; padding: 6px;">{{ $activity->device_type }}</td>
          <td
            style="border: 1px solid #e2e8f0; padding: 6px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            {{ $activity->url ? parse_url($activity->url, PHP_URL_PATH) : 'N/A' }}
          </td>
          <td style="border: 1px solid #e2e8f0; padding: 6px;">{{ $activity->formatted_duration }}</td>
          <td style="border: 1px solid #e2e8f0; padding: 6px;">
            <span
              style="background: {{ $activity->method === 'GET' ? '#dcfce7' : '#dbeafe' }}; color: {{ $activity->method === 'GET' ? '#166534' : '#1e40af' }}; padding: 2px 6px; border-radius: 10px; font-size: 9px;">
              {{ $activity->method }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <p style="font-size: 10px; color: #64748b; margin-top: 10px; text-align: right;">
      Affichage des {{ $activityLogs->count() }} dernières activités sur {{ $activitySummary['total_page_views'] ?? 0 }}
      totales
    </p>
  </div>
  @endif

  <div class="footer">
    <p>Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    <p>Plateforme de formation - {{ config('app.name') }}</p>
  </div>
</body>

</html>