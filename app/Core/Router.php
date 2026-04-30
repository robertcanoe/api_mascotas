<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'OPTIONS' => [],
    ];

    public function get(string $path, string $handler, array $options = []): void
    {
        $this->addRoute('GET', $path, $handler, $options);
    }

    public function post(string $path, string $handler, array $options = []): void
    {
        $this->addRoute('POST', $path, $handler, $options);
    }

    public function put(string $path, string $handler, array $options = []): void
    {
        $this->addRoute('PUT', $path, $handler, $options);
    }

    public function delete(string $path, string $handler, array $options = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $options);
    }

    public function options(string $path, string $handler, array $options = []): void
    {
        $this->addRoute('OPTIONS', $path, $handler, $options);
    }

    private function addRoute(string $method, string $path, string $handler, array $options): void
    {
        [$regex, $paramNames] = $this->compilePath($path);

        $this->routes[$method][] = [
            'path' => $this->normalizePath($path),
            'regex' => $regex,
            'param_names' => $paramNames,
            'handler' => $handler,
            'middleware' => $options['middleware'] ?? [],
        ];
    }

    public function match(string $method, string $uri): ?array
    {
        $method = strtoupper($method);
        $cleanUri = $this->cleanUri($uri);

        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $route) {
            if (!preg_match($route['regex'], $cleanUri, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['param_names'] as $paramName) {
                if (isset($matches[$paramName])) {
                    $params[$paramName] = urldecode($matches[$paramName]);
                }
            }

            return [
                'path' => $route['path'],
                'handler' => $route['handler'],
                'middleware' => $route['middleware'],
                'params' => $params,
            ];
        }

        return null;
    }

    private function cleanUri(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (defined('BASE_PATH') && BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) {
            $path = substr($path, strlen(BASE_PATH));
            if ($path === '' || $path === false) {
                $path = '/';
            }
        }

        return $this->normalizePath($path);
    }

    private function normalizePath(string $path): string
    {
        $normalized = '/' . trim($path, '/');
        if ($normalized === '//') {
            return '/';
        }

        return $normalized;
    }

    private function compilePath(string $path): array
    {
        $paramNames = [];
        $normalized = $this->normalizePath($path);

        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $match) use (&$paramNames): string {
            $paramNames[] = $match[1];
            return '(?P<' . $match[1] . '>[^/]+)';
        }, $normalized);

        return ['#^' . $regex . '$#', $paramNames];
    }
}
