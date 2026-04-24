<?php

declare(strict_types=1);

namespace App\Forms;

class AuthLoginValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Correo electronico invalido.';
        }

        if (mb_strlen($data['password']) < 6) {
            $errors['password'] = 'La contrasena debe tener al menos 6 caracteres.';
        }

        return $errors;
    }
}
