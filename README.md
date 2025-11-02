<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Front-end Assets (no build step)

- Tailwind CSS est désormais servi via le CDN officiel (`cdn.tailwindcss.com`) avec configuration inline dans `resources/views/components/ui/layout/meta-header.blade.php`.
- Axios et le bundle PowerGrid sont chargés côté navigateur (axios via jsDelivr, `powergrid.js` exposé par Laravel) : aucune étape de build n'est nécessaire.
- Les commandes `npm run dev` / `npm run build` ont été retirées ; redémarrez simplement vos services PHP/queue et videz le cache navigateur si vous modifiez le style.

## AI Assistant Architecture

Ce projet intègre un système d'assistant IA simplifié utilisant Ollama avec streaming en temps réel.

### 🏗️ Architecture

L'architecture IA a été refactorisée pour être simple et maintenable :

- **Un seul client HTTP** : `App\Services\Ai\OllamaClient` pour communiquer avec Ollama
- **Un seul endpoint API** : `POST /api/ai/stream` pour le streaming NDJSON
- **Un seul composant Livewire** : `ChatBox` pour toutes les interfaces de chat
- **Configuration statique** : Les trainers sont définis dans `config/ai.php` (pas de DB)

### 🎓 Trainers disponibles

Les trainers sont configurés dans `config/ai.php` :

- **default** : Assistant Evolubat généraliste (français, professionnel)
- **michel** : Professeur de maçonnerie (expert bâtiment, sécurité stricte)
- **andreas** : Professeur de musique (pédagogique, motivant)

### ⚙️ Configuration

Variables d'environnement dans `.env` :

```bash
OLLAMA_BASE_URL=http://localhost:11434
OLLAMA_DEFAULT_MODEL=llama3
OLLAMA_TIMEOUT=60
OLLAMA_TEMPERATURE=0.7
AI_DEFAULT_TRAINER_SLUG=default
```

### 🚀 Utilisation

#### Dans une vue Blade :

```blade
<livewire:chat-box 
    trainer="michel" 
    title="Assistance Maçonnerie" 
/>
```

#### Via l'API (pour du JavaScript custom) :

```javascript
const response = await fetch('/api/ai/stream', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'text/event-stream',
    },
    body: JSON.stringify({
        message: 'Comment faire un enduit ?',
        trainer: 'michel',
        conversation_id: 123, // optionnel
    }),
});

// Lire le stream NDJSON
const reader = response.body.getReader();
// ... (voir resources/views/livewire/chat-box.blade.php pour exemple complet)
```

### 🛡️ Garde-fous

- Messages limités à 2000 caractères
- Validation stricte des inputs
- Refus explicite des contenus inappropriés
- Logs en développement uniquement
- Timeout configurable (60s par défaut)

### 📦 Migrations

Pour mettre à jour la base de données (suppression des anciennes tables trainers) :

```bash
php artisan migrate
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


##
* 	* 	* 	* 	* 	/usr/local/bin/php /home/ * /repositories/for/artisan schedule:run >> /dev/null 2>&1