<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController extends ApiController
{
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function loginAction(array $request = []): void
    {
        $this->ensurePostRequest();

        $result = $this->service->login($this->getRequestData());
        $status = (int) ($result['status'] ?? 500);

        if (($result['success'] ?? false) === true) {
            $this->respond([
                'message' => 'Login correcto.',
                'data' => $result['data'],
            ], $status);
            return;
        }

        $this->respondError(
            (string) ($result['message'] ?? 'Credenciales invalidas.'),
            $status,
            $result['errors'] ?? []
        );
    }
}
