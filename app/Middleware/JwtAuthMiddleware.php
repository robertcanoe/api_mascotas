<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\HttpException;
use App\Services\TokenService;

class JwtAuthMiddleware
{
    private TokenService $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    public function handle(array $request): array
    {
        $headers = $request['headers'] ?? [];
        $authorization = $this->getAuthorizationHeader($headers);

        if ($authorization === '') {
            throw new HttpException(401, 'Authorization header is required.');
        }

        if (!preg_match('/^Bearer\s+(.+)$/i', $authorization, $matches)) {
            throw new HttpException(401, 'Invalid authorization format.');
        }

        $payload = $this->tokenService->validate(trim($matches[1]));
        $request['auth'] = $payload;

        return $request;
    }

    private function getAuthorizationHeader(array $headers): string
    {
        foreach ($headers as $name => $value) {
            if (strtolower((string) $name) === 'authorization') {
                return (string) $value;
            }
        }

        return '';
    }
}
