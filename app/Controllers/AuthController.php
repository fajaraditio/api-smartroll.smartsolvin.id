<?php

namespace App\Controllers;

use App\Services\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;

class AuthController
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request, Response $response, array $args = []): Response
    {
        $data = json_decode((string) $request->getBody(), true) ?? [];

        $validator = new Validator();
        $validation = $validator->validate($data, [
            'email'     => 'required|email',
            'password'  => 'required|min:6'
        ]);

        if ($validation->fails()) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status' =>  'error',
                'code' => 422,
                'messages' => $validation->errors()->firstOfAll()
            ]));

            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        try {
            $result = $this->service->login($data['email'], $data['password']);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status' =>  'error',
                'code' => 401,
                'message' => $e->getMessage()
            ]));

            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($result));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function me(Request $request, Response $response, array $args = []): Response
    {
        $accessToken = $_COOKIE['access_token'] ?? null;

        if (!$accessToken) {
            $response->getBody()->write(json_encode(['error' => 'Unauthenticated']));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        $user = $this->service->getUserByToken($accessToken);

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'Unauthenticated']));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        $response->getBody()->write(json_encode([
            'user' => $user
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
