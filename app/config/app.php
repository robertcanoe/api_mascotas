<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Protectora de Mascotas',
        'env' => env('APP_ENV', 'production'),
        'debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOL),
        'url' => rtrim((string) env('APP_URL', ''), '/'),
        'timezone' => env('TIMEZONE', 'UTC'),
    ],
    'db' => [
        'driver' => env('DB_DRIVER', 'pgsql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '5432'),
        'name' => env('DB_NAME', ''),
        'user' => env('DB_USER', ''),
        'pass' => env('DB_PASS', ''),
        'charset' => env('DB_CHARSET', 'UTF8'),
        'schema' => env('DB_SCHEMA', 'public'),
        'sslmode' => env('DB_SSLMODE', 'prefer'),
    ],
    'jwt' => [
        'secret' => env('JWT_SECRET', ''),
        'issuer' => env('JWT_ISSUER', 'protectora-api'),
        'ttl' => (int) env('JWT_TTL', 3600),
    ],
    'auth' => [
        'admin_email' => env('ADMIN_EMAIL', 'admin@protectora.local'),
        'admin_password_hash' => env('ADMIN_PASSWORD_HASH', ''),
    ],
];
