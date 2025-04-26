<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // AI Provider configurations
    'ai_providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_API_BASE', 'https://api.openai.com/v1'),
            'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-3.5-turbo'),
            'default_parameters' => [
                'max_tokens' => 500,
                'temperature' => 0.7,
            ],
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'base_url' => env('ANTHROPIC_API_BASE', 'https://api.anthropic.com/v1'),
            'default_model' => env('ANTHROPIC_DEFAULT_MODEL', 'claude-3-sonnet-20240229'),
            'default_parameters' => [
                'max_tokens' => 500,
                'temperature' => 0.7,
            ],
        ],
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'base_url' => env('GEMINI_API_BASE', 'https://generativelanguage.googleapis.com/v1'),
            'default_model' => env('GEMINI_DEFAULT_MODEL', 'gemini-pro'),
            'default_parameters' => [
                'max_output_tokens' => 500,
                'temperature' => 0.7,
            ],
        ],
        'llama' => [
            'api_key' => env('LLAMA_API_KEY'),
            'base_url' => env('LLAMA_API_BASE', 'http://localhost:8000/v1'),
            'default_model' => env('LLAMA_DEFAULT_MODEL', 'llama-3'),
            'default_parameters' => [
                'max_tokens' => 500,
                'temperature' => 0.7,
            ],
        ],
    ],

];
