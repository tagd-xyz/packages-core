<?php

return [
    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
    ],

    'uploads' => [
        'link_expires_in' => '+20 minutes',
    ],

    'aws' => [
        's3' => [
            'bucket' => ENV('AWS_BUCKET'),
            'region' => ENV('AWS_DEFAULT_REGION'),
            'key' => ENV('AWS_ACCESS_KEY_ID'),
            'secret' => ENV('AWS_SECRET_ACCESS_KEY'),
        ],

        'sih' => [
            'url' => ENV('AWS_SIH_URL'),
        ],

        'cloudfront' => [
            'url' => ENV('AWS_CLOUDFRONT_URL'),
        ],
    ],
];
