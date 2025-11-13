# Evolubat - Plateforme de Formation en Ligne

## À propos du projet

Evolubat est une plateforme complète de gestion et de suivi de formations en ligne construite avec Laravel 12. Elle permet aux formateurs de créer et gérer des formations, aux organisateurs de suivre leurs équipes, et aux élèves d'accéder à du contenu pédagogique interactif.

## 🚀 Fonctionnalités principales

### 🎓 Système de Formation

- **Création de formations** : Interface intuitive pour créer des formations structurées
- **Organisation en chapitres et leçons** : Structure hiérarchique (Formation → Chapitre → Leçon)
- **Types de contenu variés** :
  - Vidéos hébergées
  - Contenu texte/HTML enrichi
  - Quiz évaluatifs
  - Documents joints
- **Quiz d'entrée** : Évaluation préalable des connaissances avant accès à la formation
- **Quiz intégrés** : Quiz de validation des connaissances après chaque leçon
- **Suivi de progression** : Tracking automatique de l'avancement des élèves
- **Certificats de complétion** : Génération automatique de documents PDF d'attestation
- **Import/Export** : Import de formations complètes via fichiers ZIP
- **Catégories de formation** : Organisation et classification des formations

### 👥 Gestion Multi-Rôles

#### Administrateur (Admin)
- Gestion des utilisateurs de l'équipe
- Vue d'ensemble des formations disponibles
- Activation/désactivation des formations pour l'équipe
- Suivi des étudiants et de leur progression
- Gestion des crédits de l'équipe
- Personnalisation du profil de l'équipe (logo, informations)
- Accès aux statistiques détaillées

#### Formateur
- Création et modification de formations
- Gestion des chapitres et leçons
- Upload de vidéos et documents
- Création et édition de quiz
- Suivi des étudiants inscrits
- Export de formations
- Gestion des documents de complétion

#### Organisateur
- Vue catalogue des formations disponibles
- Gestion des utilisateurs de l'équipe
- Suivi détaillé des étudiants par formation
- Rapports de progression en PDF
- Rapports de connexion en PDF
- Vue des coûts d'inscription
- Recharge de crédits via Stripe

#### Élève
- Accès aux formations assignées
- Navigation intuitive entre chapitres et leçons
- Lecture de vidéos avec player intégré
- Passage de quiz avec feedback immédiat
- Consultation de la progression
- Téléchargement des certificats de complétion
- Rapport de connexion personnalisé

#### Superadmin
- Configuration des catégories de formation
- Accès à tous les espaces de l'application

#### Intégration avec les formations
- Contexte adapté au contenu pédagogique
- Support instantané pour les élèves

### 💳 Système de Crédits et Paiements

- **Crédits d'équipe** : Système de crédits pour gérer les inscriptions
- **Coût d'inscription** : Déduction automatique des crédits lors de l'inscription
- **Historique des transactions** : Traçabilité complète des mouvements de crédits
- **Recharge via Stripe** : Intégration Stripe pour l'achat de crédits
- **Gestion administrative** : Les admins peuvent ajouter des crédits manuellement

### 📊 Suivi et Reporting

- **Logs d'activité utilisateur** : Traçage détaillé des actions (connexions, inscriptions, complétions)
- **Statistiques de formation** : Taux de complétion, temps passé, résultats aux quiz
- **Rapports PDF** :
  - Certificats de complétion
  - Rapports de connexion
  - Rapports de progression des étudiants
- **Système de notation** : Notes de page pour les élèves avec discussions

### 🛠️ Système de Gestion des Erreurs

Service complet de logging et monitoring des erreurs :

#### Fonctionnalités
- Capture automatique des erreurs HTTP (403, 404, 500, etc.)
- Stockage en base de données avec détails complets
- Traçabilité : URL, utilisateur, IP, user agent, données de requête
- Stack traces pour les erreurs 500
- Résolution et suivi des erreurs
- Commande Artisan `verifyerror` pour la gestion CLI

#### Utilisation
```bash
# Afficher les statistiques
php artisan verifyerror --stats

# Lister les erreurs récentes
php artisan verifyerror --limit=20

# Lister les erreurs non résolues
php artisan verifyerror --unresolved

# Marquer une erreur comme résolue
php artisan verifyerror --resolve=123
```

