<?php

return [
    'default_trainer_slug' => env('AI_DEFAULT_TRAINER', 'ia-formateur-generaliste'),

    'conversation' => [
        'history_limit' => (int) env('AI_CONVERSATION_HISTORY_LIMIT', 30),
    ],

    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'chat_endpoint' => env('OPENAI_CHAT_ENDPOINT', '/chat/completions'),
            'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4o-mini'),
            'temperature' => (float) env('OPENAI_TEMPERATURE', 0.7),
        ],
    ],
];
