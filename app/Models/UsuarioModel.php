<?php

declare(strict_types=1);

namespace App\Models;

class UsuarioModel extends DBAbstractModel
{
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT id, nombre, email, password_hash, rol, activo FROM usuarios WHERE email = :email LIMIT 1';
        return $this->execute_single_query($sql, [':email' => $email]);
    }
}
