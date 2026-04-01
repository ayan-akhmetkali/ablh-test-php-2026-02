<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, callable> */
    private array $routes = [];

    private $notFoundHandler = null;

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET ' . $path] = $handler;
    }

    public function setNotFoundHandler(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $normalized = rtrim($path, '/');
        $normalized = $normalized === '' ? '/' : $normalized;

        foreach ($this->routes as $routeKey => $handler) {
            [$routeMethod, $routePath] = explode(' ', $routeKey, 2);

            if ($routeMethod !== $method) {
                continue;
            }

            $pattern = '#^' . preg_replace('#\{[^/]+\}#', '([^/]+)', $routePath) . '$#';
            if (!preg_match($pattern, $normalized, $matches)) {
                continue;
            }

            array_shift($matches);
            $handler(...$matches);
            return;
        }

        if (is_callable($this->notFoundHandler)) {
            ($this->notFoundHandler)();
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
