<?php

require __DIR__ . '/../bootstrap/app.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Ciao!");
    return $response;
});

$app->get('/auth/me',       [App\Controllers\AuthController::class, 'me']);
$app->post('/auth/login',   [App\Controllers\AuthController::class, 'login']);

$app->run();
