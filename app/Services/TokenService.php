<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\HttpException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

class TokenService
{
    public function generate(array $user): string
    {
        $now = time();
        $ttl = (int) env('JWT_TTL', 3600);

        $payload = [
            'iss' => (string) env('JWT_ISSUER', 'protectora-api'),
            'iat' => $now,
            'exp' => $now + $ttl,
            'sub' => (int) ($user['id'] ?? 0),
            'email' => (string) ($user['email'] ?? ''),
            'role' => (string) ($user['rol'] ?? 'admin'),
        ];

        return JWT::encode($payload, (string) env('JWT_SECRET', ''), 'HS256');
    }

    public function validate(string $jwt): array
    {
        try {
            $decoded = JWT::decode($jwt, new Key((string) env('JWT_SECRET', ''), 'HS256'));
            return (array) $decoded;
        } catch (Throwable $exception) {
            throw new HttpException(401, 'Invalid or expired token.');
        }
    }
}
