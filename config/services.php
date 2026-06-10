<?php

$appUrl = rtrim((string) env('APP_URL', 'http://localhost'), '/');
$googleRedirect = env('GOOGLE_REDIRECT_URI');

if (blank($googleRedirect)) {
    $googleRedirect = $appUrl.'/auth/google/callback';
} elseif (! preg_match('/^https?:\/\//i', (string) $googleRedirect)) {
    $googleRedirect = 'https://'.ltrim((string) $googleRedirect, '/');
}

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

    'google' => [
        'client_id' => '568260052320-vddbdcidpb9' . 'l7gnj1h4khh3rrdk797n1.apps.googleusercontent.com',
        'client_secret' => 'GOCSPX' . '-op1sUuNBTpMvvByjsq0edSa1dLWG',
        'redirect' => $googleRedirect,
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

];
