<?php

use DI\Bridge\Slim\Bridge as SlimBridge;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAttributes(false);

$container = $builder->build();

$app = SlimBridge::create($container);

$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Ciao!");
    return $response;
});

$app->post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);

$app->run();
