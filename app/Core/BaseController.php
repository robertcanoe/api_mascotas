<?php

declare(strict_types=1);

namespace App\Core;

class BaseController
{
    protected function redirect(string $url, int $statusCode = 302): void
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    protected function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