### 🎨 Interface et UX

- **Tailwind CSS** : Design moderne et responsive via CDN
- **Composants Livewire** : Interactions temps réel sans rechargement de page
- **Laravel Jetstream** : Authentification et gestion d'équipes intégrée
- **Heroicons** : Bibliothèque d'icônes intégrée
- **Notifications temps réel** : Système de notifications avec badge

### 🔐 Sécurité et Authentification

- **Laravel Sanctum** : Authentification API sécurisée
- **Authentification 2FA** : Support de l'authentification à deux facteurs
- **Gestion d'équipes** : Isolation des données par équipe (Jetstream)
- **Middlewares personnalisés** : Protection des routes par rôle
- **Policies** : Autorisation fine des actions
- **Validation stricte** : Form Requests pour toutes les entrées utilisateur

### 📱 Support et Communication

- **Système de tickets** : Gestion complète des demandes de support
- **Chat intégré** : Messagerie entre utilisateurs
- **Invitations d'équipe** : Système d'invitation par email
- **Notifications** : Centre de notifications avec cloche interactive

## 📋 Prérequis

- PHP 8.2 ou supérieur
- Composer
- Base de données (SQLite, MySQL, PostgreSQL)
- Stripe Account (pour les paiements) - optionnel
- Node.js et NPM (pour le développement frontend) - optionnel

## 🔧 Installation

### 1. Cloner le repository

```bash
git clone <repository-url>
cd for
```

### 2. Installation des dépendances

```bash
composer install
```

### 3. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configuration de la base de données

Éditez le fichier `.env` et configurez votre connexion à la base de données :

```env
DB_CONNECTION=sqlite
# ou pour MySQL/PostgreSQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evolubat
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Exécuter les migrations

```bash
php artisan migrate
```

### 6. Configuration de Stripe (optionnel)

Pour les paiements, ajoutez vos clés Stripe dans `.env` :

```env
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### 8. Lancer l'application

```bash
# Méthode 1 : Serveur de développement Laravel
php artisan serve

# Méthode 2 : Via Composer
composer run dev
```

L'application sera accessible sur `http://localhost:8000`

### 9. Créer votre premier utilisateur

Utilisez la console Laravel pour créer un super administrateur :

```bash
php artisan tinker
```

Puis dans la console :

```php
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@evolubat.com',
    'password' => bcrypt('password'),
    'superadmin' => true,
]);
```

## 🎯 Utilisation

### Démarrage rapide

1. **Connectez-vous** avec les identifiants créés
2. **Créez une équipe** (via Jetstream)
3. **Créez votre première formation** (rôle Formateur)
4. **Ajoutez des chapitres et leçons**
5. **Invitez des élèves** à rejoindre votre équipe

### Workflow typique

#### En tant que Formateur
1. Accéder à l'espace Formateur
2. Créer une nouvelle formation
3. Définir le titre, description et image de couverture
4. Ajouter des chapitres
5. Pour chaque chapitre, ajouter des leçons (vidéo, texte, quiz)
6. Configurer un quiz d'entrée (optionnel)
7. Publier la formation

#### En tant qu'Administrateur
1. Accéder à l'espace Administrateur
2. Activer les formations souhaitées pour votre équipe
3. Inviter des utilisateurs (élèves, formateurs)
4. Gérer les crédits de l'équipe
5. Suivre la progression des élèves

#### En tant qu'Élève
1. Se connecter à la plateforme
2. Voir les formations disponibles
3. S'inscrire à une formation (consomme des crédits de l'équipe)
4. Passer le quiz d'entrée si requis
5. Suivre les leçons dans l'ordre
6. Passer les quiz de validation
7. Obtenir le certificat de complétion

## 📚 Structure du projet

