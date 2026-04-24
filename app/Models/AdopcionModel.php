<?php

declare(strict_types=1);

namespace App\Models;

class AdopcionModel extends DBAbstractModel
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO adopciones (mascota_id, solicitante, email, mensaje, created_at)
                VALUES (:mascota_id, :solicitante, :email, :mensaje, NOW())';

        $this->execute_non_query($sql, [
            ':mascota_id' => $data['mascota_id'],
            ':solicitante' => $data['solicitante'],
            ':email' => $data['email'],
            ':mensaje' => $data['mensaje'],
        ]);

        return (int) $this->lastInsertId();
    }
}
