<?php

require __DIR__ . '/../vendor/autoload.php';

use DI\Bridge\Slim\Bridge as SlimBridge;
use DI\ContainerBuilder;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$debugMode = env('APP_DEBUG', false);

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAttributes(false);

$builder->addDefinitions(require __DIR__ . '/../config/drivers.php');

$container = $builder->build();

$app = SlimBridge::create($container);

$app->addErrorMiddleware($debugMode, $debugMode, $debugMode);
$app->add(new \App\Middleware\CorsMiddleware());

$app->addBodyParsingMiddleware();
