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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/auth/google/callback'),
    ],

    'turnstile' => [
        'site_key'   => env('TURNSTILE_SITE_KEY', '0x4AAAAAAEThLG8THi390oLg'),
        'secret_key' => env('TURNSTILE_SECRET_KEY', '0x4AAAAAAEThLDr7uQjeTpEz5Gc4Drm6zus'),
    ],

    'firebase' => [
        'credentials'         => env('FIREBASE_CREDENTIALS'),
        'api_key'             => env('FIREBASE_API_KEY'),
        'project_id'          => env('FIREBASE_PROJECT_ID'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'app_id'              => env('FIREBASE_APP_ID'),
        'vapid_key'           => env('FIREBASE_VAPID_KEY'),
    ],

];
