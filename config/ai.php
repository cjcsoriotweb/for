<?php

return [
    'default_driver' => env('AI_DRIVER', 'ollama'),

    'drivers' => [
        'ollama' => [
            'base_url' => env('AI_OLLAMA_BASE_URL', 'http://localhost:11434'),
            'model' => env('AI_OLLAMA_MODEL', 'llama3'),
            'timeout' => env('AI_OLLAMA_TIMEOUT', 60),
        ],
    ],

    'providers' => [
        'ollama' => [
            'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434/v1'),
            'chat_endpoint' => env('OLLAMA_CHAT_ENDPOINT', '/chat/completions'),
            'default_model' => env('OLLAMA_DEFAULT_MODEL', 'llama3'),
            'temperature' => env('OLLAMA_TEMPERATURE', 0.7),
            'requires_api_key' => env('OLLAMA_REQUIRES_API_KEY', false),
        ],
    ],

    'conversation' => [
        'history_limit' => 50,
    ],

    'default_trainer_slug' => env('AI_DEFAULT_TRAINER_SLUG', 'assistant-ia-generaliste'),

    'default_site_trainer' => [
        'slug' => env('AI_DEFAULT_TRAINER_SLUG', 'assistant-ia-generaliste'),
        'name' => env('AI_DEFAULT_TRAINER_NAME', 'Assistant IA Generaliste'),
        'provider' => env('AI_DEFAULT_TRAINER_PROVIDER', env('AI_DRIVER', 'ollama')),
        'model' => env('AI_DEFAULT_TRAINER_MODEL', 'llama3'),
        'description' => env('AI_DEFAULT_TRAINER_DESCRIPTION', 'Assistant IA polyvalent utilisant Ollama pour une utilisation locale.'),
        'prompt' => <<<'PROMPT'
Tu es un assistant IA polyvalent et amical. Tu aides les utilisateurs avec diverses questions et demandes.
Tu réponds de manière claire, précise et utile. Tu t'adaptes au niveau de l'utilisateur et fournis des informations pertinentes.

Règles importantes :
- Réponds toujours en français à moins que l'utilisateur te demande explicitement d'autres langues
- Sois pédagogique et explique les concepts complexes simplement
- Si tu ne sais pas quelque chose, dis-le honnêtement
- Sois positif et encourageant
PROMPT,
        'settings' => [
            'temperature' => (float) env('AI_DEFAULT_TRAINER_TEMPERATURE', 0.7),
        ],
    ],
];
