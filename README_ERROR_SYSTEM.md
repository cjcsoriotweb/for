# Website Error Logging System

## Vue d'ensemble

Le système de gestion des erreurs du website permet de capturer, stocker et analyser toutes les erreurs survenues sur le site. Cela inclut les erreurs 403, 404, 500, et autres erreurs HTTP.

## Composants créés

### 1. Table `website_errors`
- `error_code` : Code d'erreur HTTP (403, 404, 500, etc.)
- `message` : Description courte de l'erreur
- `url` : URL où l'erreur s'est produite
- `user_id` : Utilisateur connecté (optionnel)
- `ip_address` : Adresse IP du client
- `user_agent` : Navigateur/client utilisé
- `request_data` : Données de la requête (JSON)
- `stack_trace` : Trace d'exception pour les erreurs 500
- `resolved_at` : Timestamp de résolution (nullable)

### 2. Modèle `WebsiteError`
- Relations avec User
- Scopes pour filtrer les erreurs (unresolved, byCode)
- Méthodes utilitaires

### 3. Service `WebsiteErrorService`
- `logError()` : Logger une erreur générique
- `log404()`, `log403()`, `log500()` : Méthodes spécialisées
- `getErrorStatistics()` : Statistiques des erreurs
- `getUnresolvedErrors()` : Erreurs non résolues
- `markErrorAsResolved()` : Marquer une erreur comme résolue

### 4. Commande Artisan `verifyerror`
Cette commande permet de gérer les erreurs via la ligne de commande.

## Utilisation de la commande

### Afficher les statistiques
```bash
php artisan verifyerror --stats
php artisan verifyerror --stats --days=30
```

### Lister les erreurs récentes
```bash
php artisan verifyerror
php artisan verifyerror --limit=20
php artisan verifyerror --code=404
```

### Lister les erreurs non résolues
```bash
php artisan verifyerror --unresolved
```

### Marquer une erreur comme résolue
```bash
php artisan verifyerror --resolve=123
```

## Exemple d'utilisation dans le code

### Logger une erreur depuis un contrôleur
```php
use App\Services\WebsiteErrorService;

$errorService = app(WebsiteErrorService::class);

// Logger une erreur 404
$errorService->log404(request()->fullUrl(), request());

// Logger une erreur 403
$errorService->log403(request()->fullUrl(), request());

// Logger une erreur 500 avec exception
try {
    // code qui peut échouer
} catch (Throwable $e) {
    $errorService->log500(request()->fullUrl(), $e, request());
}
```

### Logger une erreur générique
```php
$errorService->logError(
    errorCode: 422,
    message: 'Validation error',
    url: request()->fullUrl(),
    request: request()
);
```

## Handler d'exception global (recommandé)

Pour capturer automatiquement les erreurs non gérées, ajoutez dans `app/Exceptions/Handler.php` :

```php
use App\Services\WebsiteErrorService;
use Throwable;

public function render($request, Throwable $exception)
{
    $errorService = app(WebsiteErrorService::class);

    if ($exception instanceof HttpException) {
        $statusCode = $exception->getStatusCode();
        $message = $exception->getMessage();

        $errorService->logError($statusCode, $message, $request->fullUrl(), $exception, $request);
    }

    return parent::render($request, $exception);
}
```

## Nettoyage automatique

Pour nettoyer les anciennes erreurs résolues (plus de 30 jours) :
```php
// Dans une commande artisan ou scheduled task
$errorService->cleanupOldResolvedErrors(30);
```

## Sécurité

Le système ne stocke que les données de requête non sensibles. Les headers comme `Authorization`, `Cookie`, etc. sont automatiquement filtrés.
