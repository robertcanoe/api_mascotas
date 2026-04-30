<?php

declare(strict_types=1);

namespace App\Models;

class MascotaModel extends DBAbstractModel
{
    public function getAll(): array
    {
        $sql = 'SELECT id, nombre, especie, edad, foto_url, descripcion FROM mascotas WHERE activo = 1 ORDER BY id DESC';
        return $this->get_results_from_query($sql);
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, nombre, especie, edad, foto_url, descripcion FROM mascotas WHERE id = :id AND activo = 1 LIMIT 1';
        return $this->execute_single_query($sql, [':id' => $id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO mascotas (nombre, especie, edad, foto_url, descripcion, activo, created_at, updated_at)
                VALUES (:nombre, :especie, :edad, :foto_url, :descripcion, 1, NOW(), NOW())';

        $this->execute_non_query($sql, [
            ':nombre' => $data['nombre'],
            ':especie' => $data['especie'],
            ':edad' => $data['edad'],
            ':foto_url' => $data['foto_url'],
            ':descripcion' => $data['descripcion'],
        ]);

        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE mascotas
                SET nombre = :nombre,
                    especie = :especie,
                    edad = :edad,
                    foto_url = :foto_url,
                    descripcion = :descripcion,
                    updated_at = NOW()
                WHERE id = :id AND activo = 1';


        // tenemos que persistir los datos en la base de datos, con this
        $affected = $this->execute_non_query($sql, [
            ':id' => $id,
            ':nombre' => $data['nombre'],
            ':especie' => $data['especie'],
            ':edad' => $data['edad'],
            ':foto_url' => $data['foto_url'],
            ':descripcion' => $data['descripcion'],
        ]);

        return $affected >= 0;
    }

    public function delete(int $id): bool
    {
        $sql = 'UPDATE mascotas SET activo = 0, updated_at = NOW() WHERE id = :id';
        $affected = $this->execute_non_query($sql, [':id' => $id]);

        return $affected > 0;
    }
}
