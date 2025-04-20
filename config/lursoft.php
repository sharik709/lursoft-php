<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lursoft API Authentication
    |--------------------------------------------------------------------------
    |
    | These are the credentials used to authenticate with the Lursoft API
    | using OAuth 2.0. You need to obtain these credentials from Lursoft.
    |
    */
    'client_id' => env('LURSOFT_CLIENT_ID'),
    'client_secret' => env('LURSOFT_CLIENT_SECRET'),
    'username' => env('LURSOFT_USERNAME'),
    'password' => env('LURSOFT_PASSWORD'),
    'scope' => env('LURSOFT_SCOPE'),

    /*
    |--------------------------------------------------------------------------
    | Lursoft API Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for the Lursoft API. The default URL is the
    | production URL. You can change this for testing purposes.
    |
    */
    'base_url' => env('LURSOFT_BASE_URL', 'https://b2b.lursoft.lv'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | The Lursoft API has rate limits. You can configure whether rate limiting is
    | enabled and how many requests per minute are allowed.
    |
    */
    'rate_limit' => [
        'enabled' => env('LURSOFT_RATE_LIMIT_ENABLED', true),
        'requests_per_minute' => env('LURSOFT_RATE_LIMIT_RPM', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how long access tokens should be cached. The default is 3600
    | seconds (1 hour) which should be suitable for most applications.
    |
    */
    'cache' => [
        'token_ttl' => env('LURSOFT_CACHE_TOKEN_TTL', 3600),
    ],
];
