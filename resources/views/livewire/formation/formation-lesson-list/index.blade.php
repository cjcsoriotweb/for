{{-- Formation Lesson List Components
===============================

Cette vue principale orchestre l'affichage de la liste des leçons en utilisant
des composants modulaires pour une meilleure maintenabilité.

Structure des composants :
- header.blade.php      : En-tête avec titre et bouton d'ajout
- lesson-item.blade.php : Élément individuel d'une leçon avec édition en ligne
- empty-state.blade.php : État vide affiché quand il n'y a pas de leçons

Utilisation :
@include('livewire.formation.formation-lesson-list.header')
@include('livewire.formation.formation-lesson-list.lesson-item', ['lesson' => $lesson])
@include('livewire.formation.formation-lesson-list.empty-state')

Variables requises :
- $lessons : Collection de leçons à afficher
- $lessonEdition : ID de la leçon en cours d'édition (pour l'état d'édition)
- $lessonsById : Array associatif des titres modifiés temporairement

Fonctionnalités supportées :
- Affichage de la liste des leçons
- Édition en ligne des titres
- Suppression avec confirmation
- États de chargement et erreurs
--}}
