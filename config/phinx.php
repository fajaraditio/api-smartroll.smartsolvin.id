<?php

return
    [
        'paths' => [
            'migrations' => __DIR__ . '/../database/migrations',
            'seeds' => __DIR__ . '/../database/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => env('APP_ENV', 'development'),
            'production' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'name' => env('DB_DATABASE', 'production_db'),
                'user' => env('DB_USERNAME', 'root'),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'name' => env('DB_DATABASE', 'development_db'),
                'user' => env('DB_USERNAME', 'root'),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'name' => env('DB_DATABASE', 'testing_db'),
                'user' => env('DB_USERNAME', 'root'),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation'
    ];
