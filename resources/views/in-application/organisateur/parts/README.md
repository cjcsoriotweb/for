# Composants Parts - Organisateur

Cette documentation présente les composants réutilisables créés pour les vues de l'organisateur.

## Composants disponibles

### 1. Breadcrumb (`breadcrumb.blade.php`)
Navigation fil d'Ariane réutilisable.

**Props:**
- `team` (required) - L'équipe courante
- `formation` (optional) - La formation courante
- `student` (optional) - L'étudiant courant
- `currentPage` (optional) - Le nom de la page courante

**Exemple:**
```blade
<x-organisateur.parts.breadcrumb :team="$team" :formation="$formation" currentPage="Coût mensuel" />
```

### 2. Stats Cards (`stats-cards.blade.php`)
Cartes de statistiques avec design cohérent.

**Props:**
- `stats` (array) - Les statistiques à afficher (total, completed, in_progress, monthly_cost, monthly_enrollments)
- `type` (string) - Type de vue ('default' ou 'students')
- `team`, `formation`, `monthlyCost`, `monthlyEnrollmentsCount` - Variables contextuelles

**Exemple:**
```blade
<x-organisateur.parts.stats-cards :stats="$stats" type="students" :team="$team" :formation="$formation" />
```

### 3. Filters (`filters.blade.php`)
Composant de filtres pour la recherche et le tri.

**Props:**
- `search`, `statusFilter`, `selectedMonth` - Valeurs actuelles des filtres
- `availableMonths` - Liste des mois disponibles (pour la page coût)
- `routeName`, `routeParams` - Route pour la soumission du formulaire

**Exemple:**
```blade
<x-organisateur.parts.filters :search="$search" :statusFilter="$statusFilter" routeName="organisateur.formations.students" :routeParams="[$team, $formation]" />
```

### 4. Student Card (`student-card.blade.php`)
Carte détaillée d'un étudiant avec progression et informations.

**Props:**
- `summary` - Objet résumé de l'étudiant
- `formation`, `team` - Contexte pour les liens

**Exemple:**
```blade
<x-organisateur.parts.student-card :summary="$summary" :formation="$formation" :team="$team" />
```

### 5. Formation Card (`formation-card.blade.php`)
Carte de présentation d'une formation.

**Props:**
- `formation` - Objet formation

**Exemple:**
```blade
<x-organisateur.parts.formation-card :formation="$formation" />
```

### 6. Empty State (`empty-state.blade.php`)
État vide avec icône et message personnalisables.

**Props:**
- `icon` - Type d'icône ('book', 'users', 'formation', ou personnalisé)
- `title`, `description` - Titre et description
- `action`, `actionText`, `actionUrl` - Action optionnelle

**Exemple:**
```blade
<x-organisateur.parts.empty-state icon="users" title="Aucun élève" description="Aucun élève n'est encore inscrit à cette formation." />
```

### 7. Action Buttons (`action-buttons.blade.php`)
Boutons d'action avec styles cohérents.

**Props:**
- `buttons` - Array de boutons avec type, url, text, et icon optionnel

**Types disponibles:**
- `back` - Bouton de retour
- `pdf` - Bouton pour PDF
- `download` - Bouton de téléchargement
- `primary` - Bouton principal
- `secondary` - Bouton secondaire

**Exemple:**
```blade
<x-organisateur.parts.action-buttons :buttons="[
  ['type' => 'back', 'url' => route('organisateur.index', $team), 'text' => 'Retour aux formations'],
  ['type' => 'pdf', 'url' => route('organisateur.formations.students.report.pdf', [$team, $formation, $student]), 'text' => 'Voir le PDF']
]" />
```

## Structure des fichiers

```
resources/views/clean/organisateur/
├── parts/
│   ├── breadcrumb.blade.php
│   ├── stats-cards.blade.php
│   ├── filters.blade.php
│   ├── student-card.blade.php
│   ├── formation-card.blade.php
│   ├── empty-state.blade.php
│   ├── action-buttons.blade.php
│   └── README.md
├── home.blade.php (refactorisé)
├── students.blade.php (refactorisé)
├── students-cost.blade.php (refactorisé)
├── student-report.blade.php (refactorisé)
└── student-report/
    ├── overview.blade.php (refactorisé)
    ├── progress.blade.php (refactorisé)
    ├── activity.blade.php (refactorisé)
    ├── pdf.blade.php (refactorisé)
    └── quizzes.blade.php (refactorisé)
```

## Avantages de cette refactorisation

1. **Réutilisabilité** - Les composants peuvent être utilisés dans plusieurs vues
2. **Maintenabilité** - Modifications centralisées dans les composants
3. **Cohérence** - Design et comportement uniformes
4. **Lisibilité** - Code plus clair et organisé
5. **Performance** - Réduction de la duplication de code

## Conventions d'utilisation

- Utiliser la syntaxe `<x-organisateur.parts.nom-composant>` pour inclure les composants
- Passer les props avec la syntaxe `:prop="$variable"`
- Respecter les conventions de nommage des props
- Maintenir la cohérence des styles et couleurs

## Tests

Tous les composants ont été testés et sont compatibles avec le système de dark mode existant.
