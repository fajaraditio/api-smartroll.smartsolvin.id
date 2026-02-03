<?php

namespace App\Controllers;

use App\Services\InventoryService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;

class RollController
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function getById(Request $request, Response $response, array $args = []): Response
    {
        $id = $args['id'] ?? $request->getAttribute('id') ?? null;

        if (!$id) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Missing roll ID'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $roll = $this->inventoryService->getRollById($id);
            if (!$roll) {
                throw new Exception('Roll not found');
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 404,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data'    => $roll
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function list(Request $request, Response $response, array $args = []): Response
    {
        $queryParams = $request->getQueryParams();
        $page = (int) ($queryParams['page'] ?? 1);
        $perPage = (int) ($queryParams['per_page'] ?? 10);

        try {
            $result = $this->inventoryService->listRolls($page, $perPage);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 400,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'page'    => $page,
            'per_page' => $perPage,
            'data'    => $result['data'],
            'total'   => $result['total']
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response, array $args = []): Response
    {
        $data = json_decode((string) $request->getBody(), true) ?? [];

        $validator = new Validator();
        $validator->addValidator('unique', new \App\Rules\UniqueRule($this->inventoryService->getPdo()));
        $validation = $validator->validate($data, [
            'name'            => 'required|unique:rolls,name',
            'width'           => 'required|numeric|min:0.01',
            'length'          => 'required|numeric|min:0.01',
            'thickness'       => 'required|numeric|min:0.001',
            'price_per_meter' => 'required|numeric|min:0'
        ]);

        if ($validation->fails()) {
            $response->getBody()->write(json_encode([
                'success'   => false,
                'status'    =>  'error',
                'code'      => 422,
                'messages'  => $validation->errors()->firstOfAll()
            ]));

            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        try {
            $result = $this->inventoryService->createRoll($data);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'success'   => false,
                'status'    => 'error',
                'code'      => 400,
                'message'   => $e->getMessage()
            ]));

            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($result));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args = []): Response
    {
        $id = $args['id'] ?? $request->getAttribute('id') ?? null;

        if (!$id) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Missing roll ID'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $data = json_decode((string) $request->getBody(), true) ?? [];

        $validator = new Validator();
        $validation = $validator->validate($data, [
            'name'            => 'sometimes|required',
            'width'           => 'sometimes|required|numeric|min:0.01',
            'length'          => 'sometimes|required|numeric|min:0.01',
            'thickness'       => 'sometimes|required|numeric|min:0.001',
            'price_per_meter' => 'sometimes|required|numeric|min:0'
        ]);

        if ($validation->fails()) {
            $response->getBody()->write(json_encode([
                'success'   => false,
                'status'    => 'error',
                'code'      => 422,
                'messages'  => $validation->errors()->firstOfAll()
            ]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        try {
            $result = $this->inventoryService->updateRoll($id, $data);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 400,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'status'  => 'updated',
            'id'      => $id
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args = []): Response
    {
        $id = $args['id'] ?? $request->getAttribute('id') ?? null;

        if (!$id) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Missing roll ID'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $this->inventoryService->deleteRoll($id);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'status'  => 'error',
                'code'    => 400,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'status'  => 'deleted',
            'id'      => $id
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
