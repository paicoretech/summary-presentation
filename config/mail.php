<?php



return [

    'default' => env('MAIL_MAILER', 'smtp'), // Updated to MAIL_MAILER for Laravel 8

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@paicbd.com'),
        'name' => env('MAIL_FROM_NAME', 'Your App Name'),
    ],
];