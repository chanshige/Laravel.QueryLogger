<?php

declare(strict_types=1);

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

return [
    'default' => 'stack',
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => BASE_DIR . 'storage/logs/laravel.log',
            'level' => 'debug',
        ],

        'output' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => LineFormatter::class,
            'formatter_with' => ['format' => "%channel%.%level_name%: %message% %context% %extra%\n"],
            'with' => ['stream' => 'php://output'],
        ],
    ],
];
