<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'prices' => [
            'starter' => env('STRIPE_PRICE_STARTER'),
            'professional' => env('STRIPE_PRICE_PROFESSIONAL'),
            'agency' => env('STRIPE_PRICE_AGENCY'),
        ],
    ],

    'majestic' => [
        'api_key' => env('MAJESTIC_API_KEY'),
    ],

    'moz' => [
        'access_id' => env('MOZ_ACCESS_ID'),
        'secret_key' => env('MOZ_SECRET_KEY'),
    ],

    'ahrefs' => [
        'api_key' => env('AHREFS_API_KEY'),
    ],
];
