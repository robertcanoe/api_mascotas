<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\HttpException;

class ApiController extends BaseController
{
    protected function respond(array $payload, int $statusCode = 200): void
    {
        $this->setApiHeaders();
        $this->json($payload, $statusCode);
    }

    protected function respondError(string $message, int $statusCode, array $errors = []): void
    {
        $payload = ['error' => $message];
        if ($errors !== []) {
            $payload['details'] = $errors;
        }

        $this->respond($payload, $statusCode);
    }

    protected function ensurePostRequest(): void
    {
        if (!is_post_request()) {
            throw new HttpException(405, 'Method Not Allowed');
        }
    }

    protected function getRequestData(): array
    {
        return json_input();
    }

    public static function setApiHeaders(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
}
