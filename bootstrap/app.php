<?php

require __DIR__ . '/../vendor/autoload.php';

use DI\Bridge\Slim\Bridge as SlimBridge;
use DI\ContainerBuilder;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAttributes(false);

$builder->addDefinitions([
    PDO::class => function () {
        return new PDO(
            "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8mb4",
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
]);

$container = $builder->build();

$app = SlimBridge::create($container);

$app->add(new \App\Middleware\CorsMiddleware());

$app->addBodyParsingMiddleware();
