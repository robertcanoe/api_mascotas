<?php

declare(strict_types=1);

namespace App\Controllers;

class IndexController extends ApiController
{
    public function infoAction(array $request = []): void
    {
        $this->respond([
            'data' => [
                'name' => 'API Mascotas',
                'env' => APP_ENV,
                'documentation' => [
                    'endpoints' => 'Ver docs/api/endpoints.md o documentacion MkDocs',
                    'postman' => 'docs/api/postman.md',
                ],
                'endpoints' => [
                    'GET /',
                    'GET /health',
                    'GET /mascotas',
                    'GET /mascotas/{id}',
                    'POST /auth/login',
                    'POST /mascotas (admin, JWT)',
                    'PUT /mascotas/{id} (admin, JWT)',
                    'DELETE /mascotas/{id} (admin, JWT)',
                    'POST /adopciones',
                ],
            ],
        ]);
    }
}
