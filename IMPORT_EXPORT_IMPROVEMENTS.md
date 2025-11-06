# Amélioration du système d'import et d'export

## Vue d'ensemble

Ce document décrit les améliorations apportées au système d'import et d'export des formations sur la plateforme Evolubat.

## Problème initial

Le système existant était limité :
- Export uniquement en ZIP
- Import JSON basique et incomplet
- Import CSV non implémenté
- Pas de validation robuste
- Pas d'historique des imports/exports
- Messages d'erreur peu clairs

## Solutions implémentées

### 1. Exports multiples formats

**Export ZIP (existant amélioré)**
- Format complet avec tous les fichiers médias
- Structure orchestre.json pour l'import
- Métadonnées enrichies

**Export JSON (nouveau)**
- Structure complète avec métadonnées
- Tous types de leçons supportés (text/video/quiz)
- Questions de quiz avec choix de réponses
- Documents de complétion inclus
- Format léger sans fichiers médias

**Export CSV (nouveau)**
- Format tableur compatible Excel/LibreOffice
- Colonnes : Formation, Description, Niveau, Chapitre, Leçon, Type, Contenu, Durée
- Séparateur point-virgule (;)
- Contenu texte nettoyé (strip_tags)

### 2. Imports améliorés

**Import ZIP (amélioré)**
- Validation renforcée du fichier orchestre.json
- Messages d'erreur détaillés et contextuels
- Gestion robuste des fichiers corrompus
- Nettoyage automatique des fichiers temporaires

**Import JSON (amélioré)**
- Validation complète de la structure
- Vérification des champs requis
- Messages d'erreur pointant vers la ligne/chapitre/leçon problématique
- Support de tous les types de leçons

**Import CSV (nouveau)**
- Support des séparateurs , et ;
- Détection automatique du format
- Création intelligente des formations/chapitres
- Gestion des positions
- Statistiques détaillées après import

### 3. Validation et gestion d'erreurs

**Validation JSON**
```php
- Vérification de la syntaxe JSON
- Validation des champs requis (title, chapters, lessons)
- Validation des types de leçons (text/video/quiz)
- Validation de chaque chapitre et leçon
- Messages d'erreur contextuels
```

**Validation CSV**
```php
- Vérification de l'en-tête CSV
- Support multi-séparateurs
- Validation des types de leçons
- Gestion des lignes incomplètes
```

**Gestion d'erreurs**
- Rollback automatique des transactions en cas d'erreur
- Nettoyage des fichiers temporaires
- Logging détaillé pour le débogage
- Messages d'erreur clairs pour l'utilisateur

### 4. Templates téléchargeables

**Template JSON**
- 2 chapitres avec exemples
- 4 leçons de types différents
- Structure complète et commentée
- Prêt à l'emploi

**Template CSV**
- 4 lignes d'exemples
- Tous les types de leçons
- En-tête avec noms de colonnes
- Format compatible Excel

Routes :
- `/formateur/templates/json` - Télécharge exemple JSON
- `/formateur/templates/csv` - Télécharge exemple CSV

### 5. Système de logging et historique

**Nouvelle table : formation_import_export_logs**
```sql
- user_id : utilisateur ayant effectué l'opération
- formation_id : formation concernée (nullable)
- type : import ou export
- format : zip, json ou csv
- filename : nom du fichier
- status : success, failed, partial
- error_message : message d'erreur (nullable)
- stats : statistiques JSON (chapitres, leçons, documents)
- file_size : taille du fichier en bytes
- created_at, updated_at
```

**Historique visuel**
- 10 derniers imports affichés sur la page d'import
- Code couleur : vert (succès) / rouge (échec)
- Liens directs vers les formations
- Affichage du format et de la date
- Messages d'erreur visibles
- Taille de fichier affichée

### 6. Interface utilisateur améliorée

**Page d'import**
- 3 cartes côte à côte (ZIP/JSON/CSV)
- Design moderne avec gradient
- Documentation intégrée
- Liens vers templates
- Section aide détaillée

**Menu export sur formation**
- Dropdown avec 3 options
- Icônes et descriptions
- Format ZIP/JSON/CSV au choix
- JavaScript pour menu déroulant

## Structure des fichiers

### Contrôleurs modifiés
- `FormationExportController.php` - Ajout exports JSON/CSV + logging
- `FormationImportController.php` - Amélioration validation + logging
- `FormateurPageController.php` - Import CSV + JSON amélioré + templates

### Modèles créés
- `FormationImportExportLog.php` - Tracking imports/exports

### Migrations créées
- `2025_11_07_000000_create_formation_import_export_logs_table.php`

