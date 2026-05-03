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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        // Optional second bot/chat (legacy send_notification2). Falls back to primary if unset.
        'bot_token_2' => env('TELEGRAM_BOT_TOKEN_2'),
        'chat_id_2' => env('TELEGRAM_CHAT_ID_2'),
        // Set to false only if the server cannot verify TLS to api.telegram.org (diagnose with php artisan telegram:test).
        'verify_ssl' => filter_var(env('TELEGRAM_HTTP_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
    ],

];
