<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(private array|null $config = null)
    {
        if (!isset($this->config)) {
            $this->config = require __DIR__ . '/../../config/cors.php';
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $origin = rtrim($request->getHeaderLine("Origin"), "/");

        $isAllowed = in_array($origin, $this->config["origins"] ?? [], true);

        // Handle preflight
        if ($request->getMethod() === "OPTIONS") {
            $response = new Response(204);
            return $isAllowed ? $this->withCorsHeaders($response, $origin) : $response;
        }

        $response = $handler->handle($request);

        return $isAllowed ? $this->withCorsHeaders($response, $origin) : $response;
    }

    private function withCorsHeaders(ResponseInterface $response, string $origin): ResponseInterface
    {
        return $response
            ->withHeader("Access-Control-Allow-Origin", $origin)
            ->withHeader("Access-Control-Allow-Methods", $this->config["methods"])
            ->withHeader("Access-Control-Allow-Headers", $this->config["headers"])
            ->withHeader("Access-Control-Allow-Credentials", $this->config["credentials"] ? "true" : "false")
            ->withHeader("Access-Control-Max-Age", (string)$this->config["max_age"]);
    }
}
