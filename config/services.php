<?php

return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER', '201234567890'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
    ],
    'paymob' => [
        'api_key' => env('PAYMOB_API_KEY'),
        'integration_id' => env('PAYMOB_INTEGRATION_ID'),
        'iframe_id' => env('PAYMOB_IFRAME_ID'),
        'hmac' => env('PAYMOB_HMAC'),
    ],
    'vodafone_cash' => [
        'number' => env('VODAFONE_CASH_NUMBER'),
    ],
    'instapay' => [
        'username' => env('INSTAPAY_USERNAME'),
    ],
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],
];
