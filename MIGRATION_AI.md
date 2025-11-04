# Migration Guide - Nouvelle Architecture IA

## Vue d'ensemble

La refactorisation de l'architecture IA simplifie radicalement le système en éliminant la dépendance à la base de données pour les trainers et en centralisant toute la logique dans quelques composants clés.

## Changements majeurs

### 🗑️ Supprimé

- **Modèles DB** : `AiTrainer`, tables `ai_trainers`, `ai_trainer_formation`
- **Composants Livewire** : `AssistantChat`, `FormationChat`, `TrainerManager`, `TrainerTester`
- **Services** : `AiConversationService`, `ChatCompletionClient` (anciens)
- **Routes** : `/superadmin/ai-trainers`, `/formateur/formation/{id}/ai`
- **Seeder** : `AiTrainerSeeder`

### ✨ Ajouté

- **OllamaClient** : Client HTTP unique (`app/Services/Ai/OllamaClient.php`)
- **AiController** : Endpoint API unique (`app/Http/Controllers/AiController.php`)
- **Composants Livewire dédiés** : `chat.ai-chat` (assistant IA) et `chat.user-chat` (user↔user)
- **Configuration** : Trainers définis dans `config/ai.php`
- **Service Provider** : `AiServiceProvider` pour l'injection de dépendances

## Configuration des trainers

Les trainers sont maintenant définis dans `config/ai.php` :

```php
'trainers' => [
    'default' => [
        'slug' => 'default',
        'name' => 'Assistant Evolubat',
        'description' => '...',
        'system_prompt' => '...',
        'model' => 'llama3',
        'temperature' => 0.7,
        'guard' => 'normal',
    ],
    // ... autres trainers
]
```

Pour ajouter un nouveau trainer, il suffit d'ajouter une entrée dans cette configuration.

## Migration de la base de données

Exécutez la migration pour supprimer les anciennes tables :

```bash
php artisan migrate
```

Cette migration :
- Supprime les tables `ai_trainers` et `ai_trainer_formation`
- Supprime la colonne `ai_trainer_id` de `ai_conversations`

**Note** : Les conversations existantes sont préservées. Le trainer utilisé sera stocké dans `metadata`.

## Utilisation dans le code

### Avant (❌ ancien code)

```php
// Ancien composant
<livewire:ai.assistant-chat :prompt="$prompt" />

// Ancienne relation
$formation->aiTrainers()->sync([...]);
```

### Après (✅ nouveau code)

```blade
{{-- Nouveau composant --}}
<livewire:chat.ai-chat contact-id="ai_{{ $trainerId }}" title="Assistance Maçonnerie" />
```

```php
// Plus de relation aiTrainers - tout est dans la config
$trainer = config('ai.trainers.michel');
```

## API streaming

L'endpoint `/api/ai/stream` retourne un flux NDJSON (Server-Sent Events) :

```javascript
// Exemple d'utilisation
const response = await fetch('/api/ai/stream', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'text/event-stream',
    },
    body: JSON.stringify({
        message: 'Ma question',
        trainer: 'michel',
        conversation_id: 123, // optionnel
    }),
});

// Lire le stream
const reader = response.body.getReader();
const decoder = new TextDecoder();

while (true) {
    const { done, value } = await reader.read();
    if (done) break;
    
    const chunk = decoder.decode(value);
    const lines = chunk.split('\n');
    
    for (const line of lines) {
        if (line.startsWith('data: ')) {
            const data = JSON.parse(line.substring(6));
            
            if (data.type === 'chunk') {
                // Afficher le contenu progressivement
                console.log(data.content);
            } else if (data.type === 'done') {
                // Réponse complète
            } else if (data.type === 'error') {
                // Gérer l'erreur
                console.error(data.message);
            }
        }
    }
}
```

## Garde-fous et sécurité

1. **Validation des inputs** : Messages limités à 2000 caractères
2. **Authentification** : Endpoint protégé par `auth:sanctum`
3. **Logs** : En développement seulement
4. **Timeouts** : Configurable (60s par défaut)
5. **Prompts système** : Définissent les limites de chaque trainer

## Avantages de la nouvelle architecture

✅ **Simplicité** : Un seul flux pour toutes les interactions IA  
✅ **Maintenabilité** : Modification des trainers sans migration DB  
✅ **Performance** : Streaming en temps réel  
✅ **Flexibilité** : Ajout de trainers par simple configuration  
✅ **Pas de code mort** : Suppression de 2500+ lignes inutiles  

## Dépannage

### Le chat ne s'affiche pas
- Vérifiez que `OLLAMA_BASE_URL` est correctement configuré
- Vérifiez que le service Ollama est démarré
- Vérifiez les logs Laravel : `php artisan pail`

### Erreur "Trainer not found"
- Vérifiez que le slug du trainer existe dans `config/ai.php`
- Clearlez le cache de config : `php artisan config:clear`

### Le streaming ne fonctionne pas
- Vérifiez que nginx/apache ne buffer pas les réponses
- Ajoutez `X-Accel-Buffering: no` dans la config nginx si nécessaire

## Support

Pour toute question sur la migration, consultez :
- Le README principal
- La configuration `config/ai.php`
- Le code des composants `chat.ai-chat` et `chat.user-chat` comme exemples d'implémentation
