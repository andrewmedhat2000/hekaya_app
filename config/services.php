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
    'twilio' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_ACCOUNT_TOKEN'),
        'service_sid' => env('TWILIO_SERVICE_SID'),
        'key' => env('TWILIO_API_KEY'),
        'secret' => env('TWILIO_API_SECRET'),
        'twiml' => env('TWILIO_VOICE_TWIML'),
        'push_sid' => env('TWILIO_PUSH_SID'),
        'number' => env('TWILIO_NUMBER'),
        'notify' => env('TWILIO_NOTIFY_SID')
    ],
    'firebase' => [
        'server_key' => env('FCM_SERVER_KEY')
    ],

    'facebook' => [
        'app_secret' => env('FACEBOOK_APP_SECRET')
    ],

    'agora' => [
        'app_id' => env('AGORA_APP_ID'),
        'certificate' => env('AGORA_APP_CERTIFICATE')
    ],

];
