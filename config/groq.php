<?php

return [
    'api_key' => env('GROQ_API_KEY'),
    'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    'api_url' => 'https://api.groq.com/openai/v1/chat/completions',
    'max_tokens' => 1024,
    'temperature' => 0.7,
];
