<?php

declare(strict_types=1);

return [
    'default' => 'test',
    'connections' => [
        'test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ],
    ],
    'migrations' => 'migrations',
];