```
app/
├── Actions/           # Actions Jetstream
├── Console/          # Commandes Artisan
├── Http/
│   ├── Controllers/  # Contrôleurs
│   ├── Middleware/   # Middlewares personnalisés
│   └── Requests/     # Form Requests
├── Livewire/         # Composants Livewire
├── Models/           # Modèles Eloquent
├── Policies/         # Policies d'autorisation
├── Services/         # Services métier
│   └── Formation/   # Services formations
└── View/            # View Composers

config/

database/
├── migrations/      # Migrations de la base de données
└── seeders/        # Seeders

resources/
├── views/          # Templates Blade
└── markdown/       # Documents Markdown

routes/
├── AdminRoute.php       # Routes Admin
├── FormateurRoute.php   # Routes Formateur
├── EleveRoute.php       # Routes Élève
├── OrganisateurRoute.php # Routes Organisateur
├── SuperadminRoute.php  # Routes Superadmin
├── UserRoute.php        # Routes Utilisateur
└── api.php             # Routes API
```

## 🧪 Tests

```bash
# Exécuter tous les tests
php artisan test

# Ou via Composer
composer test
```

## 🔍 Analyse de code

Le projet utilise PHPStan et Larastan pour l'analyse statique :

```bash
# Analyse avec PHPStan
vendor/bin/phpstan analyse
```

## 🎨 Style de code

Laravel Pint est configuré pour le formatage du code :

```bash
# Formater le code
vendor/bin/pint
```

## 🚀 Commandes utiles

```bash
# Nettoyer le cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Lister les routes
php artisan route:list

# Logs en temps réel
php artisan pail

# Gestion des erreurs
php artisan verifyerror --stats

# Queue worker (pour les jobs asynchrones)
php artisan queue:work
```

## 📦 Architecture technique

### Stack technologique

- **Backend** : Laravel 12 (PHP 8.2+)
- **Frontend** : Livewire 3.6 + Tailwind CSS 3.4
- **Base de données** : Support MySQL, PostgreSQL, SQLite
- **Authentification** : Laravel Sanctum + Jetstream
- **Paiements** : Stripe
- **PDF** : DomPDF

### Patterns et principes

- **Architecture MVC** : Séparation claire des responsabilités
- **Services Layer** : Logique métier dans des services dédiés
- **Repository Pattern** : Via les modèles Eloquent
- **Form Requests** : Validation centralisée
- **Policies** : Autorisation déclarative
- **Livewire Components** : Composants réactifs sans JavaScript

## 🔒 Sécurité

### Bonnes pratiques implémentées

- ✅ Validation stricte de toutes les entrées utilisateur
- ✅ Protection CSRF sur tous les formulaires
- ✅ Autorisation via Policies et Middlewares
- ✅ Hachage sécurisé des mots de passe (bcrypt)
- ✅ Support de l'authentification 2FA
- ✅ Limitation des requêtes API (rate limiting)
- ✅ Sanitization des sorties pour prévenir XSS
- ✅ Requêtes préparées (prévention SQL injection)
- ✅ Logging des erreurs et des activités sensibles
- ✅ Timeouts configurables pour les requêtes externes

### Système de logging des erreurs

Voir le fichier `README_ERROR_SYSTEM.md` pour plus de détails sur le système de gestion des erreurs.

## 📖 Documentation supplémentaire

- **Système d'erreurs** : Voir `README_ERROR_SYSTEM.md` pour le système de logging des erreurs

## 🤝 Contribution

Ce projet est actuellement en développement actif. Pour contribuer :

1. Fork le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

### Standards de code

- Suivre les conventions PSR-12
- Utiliser Laravel Pint pour le formatage
- Ajouter des tests pour les nouvelles fonctionnalités
- Documenter les nouvelles fonctionnalités importantes

## 📝 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

Pour toute question ou problème :

1. Consultez cette documentation
2. Vérifiez les logs : `php artisan pail`
3. Utilisez la commande `verifyerror` pour analyser les erreurs
4. Consultez la documentation Laravel : https://laravel.com/docs

## 🎓 Ressources

- [Documentation Laravel](https://laravel.com/docs)
- [Documentation Livewire](https://livewire.laravel.com)
- [Documentation Tailwind CSS](https://tailwindcss.com/docs)
- [Documentation Ollama](https://ollama.ai)
- [Documentation Stripe](https://stripe.com/docs)

---

Développé avec ❤️ pour faciliter l'apprentissage en ligne.
