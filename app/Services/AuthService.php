<?php

declare(strict_types=1);

namespace App\Services;

use App\Forms\AuthLoginForm;
use App\Models\DatabaseException;
use App\Models\UsuarioModel;

class AuthService
{
    private AuthLoginForm $form;
    private UsuarioModel $usuarioModel;
    private TokenService $tokenService;

    public function __construct()
    {
        $this->form = new AuthLoginForm();
        $this->usuarioModel = new UsuarioModel();
        $this->tokenService = new TokenService();
    }

    public function login(array $input): array
    {
        $validation = $this->form->validate($input);
        if (!$validation['is_valid']) {
            return [
                'success' => false,
                'status' => 400,
                'errors' => $validation['errors'],
            ];
        }

        $email = strtolower($validation['data']['email']);
        $password = $validation['data']['password'];

        $user = $this->findUser($email);
        if ($user === null || (int) ($user['activo'] ?? 1) !== 1) {
            return [
                'success' => false,
                'status' => 401,
                'message' => 'Credenciales invalidas.',
            ];
        }

        if (!password_verify($password, (string) ($user['password_hash'] ?? ''))) {
            return [
                'success' => false,
                'status' => 401,
                'message' => 'Credenciales invalidas.',
            ];
        }

        $token = $this->tokenService->generate($user);

        return [
            'success' => true,
            'status' => 200,
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => (int) env('JWT_TTL', 3600),
                'user' => [
                    'id' => (int) ($user['id'] ?? 0),
                    'nombre' => (string) ($user['nombre'] ?? 'Administrador'),
                    'email' => (string) ($user['email'] ?? $email),
                    'rol' => (string) ($user['rol'] ?? 'admin'),
                ],
            ],
        ];
    }

    private function findUser(string $email): ?array
    {
        try {
            $user = $this->usuarioModel->findByEmail($email);
            if (is_array($user)) {
                return $user;
            }
        } catch (DatabaseException $exception) {
            $exception->logError();
        }

        return $this->fallbackAdminUser($email);
    }

    private function fallbackAdminUser(string $email): ?array
    {
        $adminEmail = strtolower((string) env('ADMIN_EMAIL', ''));
        $adminHash = (string) env('ADMIN_PASSWORD_HASH', '');

        if ($adminEmail === '' || $adminHash === '' || $email !== $adminEmail) {
            return null;
        }

        return [
            'id' => 1,
            'nombre' => 'Administrador',
            'email' => $adminEmail,
            'password_hash' => $adminHash,
            'rol' => 'admin',
            'activo' => 1,
        ];
    }
}
