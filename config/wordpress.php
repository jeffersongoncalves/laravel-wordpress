<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WordPress Site URL
    |--------------------------------------------------------------------------
    |
    | Base URL of the WordPress site, without the `/wp-json` suffix.
    | Example: https://example.com
    |
    */
    'base_url' => env('WORDPRESS_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | WordPress Credentials
    |--------------------------------------------------------------------------
    |
    | Username and Application Password generated at
    | Users > Your Profile > Application Passwords. They are sent as HTTP
    | Basic credentials on every request to the WordPress REST API.
    |
    */
    'username' => env('WORDPRESS_USERNAME'),
    'application_password' => env('WORDPRESS_APPLICATION_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | REST API Namespace
    |--------------------------------------------------------------------------
    |
    | Route namespace appended to the site URL. Change it only if you target
    | a different WordPress REST namespace (e.g. `wp-json/wc/v3`).
    |
    */
    'namespace' => env('WORDPRESS_NAMESPACE', 'wp-json/wp/v2'),

];
