<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web Crawler Configuration
    |--------------------------------------------------------------------------
    */

    'user_agent' => env('CRAWLER_USER_AGENT', 'SEOMasterProBot/1.0 (+https://seo-master-pro.fr/bot)'),

    'max_depth' => env('CRAWLER_MAX_DEPTH', 10),

    'max_pages' => env('CRAWLER_MAX_PAGES', 100000),

    'requests_per_second' => env('CRAWLER_REQUESTS_PER_SECOND', 5),

    'timeout' => env('CRAWLER_TIMEOUT', 30),

    'respect_robots_txt' => env('CRAWLER_RESPECT_ROBOTS_TXT', true),

    'follow_redirects' => true,

    'max_redirects' => 5,

    'proxy' => [
        'enabled' => env('PROXY_ENABLED', false),
        'host' => env('PROXY_HOST'),
        'port' => env('PROXY_PORT'),
        'username' => env('PROXY_USERNAME'),
        'password' => env('PROXY_PASSWORD'),
    ],

    'skip_extensions' => [
        'pdf', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp',
        'css', 'js', 'xml', 'json',
        'zip', 'rar', 'tar', 'gz',
        'mp3', 'mp4', 'avi', 'mov',
        'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
    ],
];
