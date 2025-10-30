<?php

namespace App\Services;

use App\Models\PageNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class WebsiteErrorService
{
    /**
     * Log a website error
     */
    public function logError(int $errorCode, string $message, string $url, ?Throwable $exception = null, ?Request $request = null): PageNote
    {
        $userId = Auth::id();
        $ipAddress = $request ? $this->getClientIp($request) : request()->ip();
        $userAgent = $request ? $request->userAgent() : null;

        $noteContent = $this->formatErrorMessage($errorCode, $message, $url, $userId, $ipAddress, $userAgent, $exception, $request);
        $noteTitle = $this->formatErrorTitle($errorCode, $message);

        return PageNote::create([
            'user_id' => $userId ?? 1, // Utilisateur système par défaut
            'path' => $url,
            'title' => $noteTitle,
            'content' => $noteContent,
            'is_resolved' => false,
        ]);
    }

    /**
     * Log a 404 error
     */
    public function log404(string $url, ?Request $request = null): PageNote
    {
        return $this->logError(404, 'Page not found', $url, null, $request);
    }

    /**
     * Log a 403 error
     */
    public function log403(string $url, ?Request $request = null): PageNote
    {
        return $this->logError(403, 'Access forbidden', $url, null, $request);
    }

    /**
     * Log a 500 error
     */
    public function log500(string $url, Throwable $exception, ?Request $request = null): PageNote
    {
        return $this->logError(500, 'Internal server error', $url, $exception, $request);
    }

    /**
     * Format error title for PageNote
     */
    private function formatErrorTitle(int $errorCode, string $message): string
    {
        $codeType = match ($errorCode) {
            404 => 'Page non trouvée',
            403 => 'Accès interdit',
            500 => 'Erreur serveur',
            default => "Erreur {$errorCode}"
        };

        // Pour le titre, on garde seulement l'information essentielle
        // Extraire le message principal d'une erreur Laravel (comme "View [...] not found")
        $shortMessage = $message;
        if (preg_match('/^View \[([^\]]+)\] not found/', $message, $matches)) {
            $shortMessage = 'View ['.substr($matches[1], 0, 50).'...]';
        } elseif (strlen($message) > 60) {
            $shortMessage = substr($message, 0, 57).'...';
        }

        return "🚨 {$codeType}: {$shortMessage}";
    }

    /**
     * Format error message for storage
     */
    private function formatErrorMessage(int $errorCode, string $message, string $url, ?int $userId, string $ipAddress, ?string $userAgent, ?Throwable $exception, ?Request $request): string
    {
        $content = "🚨 **Erreur système détectée**\n\n";
        $content .= "**Code d'erreur:** {$errorCode}\n";
        $content .= "**Message:** {$message}\n";
        $content .= "**URL:** {$url}\n";
        $content .= '**Utilisateur:** '.($userId ? "ID: {$userId}" : 'Non connecté')."\n";
        $content .= "**IP:** {$ipAddress}\n";

        if ($userAgent) {
            $content .= "**Navigateur:** {$userAgent}\n";
        }

        if ($request) {
            $requestData = $this->getRequestData($request);
            $content .= "**Méthode:** {$requestData['method']}\n";
            if ($requestData['route_name']) {
                $content .= "**Route:** {$requestData['route_name']}\n";
            }
        }

        if ($exception) {
            $content .= "\n**Stack trace:**\n```\n".$exception->getTraceAsString()."\n```";
        }

        return $content;
    }

    /**
     * Get error statistics
     */
    public function getErrorStatistics(?int $days = 7): array
    {
        // Chercher toutes les notes qui semblent être des erreurs système (basé sur le contenu)
        $query = PageNote::query();

        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        // Filtrer les notes qui contiennent "Erreur système"
        $query->where('content', 'like', '%🚨 **Erreur système détectée**%');

        $notes = $query->with('user')->get();

        if ($notes->isEmpty()) {
            return [
                'total_errors' => 0,
                'unresolved_errors' => 0,
                'errors_by_code' => [],
                'recent_errors' => [],
            ];
        }

        $errorGroups = [];
        foreach ($notes as $note) {
            // Analyser le contenu pour extraire le code d'erreur
            if (preg_match('/\*\*Code d\'erreur:\*\* (\d+)/', $note->content, $matches)) {
                $code = (int) $matches[1];
            } else {
                $code = 0; // Code par défaut
            }

            if (! isset($errorGroups[$code])) {
                $errorGroups[$code] = [
                    'code' => $code,
                    'notes' => [],
                    'resolved' => 0,
                    'total' => 0,
                ];
            }

            $errorGroups[$code]['notes'][] = $note;
            $errorGroups[$code]['total']++;
            if (! $note->is_resolved) {
                $errorGroups[$code]['unresolved'] = ($errorGroups[$code]['unresolved'] ?? 0) + 1;
            }
        }

        $errorsByCode = collect(array_values($errorGroups))->map(function ($group) {
            return [
                'code' => $group['code'],
                'count' => $group['total'],
                'unresolved' => $group['unresolved'],
            ];
        })->sortBy('code')->values();

        $recentErrors = $notes->take(10)->map(function ($note) {
            // Extraire les informations du contenu de la note
            $data = $this->parseErrorMessage($note->content);

            return [
                'id' => $note->id,
                'code' => $data['code'] ?? 0,
                'message' => $data['message'] ?? '',
                'url' => $data['url'] ?? '',
                'user' => $note->user ? $note->user->name : null,
                'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                'resolved' => $note->is_resolved,
            ];
        });

        return [
            'total_errors' => $notes->count(),
            'unresolved_errors' => $notes->where('is_resolved', false)->count(),
            'errors_by_code' => $errorsByCode,
            'recent_errors' => $recentErrors,
        ];
    }

    /**
     * Get unresolved errors
     */
    public function getUnresolvedErrors(?int $limit = null)
    {
        $query = PageNote::where('content', 'like', '%🚨 **Erreur système détectée**%')
            ->where('is_resolved', false)
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Mark an error as resolved
     */
    public function markErrorAsResolved(int $noteId): bool
    {
        $note = PageNote::find($noteId);

        if ($note && str_contains($note->content, '🚨 **Erreur système détectée**')) {
            $note->update(['is_resolved' => true]);

            return true;
        }

        return false;
    }

    /**
     * Delete an error note permanently
     */
    public function deleteError(int $noteId): bool
    {
        $note = PageNote::find($noteId);

        if ($note && str_contains($note->content, '🚨 **Erreur système détectée**')) {
            $note->delete();

            return true;
        }

        return false;
    }

    /**
     * Clean up old resolved errors
     */
    public function cleanupOldResolvedErrors(int $daysToKeep = 30): int
    {
        return PageNote::where('content', 'like', '%🚨 **Erreur système détectée**%')
            ->where('is_resolved', true)
            ->where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }

    /**
     * Parse error message to extract structured data
     */
    private function parseErrorMessage(string $message): array
    {
        $data = [];

        // Extract error code
        if (preg_match('/\*\*Code d\'erreur:\*\* (\d+)/', $message, $matches)) {
            $data['code'] = (int) $matches[1];
        }

        // Extract error message
        if (preg_match('/\*\*Message:\*\* ([^\n]+)/', $message, $matches)) {
            $data['message'] = $matches[1];
        }

        // Extract URL
        if (preg_match('/\*\*URL:\*\* ([^\n]+)/', $message, $matches)) {
            $data['url'] = $matches[1];
        }

        return $data;
    }

    /**
     * Get client IP address
     */
    private function getClientIp(Request $request): string
    {
        // Check for IP in various headers (in order of preference)
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Standard proxy header
            'HTTP_X_REAL_IP',        // Nginx proxy header
            'REMOTE_ADDR',           // Direct connection
        ];

        foreach ($ipHeaders as $header) {
            if ($request->hasHeader($header)) {
                $ip = $request->header($header);

                // Handle comma-separated IPs (take the first one)
                if (str_contains($ip, ',')) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Validate IP address
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $request->ip();
    }

    /**
     * Get relevant request data for logging
     */
    private function getRequestData(Request $request): array
    {
        return [
            'method' => $request->method(),
            'query_params' => $request->query(),
            'route_name' => $request->route() ? $request->route()->getName() : null,
            'headers' => $this->getSafeHeaders($request),
        ];
    }

    /**
     * Get safe headers (excluding sensitive ones)
     */
    private function getSafeHeaders(Request $request): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'set-cookie', 'x-api-key', 'x-auth-token'];

        $headers = [];
        foreach ($request->headers->all() as $key => $values) {
            if (! in_array(strtolower($key), $sensitiveHeaders)) {
                $headers[$key] = $values[0] ?? null; // Take first value
            }
        }

        return $headers;
    }
}
