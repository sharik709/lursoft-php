<?php

return [
    'api_key' => env('LURSOFT_API_KEY'),
    'base_url' => env('LURSOFT_BASE_URL', 'https://api.lursoft.lv'),
    'client_id' => env('LURSOFT_CLIENT_ID'),
    'client_secret' => env('LURSOFT_CLIENT_SECRET'),
    'username' => env('LURSOFT_USERNAME'),
    'password' => env('LURSOFT_PASSWORD'),
    'scope' => env('LURSOFT_SCOPE', 'organization:LURSOFT'),
];
