<?php

declare(strict_types=1);

use App\Core\Logger;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('app_log')) {
    function app_log(string $message, string $level = 'INFO'): void
    {
        Logger::write($message, $level);
    }
}

if (!function_exists('get_request_headers')) {
    function get_request_headers(): array
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            return is_array($headers) ? $headers : [];
        }

        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $key = str_replace('_', '-', strtolower(substr($name, 5)));
                $headers[$key] = $value;
            }
        }

        return $headers;
    }
}

if (!function_exists('json_input')) {
    function json_input(): array
    {
        $contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));

        if (str_contains($contentType, 'application/json')) {
            $rawBody = file_get_contents('php://input') ?: '';
            if ($rawBody === '') {
                return [];
            }

            $decoded = json_decode($rawBody, true);
            return is_array($decoded) ? $decoded : [];
        }

        return $_POST;
    }
}

if (!function_exists('is_post_request')) {
    function is_post_request(): bool
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) === 'POST';
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = defined('BASE_URL') ? BASE_URL : rtrim((string) env('APP_URL', ''), '/');
        $path = ltrim($path, '/');

        if ($path === '') {
            return $base;
        }

        return $base . '/' . $path;
    }
}
