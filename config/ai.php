<?php

return [
    'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'),
    'default_model' => env('OLLAMA_DEFAULT_MODEL', 'llama3'),
    'timeout' => (int) env('OLLAMA_TIMEOUT', 60),
    'temperature' => (float) env('OLLAMA_TEMPERATURE', 0.7),

    'history_limit' => 30,

    'max_message_length' => 2000,

    'default_trainer_slug' => env('AI_DEFAULT_TRAINER_SLUG', 'default'),

    // Legacy fallback. Trainers are now managed in the ai_trainers table.
    'trainers' => [],

    'shortcodes' => [
        'SUPPORT' => [
            'view' => 'components.ai.shortcodes.support',
        ],
        'DECONNEXION' => [
            'view' => 'components.ai.shortcodes.logout',
        ],
    ],
];
