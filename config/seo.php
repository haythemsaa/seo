<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    */

    'rank_tracking' => [
        'enabled' => true,
        'default_search_engine' => 'google',
        'default_device' => 'desktop',
        'default_location' => 'France',
        'check_frequency' => 'daily', // daily, weekly, monthly
    ],

    'technical_audit' => [
        'enabled' => true,
        'default_crawl_type' => 'full', // full, incremental, targeted
        'schedule' => 'weekly',
    ],

    'backlinks' => [
        'enabled' => true,
        'check_frequency' => 'weekly',
        'apis' => [
            'majestic' => env('MAJESTIC_API_KEY') !== null,
            'moz' => env('MOZ_ACCESS_ID') !== null,
            'ahrefs' => env('AHREFS_API_KEY') !== null,
        ],
    ],

    'content_analysis' => [
        'enabled' => true,
        'min_word_count' => 300,
        'optimal_word_count' => 600,
        'max_keyword_density' => 3.0,
        'optimal_keyword_density' => [1.0, 2.0],
    ],

    'ai_recommendations' => [
        'enabled' => true,
        'auto_generate_after_crawl' => true,
        'max_recommendations' => 50,
    ],

    'reports' => [
        'default_format' => 'pdf',
        'logo_path' => 'images/logo.png',
        'primary_color' => '#2563eb',
    ],

    'rate_limits' => [
        'free' => 100,
        'starter' => 500,
        'professional' => 2000,
        'agency' => 10000,
        'enterprise' => 50000,
    ],
];
