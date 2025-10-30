<?php

return [
    'default_driver' => env('AI_DRIVER', 'ollama'),

    'drivers' => [
        'ollama' => [
            'base_url' => env('AI_OLLAMA_BASE_URL', 'http://localhost:11434'),
            'model' => env('AI_OLLAMA_MODEL', 'llama3'),
            'timeout' => env('AI_OLLAMA_TIMEOUT', 60),
        ],
        // 'openai' => [
        //     'api_key' => env('OPENAI_API_KEY'),
        //     'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        // ],
    ],
];

