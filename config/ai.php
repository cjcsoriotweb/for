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
            'requires_api_key' => true,
        ],

        'ollama' => [
            'api_key' => env('OLLAMA_API_KEY', ''),
            'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434/v1'),
            'chat_endpoint' => env('OLLAMA_CHAT_ENDPOINT', '/chat/completions'),
            'default_model' => env('OLLAMA_DEFAULT_MODEL', 'llama3'),
            'temperature' => (float) env('OLLAMA_TEMPERATURE', 0.7),
            'requires_api_key' => filter_var(env('OLLAMA_REQUIRES_API_KEY', false), FILTER_VALIDATE_BOOLEAN),
        ],
    ],
];