### Routes ajoutées
```php
POST /formateur/import/json
POST /formateur/import/csv
GET /formateur/templates/json
GET /formateur/templates/csv
GET /formateur/formation/{formation}/export?format={zip|json|csv}
```

## Utilisation

### Pour exporter une formation

1. Aller sur la page de la formation
2. Cliquer sur le bouton "Exporter"
3. Choisir le format (ZIP/JSON/CSV)
4. Le fichier est téléchargé automatiquement

### Pour importer une formation

**Via ZIP :**
1. Exporter une formation depuis la plateforme
2. Aller sur `/formateur/import`
3. Sélectionner le fichier ZIP
4. Cliquer "Importer"

**Via JSON :**
1. Télécharger le template JSON (ou créer un fichier JSON)
2. Remplir les données selon la structure
3. Importer via l'option JSON
4. Vérifier la formation créée

**Via CSV :**
1. Télécharger le template CSV
2. Ouvrir dans Excel/LibreOffice
3. Remplir les colonnes
4. Sauvegarder en CSV (séparateur ;)
5. Importer via l'option CSV

## Format JSON attendu

```json
{
  "title": "Ma Formation",
  "description": "Description de la formation",
  "level": "beginner",
  "chapters": [
    {
      "title": "Chapitre 1",
      "description": "Description du chapitre",
      "position": 1,
      "lessons": [
        {
          "title": "Leçon texte",
          "type": "text",
          "content": "Contenu de la leçon...",
          "estimated_read_time": 5,
          "position": 1
        },
        {
          "title": "Leçon vidéo",
          "type": "video",
          "content": "https://youtube.com/watch?v=...",
          "duration_minutes": 10,
          "position": 2
        },
        {
          "title": "Quiz",
          "type": "quiz",
          "content": "Description du quiz",
          "position": 3
        }
      ]
    }
  ]
}
```

## Format CSV attendu

```csv
Formation;Description Formation;Niveau;Chapitre;Position Chapitre;Leçon;Type Leçon;Contenu;Durée (minutes);Position Leçon
Ma Formation;Description...;beginner;Chapitre 1;1;Leçon 1;text;Contenu...;5;1
Ma Formation;Description...;beginner;Chapitre 1;1;Vidéo;video;https://...;10;2
```

## Tests recommandés

1. **Export ZIP** : Exporter une formation complète avec vidéos et documents
2. **Export JSON** : Exporter puis ré-importer pour vérifier l'intégrité
3. **Export CSV** : Ouvrir dans Excel pour vérifier le format
4. **Import JSON** : Tester avec le template fourni
5. **Import CSV** : Tester avec différents séparateurs
6. **Erreurs** : Tester avec fichiers invalides pour valider les messages
7. **Historique** : Vérifier que les logs sont bien enregistrés

## Limitations actuelles

- Les fichiers médias (vidéos, documents) ne sont pas inclus dans les exports JSON/CSV
- L'import JSON/CSV crée de nouvelles formations, pas de mise à jour des existantes
- SCORM n'est pas encore implémenté
- Pas de prévisualisation avant import
- Pas d'import incrémental

## Améliorations futures possibles

1. Prévisualisation des données avant import
2. Import incrémental (mise à jour de formations existantes)
3. Support SCORM
4. Export/import des médias en JSON/CSV via liens externes
5. Import depuis Google Drive / Dropbox
6. Planification d'exports automatiques
7. Statistiques avancées sur les imports/exports
8. API REST pour import/export programmatique

## Sécurité

- Validation stricte des types de fichiers
- Limite de taille : 100 Mo (ZIP), 10 Mo (JSON), 5 Mo (CSV)
- Nettoyage automatique des fichiers temporaires
- Transactions avec rollback en cas d'erreur
- Logs pour audit et traçabilité
- Formations importées désactivées par défaut

## Performance

- Import par lots pour CSV
- Transactions DB pour garantir l'intégrité
- Nettoyage automatique des fichiers temporaires
- Index sur la table de logs pour requêtes rapides

## Conclusion

Le système d'import/export est maintenant **complet et fonctionnel** avec :
- ✅ 3 formats d'export (ZIP/JSON/CSV)
- ✅ 3 formats d'import (ZIP/JSON/CSV)
- ✅ Validation robuste
- ✅ Messages d'erreur clairs
- ✅ Historique et logging
- ✅ Templates téléchargeables
- ✅ Interface utilisateur moderne
- ✅ Documentation complète

Le système répond maintenant pleinement à la demande : "j'aimerais que le système d'import et export soit plus complet plus fonctionnel".
