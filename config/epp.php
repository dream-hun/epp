<?php

return [
    /*
    |--------------------------------------------------------------------------
    | EPP Server Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the EPP server connection
    |
    */

    'host' => env('EPP_HOST', 'epp.example.com'),
    'port' => env('EPP_PORT', 700),
    'timeout' => env('EPP_TIMEOUT', 1),
    'ssl' => env('EPP_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Authentication credentials for the EPP server
    |
    */

    'username' => env('EPP_USERNAME', ''),
    'password' => env('EPP_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | SSL/TLS Configuration
    |--------------------------------------------------------------------------
    |
    | SSL/TLS settings for secure connection
    |
    */

    'ssl_options' => [
        'verify_peer' => env('EPP_SSL_VERIFY_PEER', true),
        'verify_peer_name' => env('EPP_SSL_VERIFY_PEER_NAME', true),
        'allow_self_signed' => env('EPP_SSL_ALLOW_SELF_SIGNED', false),
        'cafile' => env('EPP_SSL_CAFILE', null),
        'local_cert' => env('EPP_SSL_LOCAL_CERT', null),
        'local_pk' => env('EPP_SSL_LOCAL_PK', null),
        'passphrase' => env('EPP_SSL_PASSPHRASE', null),
    ],
];
