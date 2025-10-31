<?php

return [
    'default_driver' => env('AI_DRIVER', 'ollama'),

    'drivers' => [
        'ollama' => [
            'base_url' => env('AI_OLLAMA_BASE_URL', 'http://localhost:11434'),
            'model' => env('AI_OLLAMA_MODEL', 'llama3'),
            'timeout' => env('AI_OLLAMA_TIMEOUT', 60),
        ],
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        ],
    ],

    'providers' => [
        'ollama' => [
            'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434/api'),
            'chat_endpoint' => env('OLLAMA_CHAT_ENDPOINT', '/chat/completions'),
            'default_model' => env('OLLAMA_DEFAULT_MODEL', 'llama3'),
            'temperature' => env('OLLAMA_TEMPERATURE', 0.7),
            'requires_api_key' => env('OLLAMA_REQUIRES_API_KEY', false),
        ],
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'chat_endpoint' => env('OPENAI_CHAT_ENDPOINT', '/chat/completions'),
            'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4o-mini'),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
            'requires_api_key' => true,
        ],
    ],

    'conversation' => [
        'history_limit' => 50,
    ],

    'default_trainer_slug' => 'assistant-ia-generaliste',
];
