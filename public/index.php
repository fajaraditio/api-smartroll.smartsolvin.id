<?php

require __DIR__ . '/../bootstrap/app.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Ciao!");
    return $response->withHeader('Content-Type', 'text/plain');
});

$app->group('/auth', function (RouteCollectorProxy $group) {
    $group->get('/me',       [App\Controllers\AuthController::class, 'me']);
    $group->post('/login',   [App\Controllers\AuthController::class, 'login']);
});

$app->group('/rolls', function (RouteCollectorProxy $group) {
    $group->get('',             [App\Controllers\RollController::class, 'list']);
    $group->post('',            [App\Controllers\RollController::class, 'create']);
    $group->get('/{id}',        [App\Controllers\RollController::class, 'getById']);
    $group->put('/{id}',        [App\Controllers\RollController::class, 'update']);
    $group->delete('/{id}',     [App\Controllers\RollController::class, 'delete']);
});

$app->run();
