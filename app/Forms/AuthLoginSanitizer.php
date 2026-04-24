<?php

declare(strict_types=1);

namespace App\Forms;

class AuthLoginSanitizer
{
    public function sanitize(array $input): array
    {
        return [
            'email' => trim((string) ($input['email'] ?? '')),
            'password' => (string) ($input['password'] ?? ''),
        ];
    }
}
