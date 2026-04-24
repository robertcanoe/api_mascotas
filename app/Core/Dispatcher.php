<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\ApiController;
use Throwable;

class Dispatcher
{
    public function dispatch(?array $route, array $request): void
    {
        if ($route === null) {
            $this->respondError(404, 'Route not found', $request);
            return;
        }

        try {
            [$controllerClass, $action] = $this->resolveHandler($route['handler']);

            if (!class_exists($controllerClass)) {
                throw new HttpException(404, 'Controller not found');
            }

            $controller = new $controllerClass();
            if (!method_exists($controller, $action)) {
                throw new HttpException(404, 'Action not found');
            }

            $request['params'] = $route['params'] ?? [];
            $request['route'] = $route;

            foreach ($route['middleware'] ?? [] as $middlewareClass) {
                if (!class_exists($middlewareClass)) {
                    throw new HttpException(500, 'Middleware not found');
                }

                $middleware = new $middlewareClass();
                if (!method_exists($middleware, 'handle')) {
                    throw new HttpException(500, 'Invalid middleware signature');
                }

                $request = $middleware->handle($request);
            }

            $args = array_values($route['params'] ?? []);
            $args[] = $request;
            call_user_func_array([$controller, $action], $args);
        } catch (HttpException $exception) {
            $this->respondError($exception->getStatusCode(), $exception->getMessage(), $request, $route);
        } catch (Throwable $exception) {
            app_log($exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine(), 'ERROR');
            $this->respondError(500, 'Internal Server Error', $request, $route);
        }
    }

    private function resolveHandler(string $handler): array
    {
        if (!str_contains($handler, '@')) {
            throw new HttpException(500, 'Invalid handler definition');
        }

        [$controller, $action] = explode('@', $handler, 2);
        $controllerClass = 'App\\Controllers\\' . $controller;

        return [$controllerClass, $action];
    }

    private function respondError(int $statusCode, string $message, array $request = [], ?array $route = null): void
    {
        $expectsJson = $this->expectsJson($request, $route);

        if ($expectsJson) {
            ApiController::setApiHeaders();
            http_response_code($statusCode);
            echo json_encode([
                'error' => $this->statusLabel($statusCode),
                'message' => $message,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $baseController = new BaseController();
        $baseController->showError($message, $statusCode);
    }

    private function expectsJson(array $request, ?array $route): bool
    {
        if (isset($route['response']) && $route['response'] === 'html') {
            return false;
        }

        if (isset($route['response']) && $route['response'] === 'json') {
            return true;
        }

        $accept = '';
        foreach (($request['headers'] ?? []) as $name => $value) {
            if (strtolower((string) $name) === 'accept') {
                $accept = strtolower((string) $value);
                break;
            }
        }

        return str_contains($accept, 'application/json');
    }

    private function statusLabel(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            default => 'Internal Server Error',
        };
    }
}
